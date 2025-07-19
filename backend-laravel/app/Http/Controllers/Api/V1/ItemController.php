<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\BorrowTransaction;
use App\Models\Item;
use App\Http\Requests\V1\StoreItemRequest;
use App\Http\Requests\V1\UpdateItemRequest;
use App\Http\Resources\V1\ItemResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ItemCollection;
use App\Services\V1\ItemService;
use App\Services\V1\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Get all non-deleted items
            $items = Item::all();
            return new ItemCollection($items);
        } catch (\Exception $e) {
            \Log::error('Error fetching items: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch items: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
    
    /**
     * Get active (non-deleted) items
     */
    public function getActiveItems()
    {
        try {
            // Get all items without checking deleted_at for now
            $items = Item::all();
            
            return response()->json([
                'message' => 'Active items retrieved successfully',
                'status' => 'success',
                'data' => ItemResource::collection($items)
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error retrieving active items: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to retrieve active items: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        
        $qrCodeService = new QrCodeService();

        $itemService = new ItemService();

        $newItem = Item::create($request->validated());

        $image = $request->file('image');

        if($image) {
            $itemService->handleImageUpload($newItem, $image);
        } 

        $itemWithQrCode = $qrCodeService->generateQrCode($newItem);

        return new ItemResource($itemWithQrCode);

        // Validate the incoming request
        // $validated = $request->validated();

        // // Save the item (example)
        // $item = Item::create($validated);

        // // Dispatch the QR code generation job
        // GenerateQRCodeJob::dispatch($item);

        // // Return the item as a resource
        // return new ItemResource($item);

    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return new ItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $itemService = new ItemService();
        
        // Update the item with validated data
        $item->update($request->validated());
        
        // Handle image upload if present
        $image = $request->file('image_path');
        if ($image) {
            $itemService->handleImageUpload($item, $image);
        }
        
        // Return the updated item
        return new ItemResource($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($identifier, Request $request)
    {
        try {
            // First try to find by UUID
            $item = Item::where('uuid', $identifier)->first();
            
            // If not found by UUID, try by ID
            if (!$item) {
                // Check if the identifier is numeric (likely an ID)
                if (is_numeric($identifier)) {
                    $item = Item::find($identifier);
                }
                
                // If still not found, throw an exception
                if (!$item) {
                    throw new \Exception("Item not found with identifier: {$identifier}");
                }
            }
            
            // Delete associated QR code if exists
            if ($item->qrCode) {
                $item->qrCode->delete();
            }
            
            // Delete associated image if exists
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            
            // Delete the item (hard delete for now)
            $item->delete();
            
            return response()->json([
                'message' => 'Item deleted successfully',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error deleting item: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete item: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
    
    /**
     * Check if an item exists by UUID
     */
    public function checkItem($uuid)
    {
        try {
            $item = Item::where('uuid', $uuid)->firstOrFail();
            return response()->json([
                'message' => 'Item found',
                'status' => 'success',
                'item' => new ItemResource($item)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Item not found: ' . $e->getMessage(),
                'status' => 'error'
            ], 404);
        }
    }
    
    /**
     * Get all deleted items
     */
    public function getDeletedItems()
    {
        try {
            // For now, return an empty array since we don't have soft deletes yet
            return response()->json([
                'message' => 'Deleted items retrieved successfully',
                'status' => 'success',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error retrieving deleted items: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to retrieve deleted items: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
    
    /**
     * Restore a deleted item
     */
    public function restoreItem($uuid)
    {
        try {
            // For now, just return a success message
            return response()->json([
                'message' => 'Item restore functionality will be available once soft deletes are implemented',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error restoring item: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to restore item: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
    
    /**
     * Permanently delete an item
     */
    public function forceDelete($uuid)
    {
        try {
            // For now, just return a success message
            return response()->json([
                'message' => 'Permanent delete functionality will be available once soft deletes are implemented',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error permanently deleting item: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to permanently delete item: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
    public function borrowItem(Request $request, $uuid)
{
    $item = Item::where('uuid', $uuid)->first();

    if (!$item) {
        return response()->json(['message' => 'Item not found.'], 404);
    }

    $request->validate([
        'quantity' => 'required|integer|min:1',
        'borrowed_by' => 'required|string',
    ]);

    if ($item->quantity < $request->quantity) {
        return response()->json(['message' => 'Not enough quantity available.'], 400);
    }

    // Subtract borrowed quantity
    $item->quantity -= $request->quantity;
    $item->save();

    // Log the borrow transaction
    $borrow = BorrowTransaction::create([
        'item_id' => $item->id,
        'quantity' => $request->quantity,
        'borrowed_by' => $request->borrowed_by,
        'status' => 'borrowed',
    ]);

    return response()->json([
        'message' => 'Item borrowed successfully.',
        'remaining_quantity' => $item->quantity,
        'borrow' => $borrow,
    ], 200);
}
}

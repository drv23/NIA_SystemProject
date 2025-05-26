<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Item;
use App\Http\Requests\V1\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Resources\V1\ItemResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ItemCollection;
use App\Services\V1\ItemService;
use App\Services\V1\QrCodeService;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new ItemCollection(item::all());
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

        if ($image) {
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}

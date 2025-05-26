<?php 

namespace App\Services\V1;

use App\Models\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ItemService
{
    public function handleImageUpload(Item $item, UploadedFile $image): void
    {
        if ($image) {
            $path = $image->store('item_images', 'public');
            $item->update(['image_path' => $path]);
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id', 'qr_code_data', 'image_path', 'is_active', 'version'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

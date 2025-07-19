<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'quantity',
        'borrowed_by',
        'status',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

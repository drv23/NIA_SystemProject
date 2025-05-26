<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Item extends Model
{
    /** @use HasFactory<\Database\Factories\ItemFactory> */
    use HasFactory;

    protected $fillable = [
        'uuid', 'unit', 'description', 'pac', 'unit_value', 'date_acquired', 'po_number', 'category_id', 'location_id'
        , 'condition_id', 'condition_number_id', 'user_id', 'image_path'
    ];

     protected static function booted()
    {
        static::creating(function ($item) {
            $item->uuid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function qrCode()
    {
        return $this->hasOne(QRCode::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class, 'condition_id', 'id');
    }

    public function condition_number()
    {
        return $this->belongsTo(ConditionNumber::class, 'condition_number_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}

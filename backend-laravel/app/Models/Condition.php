<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    /** @use HasFactory<\Database\Factories\ConditionFactory> */
    use HasFactory;
    
    protected $fillable = ['condition'];

    public function item()
    {
        return $this->hasMany(Item::class);
    }
}

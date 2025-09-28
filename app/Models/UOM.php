<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UOM extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
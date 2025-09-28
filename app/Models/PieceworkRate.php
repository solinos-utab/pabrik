<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PieceworkRate extends Model
{
    protected $fillable = [
        'product_name',
        'base_rate',
        'quality_bonus',
        'speed_bonus',
    ];

    public function entries()
    {
        return $this->hasMany(PieceworkEntry::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'uom_id',
        'minimum_stock',
    ];

    protected $casts = [
        'minimum_stock' => 'decimal:2',
    ];

    public function uom()
    {
        return $this->belongsTo(UOM::class);
    }

    public function stocks()
    {
        return $this->hasMany(InventoryStock::class);
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
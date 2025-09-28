<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location',
    ];

    public function stocks()
    {
        return $this->hasMany(InventoryStock::class);
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function transfersTo()
    {
        return $this->hasMany(InventoryTransaction::class, 'to_warehouse_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PieceworkEntry extends Model
{
    protected $fillable = [
        'user_id',
        'piecework_rate_id',
        'date',
        'quantity_good',
        'quantity_defect',
        'total_earnings',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'total_earnings' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rate()
    {
        return $this->belongsTo(PieceworkRate::class, 'piecework_rate_id');
    }
}
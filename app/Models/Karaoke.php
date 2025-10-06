<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Karaoke extends Model
{
    protected $fillable = [
        'place_id',
        'price_min',
        'price_max',
        'has_all_you_can_drink',
        'byo_allowed',
        'machine_types',
    ];

    protected $casts = [
        'price_min' => 'integer',
        'price_max' => 'integer',
        'has_all_you_can_drink' => 'boolean',
        'byo_allowed' => 'boolean',
    ];

    /**
     * 場所とのリレーション
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}

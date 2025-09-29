<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Izakaya extends Model
{
    protected $fillable = [
        'place_id',
        'price_min',
        'price_max',
        'has_all_you_can_drink',
        'byo_allowed',
        'alcohol_types',
    ];

    protected $casts = [
        'has_all_you_can_drink' => 'boolean',
        'byo_allowed' => 'boolean',
        'price_min' => 'integer',
        'price_max' => 'integer',
    ];

    /**
     * 場所とのリレーション
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * お酒の種類を配列で取得
     */
    public function getAlcoholTypesArrayAttribute(): array
    {
        if (!$this->alcohol_types) {
            return [];
        }

        return array_map('trim', explode(',', $this->alcohol_types));
    }

    /**
     * お酒の種類を配列で設定
     */
    public function setAlcoholTypesArrayAttribute(array $types): void
    {
        $this->alcohol_types = implode(',', $types);
    }
}

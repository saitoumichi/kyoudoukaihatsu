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
        'has_all_you_can_drink' => 'boolean',
        'byo_allowed' => 'boolean',
        'machine_types' => 'array',
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
     * 機種の選択肢
     */
    public static function getMachineTypeOptions(): array
    {
        return [
            'DAM' => 'DAM',
            'JOYSOUND' => 'JOYSOUND',
        ];
    }

    /**
     * 機種の表示用文字列
     */
    public function getMachineTypesStringAttribute(): string
    {
        if (!$this->machine_types) {
            return '';
        }

        $options = self::getMachineTypeOptions();
        $selected = array_intersect_key($options, array_flip($this->machine_types));
        return implode(', ', $selected);
    }
}

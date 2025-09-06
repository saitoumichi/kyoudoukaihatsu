<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Drive extends Model
{
    protected $fillable = [
        'place_id',
        'category_id',
    ];

    /**
     * 場所とのリレーション
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * カテゴリとのリレーション
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(DriveCategory::class, 'category_id');
    }
}

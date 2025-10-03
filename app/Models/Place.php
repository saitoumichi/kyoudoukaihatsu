<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Place extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'kana',
        'tel',
        'address',
        'description',
        'url',
        'score',
        'tags',
        'rating_avg',
        'rating_count',
        'recommend_score',
        'reason',
        'campus_time_min',
        'is_active',
        'type',
    ];

    protected $casts = [
        'rating_avg' => 'decimal:2',
        'is_active' => 'boolean',
        'score' => 'integer',
        'rating_count' => 'integer',
        'recommend_score' => 'integer',
        'campus_time_min' => 'integer',
    ];

    /**
     * ユーザーとのリレーション
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ドライブスポットとのリレーション
     */
    public function drive(): HasOne
    {
        return $this->hasOne(Drive::class);
    }

    /**
     * カラオケとのリレーション
     */
    public function karaoke(): HasOne
    {
        return $this->hasOne(Karaoke::class);
    }

    /**
     * 居酒屋とのリレーション
     */
    public function izakaya(): HasOne
    {
        return $this->hasOne(Izakaya::class);
    }

    /**
     * 画像とのリレーション
     */
    public function images(): HasMany
    {
        return $this->hasMany(PlaceImage::class)->orderBy('sort_order');
    }

    /**
     * スコープ: アクティブな場所のみ
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * スコープ: タイプ別
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * スコープ: おすすめ順
     */
    public function scopeRecommended($query)
    {
        return $query->orderBy('recommend_score', 'desc')
                    ->orderBy('rating_avg', 'desc')
                    ->orderBy('score', 'desc');
    }

    /**
     * スコープ: 大学からの時間順
     */
    public function scopeByCampusTime($query)
    {
        return $query->whereNotNull('campus_time_min')
                    ->orderBy('campus_time_min', 'asc');
    }
}

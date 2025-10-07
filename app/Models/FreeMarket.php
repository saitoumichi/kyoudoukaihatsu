<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FreeMarket extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'condition',
        'category',
        'image_url',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * ユーザーとのリレーション
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * メッセージとのリレーション
     */
    public function messages(): HasMany
    {
        return $this->hasMany(FreeMarketMessage::class, 'free_market_id');
    }

    /**
     * スコープ: アクティブな商品のみ
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * スコープ: カテゴリ別
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * スコープ: カテゴリ一覧取得
     */
    public function scopeCategories($query)
    {
        return $query->select('category')
            ->distinct()
            ->whereNotNull('category')
            ->orderBy('category');
    }
}

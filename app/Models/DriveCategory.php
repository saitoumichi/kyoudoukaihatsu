<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DriveCategory extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'sort',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort' => 'integer',
    ];

    /**
     * ドライブスポットとのリレーション
     */
    public function drives(): HasMany
    {
        return $this->hasMany(Drive::class, 'category_id');
    }

    /**
     * スコープ: アクティブなカテゴリのみ
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * スコープ: ソート順
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort')->orderBy('name');
    }
}

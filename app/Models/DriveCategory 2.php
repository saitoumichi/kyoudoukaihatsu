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
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'sort' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * ドライブスポットとのリレーション
     */
    public function drives(): HasMany
    {
        return $this->hasMany(Drive::class, 'category_id');
    }
}

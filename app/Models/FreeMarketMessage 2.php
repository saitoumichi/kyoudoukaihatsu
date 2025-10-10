<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreeMarketMessage extends Model
{
    protected $fillable = [
        'free_market_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * 送信者とのリレーション
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * 受信者とのリレーション
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * フリマ商品とのリレーション
     */
    public function freeMarket(): BelongsTo
    {
        return $this->belongsTo(FreeMarket::class, 'free_market_id');
    }
}

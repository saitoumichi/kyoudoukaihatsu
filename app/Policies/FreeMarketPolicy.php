<?php

namespace App\Policies;

use App\Models\FreeMarket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FreeMarketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // 認証済みユーザーは一覧を見ることができる
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FreeMarket $freeMarket): bool
    {
        return true; // 認証済みユーザーは詳細を見ることができる
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // 認証済みユーザーは作成できる
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FreeMarket $freeMarket): bool
    {
        return $user->id === $freeMarket->user_id; // 所有者のみ更新可能
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FreeMarket $freeMarket): bool
    {
        return $user->id === $freeMarket->user_id; // 所有者のみ削除可能
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FreeMarket $freeMarket): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FreeMarket $freeMarket): bool
    {
        return false;
    }
}

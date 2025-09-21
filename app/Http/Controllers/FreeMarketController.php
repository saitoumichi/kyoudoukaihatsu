<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FreeMarketController extends Controller
{
    /**
     * フリマ一覧表示
     */
    public function index(): View
    {
        return view('freemarket.index');
    }

    /**
     * 買取一覧表示
     */
    public function buy(): View
    {
        return view('freemarket.buy');
    }

    /**
     * 買取詳細表示
     */
    public function show(string $id): View
    {
        return view('freemarket.show', compact('id'));
    }

    /**
     * DM一覧表示
     */
    public function dm(): View
    {
        return view('freemarket.dm');
    }

    /**
     * 出品フォーム表示
     */
    public function create(): View
    {
        return view('freemarket.create');
    }

    /**
     * 出品処理
     */
    public function store(Request $request): RedirectResponse
    {
        // TODO: 出品処理の実装
        return redirect()->route('freemarket.my')
                        ->with('success', '出品しました。');
    }

    /**
     * 自分の出品物一覧
     */
    public function my(): View
    {
        return view('freemarket.my');
    }

    /**
     * 自分の出品物詳細
     */
    public function myShow(string $id): View
    {
        return view('freemarket.my-show', compact('id'));
    }

    /**
     * 自分の出品物編集フォーム
     */
    public function edit(string $id): View
    {
        return view('freemarket.edit', compact('id'));
    }

    /**
     * 自分の出品物更新
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        // TODO: 更新処理の実装
        return redirect()->route('freemarket.my.show', $id)
                        ->with('success', '更新しました。');
    }

    /**
     * 自分の出品物削除
     */
    public function destroy(string $id): RedirectResponse
    {
        // TODO: 削除処理の実装
        return redirect()->route('freemarket.my')
                        ->with('success', '削除しました。');
    }
}

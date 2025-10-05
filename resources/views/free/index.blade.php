<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('フリマ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 検索・フィルタフォーム -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('freemarket.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- 検索 -->
                            <div>
                                <x-input-label for="search" :value="__('検索')" />
                                <x-text-input id="search" class="block mt-1 w-full" type="text" name="search"
                                    :value="request('search')" placeholder="商品名、説明で検索" />
                            </div>

                            <!-- カテゴリ選択 -->
                            <div>
                                <x-input-label for="category" :value="__('カテゴリ')" />
                                <select id="category" name="category" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">すべて</option>
                                    <option value="electronics" {{ request('category') === 'electronics' ? 'selected' : '' }}>家電・デジタル</option>
                                    <option value="fashion" {{ request('category') === 'fashion' ? 'selected' : '' }}>ファッション</option>
                                    <option value="books" {{ request('category') === 'books' ? 'selected' : '' }}>本・雑誌</option>
                                    <option value="sports" {{ request('category') === 'sports' ? 'selected' : '' }}>スポーツ・アウトドア</option>
                                    <option value="hobby" {{ request('category') === 'hobby' ? 'selected' : '' }}>ホビー・グッズ</option>
                                    <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>その他</option>
                                </select>
                            </div>

                            <!-- 価格範囲 -->
                            <div>
                                <x-input-label for="price_min" :value="__('最低価格')" />
                                <x-text-input id="price_min" class="block mt-1 w-full" type="number" name="price_min"
                                    :value="request('price_min')" placeholder="0" />
                            </div>

                            <!-- ソート -->
                            <div>
                                <x-input-label for="sort" :value="__('並び順')" />
                                <select id="sort" name="sort" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>新着順</option>
                                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>価格の安い順</option>
                                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>価格の高い順</option>
                                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>名前順</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <x-secondary-button type="button" onclick="this.form.reset()">
                                {{ __('リセット') }}
                            </x-secondary-button>
                            <x-primary-button type="submit">
                                {{ __('検索') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- アクションボタン -->
            @auth
                <div class="mb-6">
                    <a href="{{ route('freemarket.create') }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('商品を出品する') }}
                    </a>
                </div>
            @endauth

            <!-- 商品一覧 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($items as $item)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- 商品画像 -->
                    @if($item->image)
                        <img src="{{ $item->image }}" alt="{{ $item->name }}"
                             class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">画像なし</span>
                        </div>
                    @endif

                    <div class="p-6">
                        <!-- カテゴリバッジ -->
                        <div class="mb-2">
                            <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                {{ ucfirst($item->category) }}
                            </span>
                        </div>

                        <!-- 商品名 -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('freemarket.show', $item->id) }}" class="hover:text-blue-600">
                                {{ $item->name }}
                            </a>
                        </h3>

                        <!-- 価格 -->
                        <div class="text-2xl font-bold text-green-600 mb-2">
                            ¥{{ number_format($item->price) }}
                        </div>

                        <!-- 説明 -->
                        @if($item->description)
                            <p class="text-gray-600 text-sm mb-2 line-clamp-2">
                                {{ Str::limit($item->description, 100) }}
                            </p>
                        @endif

                        <!-- 出品者情報 -->
                        <div class="text-sm text-gray-500 mb-2">
                            出品者: {{ $item->user->name ?? '不明' }}
                        </div>

                        <!-- 出品日 -->
                        <div class="text-sm text-gray-500 mb-4">
                            {{ $item->created_at->format('Y/m/d') }}
                        </div>

                        <!-- アクション -->
                        <div class="flex justify-between items-center">
                            <a href="{{ route('freemarket.show', $item->id) }}"
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                詳細を見る
                            </a>

                            @auth
                                @if($item->user_id === auth()->id())
                                    <div class="space-x-2">
                                        <a href="{{ route('freemarket.my.edit', $item->id) }}"
                                           class="text-green-600 hover:text-green-800 text-sm">
                                            編集
                                        </a>
                                        <form method="POST" action="{{ route('freemarket.my.destroy', $item->id) }}"
                                              class="inline" onsubmit="return confirm('削除しますか？')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                削除
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <a href="{{ route('freemarket.dm', $item->id) }}"
                                       class="bg-blue-500 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded">
                                        DMする
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">出品されている商品がありません。</p>
                        @auth
                            <a href="{{ route('freemarket.create') }}" class="text-blue-600 hover:text-blue-800 mt-4 inline-block">
                                最初の商品を出品する
                            </a>
                        @endauth
                    </div>
                @endforelse
            </div>

            <!-- ページネーション -->
            <div class="mt-6">
                {{-- ページネーション（実装時は有効化） --}}
                {{-- {{ $items->links() }} --}}
            </div>
        </div>
    </div>
</x-app-layout>

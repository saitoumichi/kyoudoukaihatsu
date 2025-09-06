<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('場所一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 検索・フィルタフォーム -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="GET" action="{{ route('places.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- 検索 -->
                            <div>
                                <x-input-label for="search" :value="__('検索')" />
                                <x-text-input id="search" class="block mt-1 w-full" type="text" name="search"
                                    :value="request('search')" placeholder="場所名、説明、タグで検索" />
                            </div>

                            <!-- タイプ選択 -->
                            <div>
                                <x-input-label for="type" :value="__('タイプ')" />
                                <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">すべて</option>
                                    <option value="drive" {{ request('type') === 'drive' ? 'selected' : '' }}>ドライブ</option>
                                    <option value="karaoke" {{ request('type') === 'karaoke' ? 'selected' : '' }}>カラオケ</option>
                                    <option value="izakaya" {{ request('type') === 'izakaya' ? 'selected' : '' }}>居酒屋</option>
                                </select>
                            </div>

                            <!-- カテゴリ選択（ドライブのみ） -->
                            <div>
                                <x-input-label for="category_id" :value="__('カテゴリ')" />
                                <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">すべて</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- ソート -->
                            <div>
                                <x-input-label for="sort" :value="__('並び順')" />
                                <select id="sort" name="sort" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="recommended" {{ request('sort') === 'recommended' ? 'selected' : '' }}>おすすめ順</option>
                                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>名前順</option>
                                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>評価順</option>
                                    <option value="campus_time" {{ request('sort') === 'campus_time' ? 'selected' : '' }}>大学からの時間順</option>
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
                    <a href="{{ route('places.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('新しい場所を追加') }}
                    </a>
                </div>
            @endauth

            <!-- 場所一覧 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($places as $place)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <!-- 画像 -->
                        @if($place->images->count() > 0)
                            <img src="{{ $place->images->first()->path }}" alt="{{ $place->name }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">画像なし</span>
                            </div>
                        @endif

                        <div class="p-6">
                            <!-- タイプバッジ -->
                            <div class="mb-2">
                                @switch($place->type)
                                    @case('drive')
                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                            ドライブ
                                        </span>
                                        @if($place->drive && $place->drive->category)
                                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full ml-1">
                                                {{ $place->drive->category->name }}
                                            </span>
                                        @endif
                                        @break
                                    @case('karaoke')
                                        <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">
                                            カラオケ
                                        </span>
                                        @break
                                    @case('izakaya')
                                        <span class="inline-block bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">
                                            居酒屋
                                        </span>
                                        @break
                                @endswitch
                            </div>

                            <!-- 場所名 -->
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                <a href="{{ route('places.show', $place) }}" class="hover:text-blue-600">
                                    {{ $place->name }}
                                </a>
                            </h3>

                            <!-- 評価 -->
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($place->rating_avg))
                                            ★
                                        @elseif($i - 0.5 <= $place->rating_avg)
                                            ☆
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm text-gray-600">
                                    {{ number_format($place->rating_avg, 1) }} ({{ $place->rating_count }}件)
                                </span>
                            </div>

                            <!-- 説明 -->
                            @if($place->description)
                                <p class="text-gray-600 text-sm mb-2 line-clamp-2">
                                    {{ Str::limit($place->description, 100) }}
                                </p>
                            @endif

                            <!-- タグ -->
                            @if($place->tags)
                                <div class="mb-2">
                                    @foreach(explode(',', $place->tags) as $tag)
                                        <span class="inline-block bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded mr-1 mb-1">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- アクション -->
                            <div class="flex justify-between items-center">
                                <a href="{{ route('places.show', $place) }}"
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    詳細を見る
                                </a>

                                @auth
                                    <div class="space-x-2">
                                        <a href="{{ route('places.edit', $place) }}"
                                           class="text-green-600 hover:text-green-800 text-sm">
                                            編集
                                        </a>
                                        <form method="POST" action="{{ route('places.destroy', $place) }}"
                                              class="inline" onsubmit="return confirm('削除しますか？')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                削除
                                            </button>
                                        </form>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">場所が見つかりませんでした。</p>
                        @auth
                            <a href="{{ route('places.create') }}" class="text-blue-600 hover:text-blue-800 mt-4 inline-block">
                                最初の場所を追加する
                            </a>
                        @endauth
                    </div>
                @endforelse
            </div>

            <!-- ページネーション -->
            <div class="mt-6">
                {{ $places->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

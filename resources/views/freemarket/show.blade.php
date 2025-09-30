<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('商品詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- 商品情報 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- 商品画像 -->
                        <div>
                            @if($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-96 object-cover rounded-lg">
                            @else
                                <div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
                                    <span class="text-gray-500 text-lg">画像なし</span>
                                </div>
                            @endif
                        </div>

                        <!-- 商品詳細 -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $item->name }}</h1>

                            <div class="text-4xl font-bold text-green-600 mb-4">
                                ¥{{ number_format($item->price) }}
                            </div>

                            <div class="mb-4">
                                <span class="inline-block bg-yellow-100 text-yellow-800 text-sm px-3 py-1 rounded-full">
                                    {{ ucfirst($item->category) }}
                                </span>
                            </div>

                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">商品説明</h3>
                                <p class="text-gray-700">{{ $item->description }}</p>
                            </div>

                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">出品者情報</h3>
                                <p class="text-gray-700">出品者: {{ $item->user->name }}</p>
                                <p class="text-gray-500 text-sm">出品日: {{ $item->created_at->format('Y年m月d日') }}</p>
                            </div>

                            <!-- アクションボタン -->
                            <div class="space-y-4">
                                @auth
                                    @if($item->user_id === auth()->id())
                                        <div class="flex space-x-4">
                                            <a href="{{ route('freemarket.my.edit', $item->id) }}">
                                                編集する
                                            </a>
                                            <form method="POST" action="{{ route('freemarket.my.destroy', $item->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit">削除する</button>
                                            </form>
                                        </div>
                                    @else
                                        <a href="{{ route('freemarket.dm', $item) }}"
                                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded text-lg">
                                            詳細
                                        </a>
                                    @endif
                                @else
                                    <p class="text-gray-500">DM機能を使用するにはログインが必要です。</p>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

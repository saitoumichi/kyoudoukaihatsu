<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $place->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- タイプバッジ -->
                    <div class="mb-4">
                        @switch($place->type)
                            @case('drive')
                                <span class="inline-block bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">
                                    ドライブ
                                </span>
                                @if($place->drive && $place->drive->category)
                                    <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full ml-2">
                                        {{ $place->drive->category->name }}
                                    </span>
                                @endif
                                @break
                            @case('karaoke')
                                <span class="inline-block bg-purple-100 text-purple-800 text-sm px-3 py-1 rounded-full">
                                    カラオケ
                                </span>
                                @break
                            @case('izakaya')
                                <span class="inline-block bg-orange-100 text-orange-800 text-sm px-3 py-1 rounded-full">
                                    居酒屋
                                </span>
                                @break
                        @endswitch
                    </div>

                    <!-- 画像ギャラリー -->
                    @if($place->images->count() > 0)
                        <div class="mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($place->images as $image)
                                    <img src="{{ $image->path }}" alt="{{ $place->name }}"
                                         class="w-full h-48 object-cover rounded-lg">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 基本情報 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">基本情報</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">名前</dt>
                                    <dd class="text-sm text-gray-900">{{ $place->name }}</dd>
                                </div>
                                @if($place->kana)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">読み方</dt>
                                        <dd class="text-sm text-gray-900">{{ $place->kana }}</dd>
                                    </div>
                                @endif
                                @if($place->tel)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">電話番号</dt>
                                        <dd class="text-sm text-gray-900">{{ $place->tel }}</dd>
                                    </div>
                                @endif
                                @if($place->address)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">住所</dt>
                                        <dd class="text-sm text-gray-900">{{ $place->address }}</dd>
                                    </div>
                                @endif
                                @if($place->url)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">URL</dt>
                                        <dd class="text-sm text-gray-900">
                                            <a href="{{ $place->url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                {{ $place->url }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">評価・情報</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">評価</dt>
                                    <dd class="flex items-center">
                                        <div class="flex text-yellow-400 mr-2">
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
                                        <span class="text-sm text-gray-900">
                                            {{ number_format($place->rating_avg, 1) }} ({{ $place->rating_count }}件)
                                        </span>
                                    </dd>
                                </div>
                                @if($place->campus_time_min)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">大学からの時間</dt>
                                        <dd class="text-sm text-gray-900">{{ $place->campus_time_min }}分</dd>
                                    </div>
                                @endif
                                @if($place->reason)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">おすすめ理由</dt>
                                        <dd class="text-sm text-gray-900">{{ $place->reason }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- 説明 -->
                    @if($place->description)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">説明</h3>
                            <p class="text-gray-700 whitespace-pre-line">{{ $place->description }}</p>
                        </div>
                    @endif

                    <!-- タグ -->
                    @if($place->tags)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">タグ</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $place->tags) as $tag)
                                    <span class="inline-block bg-gray-100 text-gray-600 text-sm px-3 py-1 rounded-full">
                                        {{ trim($tag) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- タイプ別詳細情報 -->
                    @if($place->type === 'karaoke' && $place->karaoke)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">カラオケ詳細</h3>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($place->karaoke->price_min || $place->karaoke->price_max)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">料金</dt>
                                        <dd class="text-sm text-gray-900">
                                            @if($place->karaoke->price_min && $place->karaoke->price_max)
                                                {{ number_format($place->karaoke->price_min) }}円〜{{ number_format($place->karaoke->price_max) }}円
                                            @elseif($place->karaoke->price_min)
                                                {{ number_format($place->karaoke->price_min) }}円〜
                                            @elseif($place->karaoke->price_max)
                                                〜{{ number_format($place->karaoke->price_max) }}円
                                            @endif
                                        </dd>
                                    </div>
                                @endif
                                @if($place->karaoke->machine_types)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">機種</dt>
                                        <dd class="text-sm text-gray-900">{{ implode(', ', $place->karaoke->machine_types) }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">飲み放題</dt>
                                    <dd class="text-sm text-gray-900">{{ $place->karaoke->has_all_you_can_drink ? 'あり' : 'なし' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">BYO</dt>
                                    <dd class="text-sm text-gray-900">{{ $place->karaoke->byo_allowed ? '可' : '不可' }}</dd>
                                </div>
                            </dl>
                        </div>
                    @elseif($place->type === 'izakaya' && $place->izakaya)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">居酒屋詳細</h3>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($place->izakaya->price_min || $place->izakaya->price_max)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">料金</dt>
                                        <dd class="text-sm text-gray-900">
                                            @if($place->izakaya->price_min && $place->izakaya->price_max)
                                                {{ number_format($place->izakaya->price_min) }}円〜{{ number_format($place->izakaya->price_max) }}円
                                            @elseif($place->izakaya->price_min)
                                                {{ number_format($place->izakaya->price_min) }}円〜
                                            @elseif($place->izakaya->price_max)
                                                〜{{ number_format($place->izakaya->price_max) }}円
                                            @endif
                                        </dd>
                                    </div>
                                @endif
                                @if($place->izakaya->alcohol_types)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">酒類</dt>
                                        <dd class="text-sm text-gray-900">{{ $place->izakaya->alcohol_types }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">飲み放題</dt>
                                    <dd class="text-sm text-gray-900">{{ $place->izakaya->has_all_you_can_drink ? 'あり' : 'なし' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">BYO</dt>
                                    <dd class="text-sm text-gray-900">{{ $place->izakaya->byo_allowed ? '可' : '不可' }}</dd>
                                </div>
                            </dl>
                        </div>
                    @endif

                    <!-- アクションボタン -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <a href="{{ route('places.index') }}"
                           class="text-gray-600 hover:text-gray-800 font-medium">
                            ← 一覧に戻る
                        </a>

                        @auth
                            <div class="space-x-4">
                                <a href="{{ route('places.edit', $place) }}"
                                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    編集
                                </a>
                                <form method="POST" action="{{ route('places.destroy', $place) }}"
                                      class="inline" onsubmit="return confirm('削除しますか？')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        削除
                                    </button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

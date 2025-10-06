<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('商品を出品する') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('freemarket.store') }}" class="space-y-6">
                        @csrf

                        <!-- 商品名 -->
                        <div>
                            <x-input-label for="name" :value="__('商品名')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- 説明 -->
                        <div>
                            <x-input-label for="description" :value="__('商品説明')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- 価格 -->
                        <div>
                            <x-input-label for="price" :value="__('価格')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price')" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <!-- カテゴリ -->
                        <div>
                            <x-input-label for="category" :value="__('カテゴリ')" />
                            <select id="category" name="category" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">カテゴリを選択してください</option>
                                <option value="electronics" {{ old('category') === 'electronics' ? 'selected' : '' }}>家電・デジタル</option>
                                <option value="fashion" {{ old('category') === 'fashion' ? 'selected' : '' }}>ファッション</option>
                                <option value="books" {{ old('category') === 'books' ? 'selected' : '' }}>本・雑誌</option>
                                <option value="sports" {{ old('category') === 'sports' ? 'selected' : '' }}>スポーツ・アウトドア</option>
                                <option value="hobby" {{ old('category') === 'hobby' ? 'selected' : '' }}>ホビー・グッズ</option>
                                <option value="other" {{ old('category') === 'other' ? 'selected' : '' }}>その他</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <!-- 画像 -->
                        <div>
                            <x-input-label for="image" :value="__('商品画像')" />
                            <input id="image" class="block mt-1 w-full" type="file" name="image" accept="image/*" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('free.index') }}" class="text-gray-600 hover:text-gray-800">
                                {{ __('キャンセル') }}
                            </a>
                            <x-primary-button>
                                {{ __('出品する') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

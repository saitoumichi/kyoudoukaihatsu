<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('新しい場所を追加') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('places.store') }}" class="space-y-6">
                        @csrf

                        <!-- 基本情報 -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">基本情報</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="name" :value="__('場所名')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                                  :value="old('name')" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="kana" :value="__('読み方（任意）')" />
                                    <x-text-input id="kana" class="block mt-1 w-full" type="text" name="kana"
                                                  :value="old('kana')" />
                                    <x-input-error :messages="$errors->get('kana')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="tel" :value="__('電話番号（任意）')" />
                                    <x-text-input id="tel" class="block mt-1 w-full" type="tel" name="tel"
                                                  :value="old('tel')" />
                                    <x-input-error :messages="$errors->get('tel')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="address" :value="__('住所（任意）')" />
                                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address"
                                                  :value="old('address')" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="url" :value="__('URL（任意）')" />
                                    <x-text-input id="url" class="block mt-1 w-full" type="url" name="url"
                                                  :value="old('url')" />
                                    <x-input-error :messages="$errors->get('url')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="campus_time_min" :value="__('大学からの時間（分）')" />
                                    <x-text-input id="campus_time_min" class="block mt-1 w-full" type="number"
                                                  name="campus_time_min" :value="old('campus_time_min')" min="0" />
                                    <x-input-error :messages="$errors->get('campus_time_min')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- タイプ選択 -->
                        <div>
                            <x-input-label for="type" :value="__('タイプ')" />
                            <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    onchange="toggleTypeFields()" required>
                                <option value="">選択してください</option>
                                <option value="drive" {{ old('type') === 'drive' ? 'selected' : '' }}>ドライブ</option>
                                <option value="karaoke" {{ old('type') === 'karaoke' ? 'selected' : '' }}>カラオケ</option>
                                <option value="izakaya" {{ old('type') === 'izakaya' ? 'selected' : '' }}>居酒屋</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- ドライブ固有フィールド -->
                        <div id="drive-fields" class="hidden">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">ドライブ詳細</h3>
                            <div>
                                <x-input-label for="category_id" :value="__('カテゴリ')" />
                                <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">選択してください</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>
                        </div>

                        <!-- カラオケ・居酒屋共通フィールド -->
                        <div id="common-fields" class="hidden">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">料金・詳細情報</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="price_min" :value="__('最低料金（円）')" />
                                    <x-text-input id="price_min" class="block mt-1 w-full" type="number"
                                                  name="price_min" :value="old('price_min')" min="0" />
                                    <x-input-error :messages="$errors->get('price_min')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="price_max" :value="__('最高料金（円）')" />
                                    <x-text-input id="price_max" class="block mt-1 w-full" type="number"
                                                  name="price_max" :value="old('price_max')" min="0" />
                                    <x-input-error :messages="$errors->get('price_max')" class="mt-2" />
                                </div>

                                <div class="flex items-center">
                                    <input id="has_all_you_can_drink" type="checkbox" name="has_all_you_can_drink" value="1"
                                           {{ old('has_all_you_can_drink') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="has_all_you_can_drink" class="ml-2 text-sm text-gray-600">
                                        飲み放題あり
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input id="byo_allowed" type="checkbox" name="byo_allowed" value="1"
                                           {{ old('byo_allowed') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label for="byo_allowed" class="ml-2 text-sm text-gray-600">
                                        BYO可
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- カラオケ固有フィールド -->
                        <div id="karaoke-fields" class="hidden">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">カラオケ詳細</h3>
                            <div>
                                <x-input-label for="machine_types" :value="__('機種（複数選択可）')" />
                                <div class="mt-2 space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="machine_types[]" value="JOYSOUND"
                                               {{ in_array('JOYSOUND', old('machine_types', [])) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">JOYSOUND</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="machine_types[]" value="DAM"
                                               {{ in_array('DAM', old('machine_types', [])) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">DAM</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="machine_types[]" value="UGA"
                                               {{ in_array('UGA', old('machine_types', [])) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">UGA</span>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('machine_types')" class="mt-2" />
                            </div>
                        </div>

                        <!-- 居酒屋固有フィールド -->
                        <div id="izakaya-fields" class="hidden">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">居酒屋詳細</h3>
                            <div>
                                <x-input-label for="alcohol_types" :value="__('酒類（任意）')" />
                                <x-text-input id="alcohol_types" class="block mt-1 w-full" type="text"
                                              name="alcohol_types" :value="old('alcohol_types')"
                                              placeholder="例：ビール、日本酒、ワイン" />
                                <x-input-error :messages="$errors->get('alcohol_types')" class="mt-2" />
                            </div>
                        </div>

                        <!-- 説明・タグ -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">その他情報</h3>
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="description" :value="__('説明（任意）')" />
                                    <textarea id="description" name="description" rows="4"
                                              class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="tags" :value="__('タグ（カンマ区切り、任意）')" />
                                    <x-text-input id="tags" class="block mt-1 w-full" type="text"
                                                  name="tags" :value="old('tags')"
                                                  placeholder="例：安い,学生向け,個室あり" />
                                    <x-input-error :messages="$errors->get('tags')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="reason" :value="__('おすすめ理由（任意）')" />
                                    <x-text-input id="reason" class="block mt-1 w-full" type="text"
                                                  name="reason" :value="old('reason')" />
                                    <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- アクティブ状態 -->
                        <div class="flex items-center">
                            <input id="is_active" type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <label for="is_active" class="ml-2 text-sm text-gray-600">
                                公開する
                            </label>
                        </div>

                        <!-- 送信ボタン -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('places.index') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                キャンセル
                            </a>
                            <x-primary-button>
                                {{ __('作成') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTypeFields() {
            const type = document.getElementById('type').value;
            const driveFields = document.getElementById('drive-fields');
            const commonFields = document.getElementById('common-fields');
            const karaokeFields = document.getElementById('karaoke-fields');
            const izakayaFields = document.getElementById('izakaya-fields');

            // 全てのフィールドを非表示
            driveFields.classList.add('hidden');
            commonFields.classList.add('hidden');
            karaokeFields.classList.add('hidden');
            izakayaFields.classList.add('hidden');

            // 選択されたタイプに応じてフィールドを表示
            if (type === 'drive') {
                driveFields.classList.remove('hidden');
            } else if (type === 'karaoke') {
                commonFields.classList.remove('hidden');
                karaokeFields.classList.remove('hidden');
            } else if (type === 'izakaya') {
                commonFields.classList.remove('hidden');
                izakayaFields.classList.remove('hidden');
            }
        }

        // ページ読み込み時に初期化
        document.addEventListener('DOMContentLoaded', function() {
            toggleTypeFields();
        });
    </script>
</x-app-layout>

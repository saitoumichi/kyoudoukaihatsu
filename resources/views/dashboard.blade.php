<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ダッシュボード') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 統計カード -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">登録場所数</p>
                                <p class="text-2xl font-semibold text-gray-900">0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">出品数</p>
                                <p class="text-2xl font-semibold text-gray-900">0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">未読DM</p>
                                <p class="text-2xl font-semibold text-gray-900">0</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- クイックアクション -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- 場所管理 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">場所管理</h3>
                        <div class="space-y-3">
                            <a href="{{ route('places.create') }}" class="block w-full bg-blue-500 text-white text-center py-2 px-4 rounded-md hover:bg-blue-600 transition duration-150">
                                新しい場所を追加
                            </a>
                            <a href="{{ route('places.index') }}" class="block w-full bg-gray-500 text-white text-center py-2 px-4 rounded-md hover:bg-gray-600 transition duration-150">
                                場所一覧を見る
                            </a>
                        </div>
                    </div>
                </div>

                <!-- フリマ -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">フリマ</h3>
                        <div class="space-y-3">
                            <a href="{{ route('freemarket.create') }}" class="block w-full bg-green-500 text-white text-center py-2 px-4 rounded-md hover:bg-green-600 transition duration-150">
                                新しく出品する
                            </a>
                            <a href="{{ route('freemarket.my') }}" class="block w-full bg-gray-500 text-white text-center py-2 px-4 rounded-md hover:bg-gray-600 transition duration-150">
                                自分の出品を見る
                            </a>
                        </div>
                    </div>
                </div>

                <!-- アカウント管理 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">アカウント管理</h3>
                        <div class="space-y-3">
                            <a href="/profile" class="block w-full bg-red-500 text-white text-center py-2 px-4 rounded-md hover:bg-red-600 transition duration-150">
                                パスワード変更
                            </a>
                            <a href="http://localhost:8000/places/create" class="block w-full bg-purple-500 text-white text-center py-2 px-4 rounded-md hover:bg-purple-600 transition duration-150">
                                新しく掲載を作成
                            </a>
                            <a href="/places/edit" class="block w-full bg-orange-500 text-white text-center py-2 px-4 rounded-md hover:bg-orange-600 transition duration-150">
                                あなたの掲載一覧
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 最近の活動 -->
            <div class="mt-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">最近の活動</h3>
                        <div class="text-gray-500">
                            <p>まだ活動がありません。</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

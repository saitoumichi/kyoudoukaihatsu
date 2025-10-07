<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('手続き状況 - ') }}{{ $free->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- 商品情報 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $free->title }}</h3>
                            <p class="text-gray-600">出品者: {{ $free->user->login_id ?? '不明' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-green-600">¥{{ number_format($free->price ?? 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 手続き状況表示 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">現在の手続き状況</h3>

                    <!-- ステータス表示 -->
                    <div class="mb-6">
                        @php
                            $statusConfig = [
                                'negotiating' => ['label' => '交渉中', 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '💬'],
                                'agreed' => ['label' => '合意済み', 'color' => 'bg-blue-100 text-blue-800', 'icon' => '🤝'],
                                'shipped' => ['label' => '発送済み', 'color' => 'bg-purple-100 text-purple-800', 'icon' => '📦'],
                                'completed' => ['label' => '完了', 'color' => 'bg-green-100 text-green-800', 'icon' => '✅'],
                                'cancelled' => ['label' => 'キャンセル', 'color' => 'bg-red-100 text-red-800', 'icon' => '❌']
                            ];
                            $currentStatus = $statusConfig[$status] ?? $statusConfig['negotiating'];
                        @endphp

                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">{{ $currentStatus['icon'] }}</span>
                            <span class="inline-block px-4 py-2 rounded-full text-sm font-medium {{ $currentStatus['color'] }}">
                                {{ $currentStatus['label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- 進捗バー -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                            <span>交渉中</span>
                            <span>合意済み</span>
                            <span>発送済み</span>
                            <span>完了</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            @php
                                $progress = match($status) {
                                    'negotiating' => 25,
                                    'agreed' => 50,
                                    'shipped' => 75,
                                    'completed' => 100,
                                    default => 0
                                };
                            @endphp
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    <!-- 手続き詳細 -->
                    <div class="space-y-4">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="font-semibold text-gray-900">交渉開始</h4>
                            <p class="text-sm text-gray-600">買取リクエストが送信されました</p>
                            <p class="text-xs text-gray-500">{{ now()->format('Y年m月d日 H:i') }}</p>
                        </div>

                        @if(in_array($status, ['agreed', 'shipped', 'completed']))
                            <div class="border-l-4 border-green-500 pl-4">
                                <h4 class="font-semibold text-gray-900">合意完了</h4>
                                <p class="text-sm text-gray-600">取引条件が合意されました</p>
                                <p class="text-xs text-gray-500">{{ now()->subDays(1)->format('Y年m月d日 H:i') }}</p>
                            </div>
                        @endif

                        @if(in_array($status, ['shipped', 'completed']))
                            <div class="border-l-4 border-purple-500 pl-4">
                                <h4 class="font-semibold text-gray-900">発送完了</h4>
                                <p class="text-sm text-gray-600">商品が発送されました</p>
                                <p class="text-xs text-gray-500">{{ now()->subHours(6)->format('Y年m月d日 H:i') }}</p>
                            </div>
                        @endif

                        @if($status === 'completed')
                            <div class="border-l-4 border-green-500 pl-4">
                                <h4 class="font-semibold text-gray-900">取引完了</h4>
                                <p class="text-sm text-gray-600">取引が正常に完了しました</p>
                                <p class="text-xs text-gray-500">{{ now()->subMinutes(30)->format('Y年m月d日 H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 手続き状況更新フォーム -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">手続き状況を更新</h3>

                    <form method="POST" action="{{ route('free.status.update', $free->id) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                新しい状況
                            </label>
                            <select name="status" id="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="negotiating" {{ $status === 'negotiating' ? 'selected' : '' }}>交渉中</option>
                                <option value="agreed" {{ $status === 'agreed' ? 'selected' : '' }}>合意済み</option>
                                <option value="shipped" {{ $status === 'shipped' ? 'selected' : '' }}>発送済み</option>
                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>完了</option>
                            </select>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                備考（任意）
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="状況更新に関する備考があれば入力してください..."></textarea>
                        </div>

                        <div class="flex justify-between items-center">
                            <div class="flex space-x-4">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    状況を更新
                                </button>

                                <a href="{{ route('free.dm', $free->id) }}"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    DMに戻る
                                </a>
                            </div>

                            <a href="{{ route('free.show', $free->id) }}"
                                class="text-blue-600 hover:text-blue-800">
                                商品詳細に戻る
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- アクション履歴 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">アクション履歴</h3>

                    <div class="space-y-3">
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">買取リクエスト送信</p>
                                <p class="text-xs text-gray-500">{{ now()->format('Y年m月d日 H:i') }}</p>
                            </div>
                        </div>

                        @if($status !== 'negotiating')
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">状況更新: {{ $currentStatus['label'] }}</p>
                                    <p class="text-xs text-gray-500">{{ now()->subHours(2)->format('Y年m月d日 H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

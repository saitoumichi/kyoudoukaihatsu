<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Tab Navigation -->
            <div class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <nav class="flex space-x-8" aria-label="Tabs">
                        <a href="{{ route('places.index') }}"
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('places.index') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            ホーム
                        </a>
                        <a href="{{ route('places.by-type', 'drive') }}"
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('places.by-type') && request()->route('type') == 'drive' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            ドライブ
                        </a>
                        <a href="{{ route('places.by-type', 'karaoke') }}"
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('places.by-type') && request()->route('type') == 'karaoke' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            カラオケ
                        </a>
                        <a href="{{ route('places.by-type', 'izakaya') }}"
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('places.by-type') && request()->route('type') == 'izakaya' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            居酒屋
                        </a>
                        <a href="{{ route('freemarket.index') }}"
                           class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('freemarket.*') ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            フリマ
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>

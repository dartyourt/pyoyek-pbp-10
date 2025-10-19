<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'UMKM-Mini Commerce') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script>
            // On page load or when changing themes, best to add inline in `head` to avoid FOUC
            const savedTheme = localStorage.getItem('color-theme');
            
            if (savedTheme) {
                // Use the saved theme preference
                if (savedTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } else {
                // If no saved preference, use system preference and save it
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            }
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        <!-- in-page toast (listens for window 'toast' events). Supports optional undo -->
        <div x-data="{ show:false, message:'', type:'info', undoToken:null }" x-on:toast.window="message = $event.detail.message; type = $event.detail.type || 'info'; undoToken = $event.detail.undoToken || null; show = true; if ($event.detail.duration) { setTimeout(()=> show = false, $event.detail.duration) } else { setTimeout(()=> show = false, 3500) }" class="fixed bottom-6 right-6 z-50">
            <div x-show="show" x-transition class="max-w-xs w-80 px-4 py-3 rounded shadow-lg flex items-center justify-between space-x-4" :class="{ 'bg-green-600 text-white': type==='success', 'bg-red-600 text-white': type==='error', 'bg-gray-800 text-white': type==='info' }">
                <div class="flex-1">
                    <div x-text="message"></div>
                </div>
                <div class="flex items-center">
                    <button x-show="undoToken" x-on:click.prevent="$dispatch('undo', { token: undoToken }); show = false" class="ml-2 text-sm underline">Undo</button>
                    <button x-on:click.prevent="show = false" class="ml-2 text-sm">✕</button>
                </div>
            </div>
        </div>
    </body>
</html>

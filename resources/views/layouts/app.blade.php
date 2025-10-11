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
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
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
        
        <!-- in-page toast (listens for window 'toast' events) -->
        <div x-data="{ show:false, message:'', type:'info' }" x-on:toast.window="message = $event.detail.message; type = $event.detail.type || 'info'; show = true; setTimeout(()=> show = false, 3500)" class="fixed bottom-6 right-6 z-50">
            <div x-show="show" x-transition class="max-w-xs w-80 px-4 py-3 rounded shadow-lg" :class="{ 'bg-green-600 text-white': type==='success', 'bg-red-600 text-white': type==='error', 'bg-gray-800 text-white': type==='info' }">
                <div x-text="message"></div>
            </div>
        </div>
    </body>
</html>

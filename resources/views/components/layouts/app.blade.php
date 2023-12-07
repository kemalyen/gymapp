<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @filamentStyles
    @vite('resources/css/app.css')

</head>

<body class="h-full">


    <div class="min-h-full">
        @include('layouts.nav')
        @include('layouts.header')

        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @filamentScripts
    @vite('resources/js/app.js')
</body>

</html>

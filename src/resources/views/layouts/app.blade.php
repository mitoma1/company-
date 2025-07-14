<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'COACHTECH') }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ページ固有CSS -->
    @stack('styles')

    <!-- Alpine.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 text-gray-800">
    @include('layouts.header')

    <main class="py-6 container mx-auto">
        @yield('content')
    </main>
</body>

</html>
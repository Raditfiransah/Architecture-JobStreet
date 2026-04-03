<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Web Architect') }} - Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 -translate-x-full lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-200 ease-in-out">
            <x-sidebar :role="auth()->user()->role" />
        </div>

        <div class="flex-1 flex flex-col min-w-0">
            <x-topbar :user="auth()->user()" />

            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden" onclick="document.getElementById('sidebar').classList.add('-translate-x-full'); this.classList.add('hidden');"></div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const observer = new MutationObserver(() => {
            if (sidebar.classList.contains('-translate-x-full')) {
                overlay.classList.add('hidden');
            } else {
                overlay.classList.remove('hidden');
            }
        });
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
    </script>
</body>
</html>

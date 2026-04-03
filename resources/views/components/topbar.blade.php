@props(['user' => null])

<header class="bg-white border-b border-gray-100 h-16 px-6 flex items-center justify-between">
    <div class="flex items-center">
        <button type="button" class="lg:hidden mr-4 text-gray-500 hover:text-gray-700" onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <div class="flex items-center space-x-4">
        <div class="text-right">
            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
            <p class="text-xs text-gray-500">{{ ucfirst($user->role) }}</p>
        </div>

        @if ($user->avatar_url)
            <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-full">
        @else
            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center">
                <span class="text-white text-sm font-medium">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                Logout
            </button>
        </form>
    </div>
</header>

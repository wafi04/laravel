<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen flex">
        <div class="w-fit  dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-lg">
            <div class="h-16 flex items-center text-white font-semibold justify-center border-b dark:border-gray-700">

            Dashboard
        </div>

            <div class="p-4 border-b dark:border-gray-700">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800 dark:text-gray-200">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
          <nav class="mt-5 p-3 space-y-2">
    <x-sidebar-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        <span class="text-sm font-medium">Beranda</span>
    </x-sidebar-nav-link>

    <x-sidebar-nav-link :href="route('dashboard.gedung.index')"
:active="request()->routeIs('dashboard.gedung.*')">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
        </svg>
        <span class="text-sm font-medium">Daftar Gedung</span>
    </x-sidebar-nav-link>

    <x-sidebar-nav-link :href="route('dashboard-jadwal')" :active="request()->routeIs('dashboard-jadwal')">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <span class="text-sm font-medium">Jadwal Booking</span>
    </x-sidebar-nav-link>


    <div class="border-t border-gray-200 dark:border-gray-700 my-3"></div>
    
  

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
</nav>

            <!-- Logout -->
             <x-sidebar-nav-link href="#" onclick="document.getElementById('logout-form').submit(); return false;">
        <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
        </svg>
        <span class="text-sm font-medium text-red-500">Keluar</span>
    </x-sidebar-nav-link>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
           

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    
</body>
</html>
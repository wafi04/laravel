<!-- resources/views/components/navbar.blade.php -->
<nav class="shadow-md">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="/" class="text-2xl font-bold text-red-600">
            GedungKu
        </a>
        <div class="space-x-4 flex items-center gap-4">
            @auth   
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="bg-red-500    px-4 py-2 rounded-full hover:bg-red-600 transition mr-2">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('bookings.my-bookings') }}" class="text-gray-700 hover:text-red-600 transition mr-4">
                    My Bookings
                </a>

                @endif

                <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="text-gray-500 hover:text-red-600 transition ml-2">
        Logout
    </button>
</form>
            @else
             
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="bg-red-500  px-4 py-2 rounded-full hover:bg-red-600 transition">
                    Daftar
                </a>
            @endauth
        </div>
    </div>
</nav>
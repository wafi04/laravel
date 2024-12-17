<x-app-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                {{ __('Dashboard') }}
            </h1>
        </div>

        {{-- Statistik Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Gedung --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                            {{ __('Total Gedung') }}
                        </h3>
                       <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">
    {{ $stats['total_gedung'] ?? 0 }}
</p>

                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 p-3 rounded-full">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Jadwal Mendatang --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                            {{ __('Upcoming Jadwal') }}
                        </h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                           {{ $stats['pending_bookings'] ?? 0 }}
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 p-3 rounded-full">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-2">
                    <a href="{{ route('dashboard-jadwal') }}" class="text-sm text-green-600 dark:text-green-400 hover:underline">
                        {{ __('View Schedule') }}
                    </a>
                </div>
            </div>

            {{-- Total Booking --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                            {{ __('Total Booking') }}
                        </h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">
    {{ $stats['total_bookings'] ?? 0 }}
</p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-300 p-3 rounded-full">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Pendapatan Total --}}
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                            {{ __('Total Pendapatan') }}
                        </h3>
                       <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">
    Rp {{ number_format($stats['total_pendapatan'] ?? 0, 0, ',', '.') }}
</p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-300 p-3 rounded-full">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Booking Terbaru --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 shadow-md mt-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                {{ __('Booking Terbaru') }}
            </h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">{{ __('Gedung') }}</th>
                            <th scope="col" class="px-6 py-3">{{ __('Tanggal') }}</th>
                            <th scope="col" class="px-6 py-3">{{ __('Status') }}</th>
                            <th scope="col" class="px-6 py-3">{{ __('Total Harga') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                      @forelse($bookings as $booking)
    <tr class="bg-white border-b text-center dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
        <td class="px-6 py-4">{{ $booking->gedung->name }}</td>
        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</td>
        <td class="px-6 py-4">
            <span class="
                px-2 py-1 rounded-full text-xs font-medium
                {{ $booking->status === 'PENDING' ? 'bg-yellow-100 text-yellow-800' : 
                   ($booking->status === 'CONFIRMED' ? 'bg-green-100 text-green-800' : 
                   'bg-red-100 text-red-800') }}
            ">
                {{ $booking->status }}
            </span>
        </td>
        <td class="px-6 py-4">Rp {{ number_format($booking->total_harga ?? 0, 0, ',', '.') }}</td>
        
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
            {{ __('Tidak ada booking terbaru') }}
        </td>
    </tr>
@endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
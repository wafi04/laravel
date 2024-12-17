<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ __('Dashboard Jadwal') }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kelola semua jadwal booking gedung Anda
                    </p>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="mb-6">
                <form method="GET" action="{{ route('dashboard-jadwal') }}" class="flex items-center space-x-4">
                    <label class="text-gray-700 dark:text-gray-300">Filter Status:</label>
                    <select name="status" onchange="this.form.submit()" 
                        class="form-select block w-full sm:w-auto border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm">
                        <option value="all" {{ $selectedStatus == 'all' ? 'selected' : '' }}>Semua Status ({{ $stats['total'] }})</option>
                        <option value="PENDING" {{ $selectedStatus == 'PENDING' ? 'selected' : '' }}>Pending ({{ $stats['pending'] }})</option>
                        <option value="CONFIRMED" {{ $selectedStatus == 'CONFIRMED' ? 'selected' : '' }}>Confirmed ({{ $stats['confirmed'] }})</option>
                        <option value="COMPLETED" {{ $selectedStatus == 'COMPLETED' ? 'selected' : '' }}>Completed ({{ $stats['completed'] }})</option>
                        <option value="CANCELLED" {{ $selectedStatus == 'CANCELLED' ? 'selected' : '' }}>Cancelled ({{ $stats['cancelled'] }})</option>
                    </select>
                </form>
            </div>

            <!-- Booking Cards -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($bookings as $booking)
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold  text-white ">
                                    {{ $booking->gedung->name }}
                                </h3>
                                <span class="px-3 py-1 text-xs rounded-full 
                                    {{ $booking->status == 'PENDING' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($booking->status == 'CONFIRMED' ? 'bg-green-100 text-green-800' : 
                                       ($booking->status == 'CANCELLED' ? 'bg-red-100 text-red-800' : 
                                       'bg-gray-100 text-gray-800')) }}">
                                    {{ $booking->status }}
                                </span>
                            </div>
                            <div class="space-y-2 text-sm  dark:text-gray-400">
                                <p>
                                    <strong>Tanggal:</strong> 
                                    {{ $booking->start_date->format('d M Y') }} - 
                                    {{ $booking->end_date->format('d M Y') }}
                                </p>
                                <p>
                                    <strong>Pemesan:</strong> {{ $booking->user->name }}
                                </p>
                                @if($booking->payments->isNotEmpty())
                                    <p>
                                        <strong>Pembayaran:</strong> 
                                        Rp {{ number_format($booking->payments->first()->harga, 0, ',', '.') }} 
                                        ({{ $booking->payments->first()->metode }})
                                    </p>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-4 flex space-x-2">
                              @if(in_array($booking->status, ['CONFIRMED']) && $booking->payments->isNotEmpty())
    <form 
        action="{{ route('bookings.update-status', $booking->id) }}" 
        method="POST" 
        class="w-full">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="COMPLETED">
        
        <button 
            type="submit"
            class="w-full text-green-600 hover:text-green-900 dark:hover:text-green-400 flex items-center justify-center border border-green-600 rounded-md py-2 px-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Selesaikan
        </button>
    </form>
@endif

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-4 text-lg font-medium">
                            Tidak ada booking dengan status "{{ $selectedStatus }}"
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</x-app-layout>


@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-6">Booking Saya</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($bookings->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500">Anda belum memiliki booking.</p>
                    <a href="{{ route('home') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Cari Gedung
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Gedung</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Tanggal Mulai</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Tanggal Selesai</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Total Harga</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($bookings as $booking)
                            <tr>
                                <td class="px-6 py-4">
                                    <a href="{{ route('gedung.show', $booking->gedung) }}" class="text-blue-600 hover:underline">
                                        {{ $booking->gedung->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">{{ $booking->start_date->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4">{{ $booking->end_date->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-sm rounded-full 
                                        @if($booking->status == 'Menunggu Konfirmasi') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status == 'Dikonfirmasi') bg-green-100 text-green-800
                                        @elseif($booking->status == 'Dibatalkan') bg-red-100 text-red-800
                                        @elseif($booking->status == 'Selesai') bg-blue-100 text-blue-800
                                        @endif">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($booking->status == 'Menunggu Konfirmasi')
                                        <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
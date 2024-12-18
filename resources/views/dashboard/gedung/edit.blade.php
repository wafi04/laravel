<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto  shadow-xl rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6">
                <h1 class="text-3xl font-bold text-white text-center">Edit Gedung</h1>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('dashboard.gedung.update', $gedung->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Nama Gedung</label>
                        <input type="text" name="name" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               value="{{ old('name', $gedung->name) }}" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Alamat Gedung</label>
                        <input type="text" name="alamat" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               value="{{ old('alamat', $gedung->alamat) }}" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Harga Gedung</label>
                        <input type="number" name="harga" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               value="{{ old('harga', $gedung->harga) }}" step="0.01" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Kapasitas Gedung</label>
                        <input type="number" name="kapasitas" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               value="{{ old('kapasitas', $gedung->kapasitas) }}">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Deskripsi Gedung</label>
                    <textarea name="deskripsi" 
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              rows="4">{{ old('deskripsi', $gedung->deskripsi) }}</textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Ketersediaan Gedung</label>
                    <select name="ketersediaan" 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                            required>
                        <option value="Tersedia" {{ old('ketersediaan', $gedung->ketersediaan) == 'Tersedia' ? 'selected' : '' }}>
                            Tersedia
                        </option>
                        <option value="Tidak Tersedia" {{ old('ketersediaan', $gedung->ketersediaan) == 'Tidak Tersedia' ? 'selected' : '' }}>
                            Tidak Tersedia
                        </option>
                    </select>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <button type="submit" 
                            class="bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold py-2 px-6 rounded-full hover:opacity-90 transition duration-300">
                        Perbarui Gedung
                    </button>
                    <a href="{{ route('dashboard.gedung.index') }}" 
                       class="text-blue-600 hover:underline">
                        Kembali ke Daftar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Tambah Gedung Baru</h1>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative m-4" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('gedung.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nama Gedung
                        </label>
                        <x-text-input 
                            id="name" 
                            name="name"
                            class="w-full"
                            placeholder="Masukkan nama gedung"
                        />
                    </div>

                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Alamat Gedung
                        </label>
                        <x-text-input 
                            id="alamat" 
                            name="alamat"
                            class="w-full"
                            placeholder="Masukkan alamat gedung"
                        />
                    </div>

                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Harga Gedung
                        </label>
                        <x-text-input 
                            type="number"
                            id="harga" 
                            name="harga"
                            class="w-full"
                            placeholder="Masukkan harga gedung"
                            inputmode="numeric"
                        />
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Deskripsi Gedung
                        </label>
                        <textarea 
                            id="deskripsi" 
                            name="deskripsi" 
                            rows="4"
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            placeholder="Masukkan deskripsi gedung"
                        ></textarea>
                    </div>

                    <div>
                        <label for="kapasitas" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Kapasitas Gedung
                        </label>
                        <x-text-input 
                            type="number"
                            id="kapasitas" 
                            name="kapasitas"
                            class="w-full"
                            placeholder="Masukkan kapasitas gedung"
                            inputmode="numeric"
                        />
                    </div>

                    <div>
                        <label for="ketersediaan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ketersediaan Gedung
                        </label>
                        <select 
                            name="ketersediaan" 
                            id="ketersediaan"
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            required
                        >
                            <option value="Tersedia">Tersedia</option>
                            <option value="Tidak Tersedia">Tidak Tersedia</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <x-primary-button type="submit">
                        Tambah Gedung
                    </x-primary-button>
                    <a 
                        href="{{ route('gedung.index') }}" 
                        class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                        Kembali ke Daftar Gedung
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
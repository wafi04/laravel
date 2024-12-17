<x-app-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                Daftar Gedung
            </h1>
            <a href="{{ route('dashboard.gedung.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                                                                <x-primary-button>
            Tambah
                                                                                </x-primary-button>
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Nama Gedung
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Alamat
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($gedungs as $gedung)
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                {{ $gedung->name }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $gedung->alamat }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex gap-4">
                                    <a href="{{ route('dashboard.gedung.edit', $gedung->id) }}" 
                                                                                <x-primary-button>
                                         {{ __('edit') }}
                                        </x-primary-button>
                                    </a>
                                    <form action="{{ route('dashboard.gedung.destroy', $gedung->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus gedung ini?')">
                                        @csrf
                                        @method('DELETE')
                                            <x-primary-button>
                                                {{ __('Delete') }}
                                            </x-primary-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data gedung
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
   
</x-app-layout>
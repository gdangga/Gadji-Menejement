<x-layouts.app>
    <x-slot:header>
        Daftar Karyawan
    </x-slot:header>

    <div class="bg-white overflow-hidden shadow-sm sm rounded-lg">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Data Karyawan</h2>
                <a href="{{ route('karyawan.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    + Tambah Karyawan
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Posisi</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Tgl Bergabung</th>
                            <th class="py-3 px-6 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($karyawans as $karyawan)
                        <tr>
                            <td class="py-4 px-6">{{ $karyawan->nama_lengkap }}</td>
                            <td class="py-4 px-6 hidden sm:table-cell">{{ $karyawan->posisi ?? '-' }}</td>
                            <td class="py-4 px-6 hidden md:table-cell">{{ $karyawan->tanggal_bergabung ? \Carbon\Carbon::parse($karyawan->tanggal_bergabung)->format('d M Y') : '-' }}</td>
                            <td class="py-4 px-6 text-right text-sm font-medium">
                                <a href="{{ route('karyawan.show', $karyawan) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                <a href="{{ route('karyawan.edit', $karyawan) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Edit</a>
                                <form action="{{ route('karyawan.destroy', $karyawan) }}" method="POST" class="inline-block ml-4 delete-form" data-confirm-message="Yakin hapus karyawan: {{ $karyawan->nama_lengkap }}?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 px-6 text-center text-gray-500">Tidak ada data karyawan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $karyawans->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
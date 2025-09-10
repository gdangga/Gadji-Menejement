<x-layouts.app>
    <x-slot:header>
        Tambah Proyek untuk: {{ $karyawan->nama_lengkap }}
    </x-slot:header>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <form action="{{ route('proyek.store', $karyawan) }}" method="POST">
                @csrf
                <div class="space-y-4 max-w-lg">
                    <div>
                        <label for="nama_proyek" class="block text-sm font-medium text-gray-700">Nama Proyek</label>
                        <input type="text" name="nama_proyek" id="nama_proyek" value="{{ old('nama_proyek') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label for="tanggal_pengerjaan" class="block text-sm font-medium text-gray-700">Tanggal Pengerjaan</label>
                        <input type="date" name="tanggal_pengerjaan" id="tanggal_pengerjaan" value="{{ old('tanggal_pengerjaan', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                     <div>
                        <label for="jumlah_upah" class="block text-sm font-medium text-gray-700">Jumlah Upah (Rp)</label>
                        <input type="number" step="1000" name="jumlah_upah" id="jumlah_upah" value="{{ old('jumlah_upah') }}" placeholder="Contoh: 500000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                </div>
                <div class="mt-6 flex items-center space-x-4">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 shadow-sm">Simpan Proyek</button>
                    <a href="{{ route('karyawan.show', $karyawan) }}" class="px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-md hover:bg-gray-300 shadow-sm">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
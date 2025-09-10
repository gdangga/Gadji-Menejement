<x-layouts.app>
    <x-slot:header>
        Dashboard
    </x-slot:header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow-sm sm rounded-lg p-6">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Jumlah Karyawan</h3>
            <p class="text-3xl font-bold mt-2">{{ $jumlahKaryawan }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm rounded-lg p-6">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Upah Bulan Ini</h3>
            <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalUpahBulanIni, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm rounded-lg p-6">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Proyek Bulan Ini</h3>
            <p class="text-3xl font-bold mt-2">{{ $proyekBulanIni }} Proyek</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white overflow-hidden shadow-sm sm rounded-lg p-6">
            <h3 class="font-semibold text-lg mb-4">Akses Cepat</h3>
            <div class="space-y-4">
                <a href="{{ route('karyawan.create') }}" class="w-full text-center block px-4 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition">
                    + Tambah Karyawan Baru
                </a>
                <a href="{{ route('penggajian.index') }}" class="w-full text-center block px-4 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-md hover:bg-green-700 transition">
                    Proses Penggajian
                </a>
            </div>
        </div>
        <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm rounded-lg p-6">
            <h3 class="font-semibold text-lg mb-4">Aktivitas Proyek Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($aktivitasTerbaru as $proyek)
                            <tr>
                                <td class="py-4">
                                    <p class="font-medium text-gray-900">{{ $proyek->nama_proyek }}</p>
                                    <p class="text-sm text-gray-500">untuk {{ $proyek->karyawan->nama_lengkap }}</p>
                                </td>
                                <td class="py-4 text-right">
                                    <p class="font-medium text-gray-900">Rp {{ number_format($proyek->jumlah_upah, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-500">{{ $proyek->created_at->diffForHumans() }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-4 text-gray-500">Belum ada aktivitas proyek.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
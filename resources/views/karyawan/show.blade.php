<x-layouts.app>
    <x-slot:header>
        Detail: {{ $karyawan->nama_lengkap }}
    </x-slot:header>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6 border-b flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <p><strong>Email:</strong> {{ $karyawan->email ?? '-' }}</p>
                <p><strong>Posisi:</strong> {{ $karyawan->posisi ?? '-' }}</p>
                
                {{-- BLOK TOTAL DIPERBARUI --}}
                <div class="mt-2 space-y-1">
                    <p class="text-md">
                        <strong>Total Upah (Periode Ini):</strong> 
                        <span class="font-semibold text-green-600">Rp {{ number_format($totalUpahPeriodeIni, 0, ',', '.') }}</span>
                    </p>
                    <p class="text-lg">
                        <strong>Total Seluruh Upah:</strong> 
                        <span class="font-semibold text-indigo-600">Rp {{ number_format($totalUpahSeluruhnya, 0, ',', '.') }}</span>
                    </p>
                </div>
            </div>
            <a href="{{ route('proyek.create', $karyawan) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 w-full sm:w-auto text-center whitespace-nowrap">
                + Tambah Proyek
            </a>
        </div>

        <div class="p-6">
            <div class="md:flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-2 md:mb-0">Daftar Proyek</h3>
                
                {{-- FORM FILTER BARU --}}
                <form action="{{ route('karyawan.show', $karyawan) }}" method="GET" class="flex items-center space-x-2">
                    <select name="bulan" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                        @endfor
                    </select>
                    <select name="tahun" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @for ($i = now()->year; $i >= now()->year - 5; $i--)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm shadow-sm hover:bg-indigo-700">Filter</button>
                </form>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tampilan Tabel untuk Desktop --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full bg-white">
                     <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-6 text-left">Nama Proyek</th>
                            <th class="py-3 px-6 text-left">Tanggal</th>
                            <th class="py-3 px-6 text-left">Upah</th>
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($proyeks as $proyek)
                        <tr>
                            <td class="py-4 px-6">{{ $proyek->nama_proyek }}</td>
                            <td class="py-4 px-6">{{ \Carbon\Carbon::parse($proyek->tanggal_pengerjaan)->format('d M Y') }}</td>
                            <td class="py-4 px-6">Rp {{ number_format($proyek->jumlah_upah, 0, ',', '.') }}</td>
                            <td class="py-4 px-6">
                                @if($proyek->slip_gaji_id)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sudah Dibayar</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum Dibayar</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right text-sm">
                                <div class="flex justify-end items-center space-x-2">
                                    @if($proyek->slip_gaji_id)
                                    <form action="{{ route('proyek.batalkan', $proyek) }}" method="POST" onsubmit="return confirm('Yakin batalkan pembayaran proyek ini?');">
                                        @csrf
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900 font-semibold">Batalkan</button>
                                    </form>
                                    <span>|</span>
                                    @endif
                                    <form action="{{ route('proyek.destroy', $proyek) }}" method="POST" onsubmit="return confirm('Anda YAKIN ingin menghapus proyek ini? Data tidak bisa dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-gray-500">Tidak ada proyek pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Tampilan Kartu untuk Mobile --}}
            <div class="md:hidden space-y-4">
                @forelse ($proyeks as $proyek)
                    <div class="border rounded-lg p-4 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div class="flex-grow">
                                <p class="font-semibold text-gray-800">{{ $proyek->nama_proyek }}</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($proyek->tanggal_pengerjaan)->format('d M Y') }}</p>
                            </div>
                            <div class="text-right ml-4 flex-shrink-0">
                                @if($proyek->slip_gaji_id)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sudah Dibayar</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Belum Dibayar</span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-500">Upah</p>
                                <p class="font-semibold text-gray-800">Rp {{ number_format($proyek->jumlah_upah, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center space-x-2 text-sm">
                                @if($proyek->slip_gaji_id)
                                <form action="{{ route('proyek.batalkan', $proyek) }}" method="POST" onsubmit="return confirm('Yakin batalkan pembayaran proyek ini?');">
                                    @csrf
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900 font-semibold">Batalkan</button>
                                </form>
                                <span>|</span>
                                @endif
                                <form action="{{ route('proyek.destroy', $proyek) }}" method="POST" onsubmit="return confirm('Anda YAKIN ingin menghapus proyek ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-gray-500">
                        {{-- PESAN KOSONG DIPERBARUI --}}
                        Tidak ada proyek pada periode ini.
                    </div>
                @endforelse
            </div>

             {{-- PAGINASI DIPERBARUI --}}
             <div class="mt-4">{{ $proyeks->appends(request()->query())->links() }}</div>
        </div>
    </div>
</x-layouts.app>
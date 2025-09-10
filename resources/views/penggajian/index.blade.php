<x-layouts.app>
    <x-slot:header>
        Proses Penggajian
    </x-slot:header>

    <div class="space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
                <form action="{{ route('penggajian.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-end gap-4">
                    <div>
                        <label for="bulan" class="block text-sm font-medium">Bulan:</label>
                        <select name="bulan" id="bulan" class="rounded-md border-gray-300 mt-1 w-full sm:w-auto">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="tahun" class="block text-sm font-medium">Tahun:</label>
                        <select name="tahun" id="tahun" class="rounded-md border-gray-300 mt-1 w-full sm:w-auto">
                            @for ($i = now()->year; $i >= now()->year - 5; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md w-full sm:w-auto">Filter</button>
                </form>

                <div class="mt-4 md:mt-0">
                    <a href="#" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700 w-full text-center block sm:w-auto">
                        Export Laporan (Excel)
                    </a>
                </div>
            </div>
        </div>

        @forelse ($karyawans as $karyawan)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <form action="{{ route('penggajian.cetak') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="karyawan_id" value="{{ $karyawan->id }}">
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">

                <div class="p-6 border-b flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <span class="font-semibold text-lg">{{ $karyawan->nama_lengkap }}</span>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm hover:bg-green-700 w-full sm:w-auto">
                        Generate & Cetak Slip
                    </button>
                </div>

                <div class="p-6">
                    <h4 class="text-gray-600 mb-2">Pilih proyek yang akan dibayarkan:</h4>
                    <div class="space-y-2">
                        @foreach ($karyawan->proyeks as $proyek)
                        <label for="proyek_{{ $proyek->id }}" class="flex flex-col sm:flex-row items-start sm:items-center p-3 border rounded-md hover:bg-gray-50 cursor-pointer">
                            <div class="flex items-center">
                                <input type="checkbox" id="proyek_{{ $proyek->id }}" name="proyek_ids[]" value="{{ $proyek->id }}" checked class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-3 flex-grow">{{ $proyek->nama_proyek }} ({{ \Carbon\Carbon::parse($proyek->tanggal_pengerjaan)->format('d M Y') }})</span>
                            </div>
                            <span class="font-mono mt-2 sm:mt-0 sm:ml-auto">Rp {{ number_format($proyek->jumlah_upah, 0, ',', '.') }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
        @empty
        <div class="bg-white text-center p-6 rounded-lg shadow-sm">
            <p class="text-gray-500">Tidak ada proyek yang perlu dibayarkan untuk periode ini.</p>
        </div>
        @endforelse
    </div>
</x-layouts.app>
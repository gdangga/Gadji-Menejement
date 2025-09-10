<x-layouts.app>
    <x-slot:header>
        Edit Data Karyawan: {{ $karyawan->nama_lengkap }}
    </x-slot:header>

    <div class="bg-white overflow-hidden shadow-sm sm rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <form action="{{ route('karyawan.update', $karyawan) }}" method="POST">
                @csrf
                @method('PUT')
                @include('karyawan._form')
            </form>
        </div>
    </div>
</x-layouts.app>
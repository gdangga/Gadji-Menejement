<x-layouts.app>
    <x-slot:header>
        Tambah Karyawan Baru
    </x-slot:header>

    <div class="bg-white overflow-hidden shadow-sm sm rounded-lg">
        <div class="p-6 border-b border-gray-200">
            <form action="{{ route('karyawan.store') }}" method="POST">
                @csrf
                @include('karyawan._form')
            </form>
        </div>
    </div>
</x-layouts.app>
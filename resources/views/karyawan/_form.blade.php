<div class="space-y-4">
    <div>
        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $karyawan->nama_lengkap ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
    </div>
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $karyawan->email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
     <div>
        <label for="posisi" class="block text-sm font-medium text-gray-700">Posisi</label>
        <input type="text" name="posisi" id="posisi" value="{{ old('posisi', $karyawan->posisi ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="tanggal_bergabung" class="block text-sm font-medium text-gray-700">Tanggal Bergabung</label>
        <input type="date" name="tanggal_bergabung" id="tanggal_bergabung" value="{{ old('tanggal_bergabung', $karyawan->tanggal_bergabung ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
</div>
<div class="mt-6 flex items-center space-x-4">
    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Simpan</button>
    <a href="{{ route('karyawan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</a>
</div>
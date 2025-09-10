<?php
namespace App\Http\Controllers;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller {
    public function index() {
        $karyawans = Karyawan::latest()->paginate(10);
        return view('karyawan.index', compact('karyawans'));
    }

    public function create() {
        return view('karyawan.create');
    }

    public function store(Request $request) {
        $request->validate(['nama_lengkap' => 'required|string|max:255']);
        Karyawan::create($request->all());
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function show(Request $request, Karyawan $karyawan)
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);
        $proyekQuery = $karyawan->proyeks();
        $filteredQuery = $karyawan->proyeks()
                                ->whereYear('tanggal_pengerjaan', $tahun)
                                ->whereMonth('tanggal_pengerjaan', $bulan);
        $proyeks = $filteredQuery->latest()->paginate(10);
        $totalUpahPeriodeIni = $filteredQuery->sum('jumlah_upah');
        $totalUpahSeluruhnya = $karyawan->proyeks()->sum('jumlah_upah');
        return view('karyawan.show', compact(
            'karyawan', 
            'proyeks', 
            'totalUpahPeriodeIni', 
            'totalUpahSeluruhnya',
            'bulan',
            'tahun'
        ));
    }

    public function edit(Karyawan $karyawan) {
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan) {
        $request->validate(['nama_lengkap' => 'required|string|max:255']);
        $karyawan->update($request->all());
        return redirect()->route('karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan) {
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
    }
}
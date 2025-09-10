<?php
namespace App\Http\Controllers;
use App\Models\Karyawan;
use App\Models\Proyek;
use Illuminate\Http\Request;

class DashboardController extends Controller {
    public function index() {
        $jumlahKaryawan = Karyawan::count();
        $totalUpahBulanIni = Proyek::whereYear('tanggal_pengerjaan', now()->year)
                                   ->whereMonth('tanggal_pengerjaan', now()->month)
                                   ->sum('jumlah_upah');
        $proyekBulanIni = Proyek::whereYear('tanggal_pengerjaan', now()->year)
                                ->whereMonth('tanggal_pengerjaan', now()->month)
                                ->count();
        $aktivitasTerbaru = Proyek::with('karyawan')->latest()->take(5)->get();

        return view('dashboard', compact('jumlahKaryawan', 'totalUpahBulanIni', 'proyekBulanIni', 'aktivitasTerbaru'));
    }
}
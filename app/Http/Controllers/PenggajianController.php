<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Proyek;
use App\Models\SlipGaji;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\ProyekPerKaryawanExport;
use App\Mail\SlipGajiMail;
use Exception;

class PenggajianController extends Controller
{
    public function index(Request $request)
    {
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        $karyawans = Karyawan::with(['proyeks' => function ($query) use ($bulan, $tahun) {
            $query->whereYear('tanggal_pengerjaan', $tahun)
                  ->whereMonth('tanggal_pengerjaan', $bulan)
                  ->whereNull('slip_gaji_id');
        }])->whereHas('proyeks', function ($query) use ($bulan, $tahun) {
             $query->whereYear('tanggal_pengerjaan', $tahun)
                  ->whereMonth('tanggal_pengerjaan', $bulan)
                  ->whereNull('slip_gaji_id');
        })->get();
        
        return view('penggajian.index', compact('karyawans', 'bulan', 'tahun'));
    }

    public function cetakSlip(Request $request)
    {
        $request->validate([
            'proyek_ids' => 'required|array|min:1',
            'proyek_ids.*' => 'exists:proyeks,id',
            'karyawan_id' => 'required|exists:karyawans,id',
            'bulan' => 'required|integer',
            'tahun' => 'required|integer',
        ]);

        $karyawan = Karyawan::findOrFail($request->karyawan_id);
        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;

        $proyeksToPay = Proyek::whereIn('id', $request->proyek_ids)->get();
        
        if ($proyeksToPay->isEmpty()) {
            return back()->with('error', 'Tidak ada proyek yang dipilih untuk dibayar.');
        }

        $totalUpahBaru = $proyeksToPay->sum('jumlah_upah');

        $slipGaji = SlipGaji::firstOrCreate(
            ['karyawan_id' => $karyawan->id, 'periode_bulan' => $bulan, 'periode_tahun' => $tahun],
            ['total_upah' => 0]
        );

        $slipGaji->total_upah += $totalUpahBaru;
        $slipGaji->save();

        $proyeksToPay->each->update(['slip_gaji_id' => $slipGaji->id]);

        $semuaProyekDiSlip = Proyek::where('slip_gaji_id', $slipGaji->id)->get();
        $periode = Carbon::createFromDate($tahun, $bulan, 1)->format('F Y');
        $data = ['slipGaji' => $slipGaji, 'proyeks' => $semuaProyekDiSlip, 'karyawan' => $karyawan, 'periode' => $periode];

        $pdf = PDF::loadView('penggajian.slip_pdf', $data);
        $namaFile = 'slip-gaji-' . str_replace(' ', '-', $karyawan->nama_lengkap) . '-' . $bulan . '-' . $tahun . '.pdf';

        $akuntan = Auth::user();

        try {
            $pdfContent = $pdf->output(); 
            Mail::to($akuntan->email)->send(new SlipGajiMail($pdfContent, $namaFile, $karyawan, $periode));
        } catch (Exception $e) {
        }

        return $pdf->download($namaFile);
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer',
        ]);

        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;

        $namaBulan = Carbon::create()->month($bulan)->format('F');
        $namaFile = "Laporan Proyek - {$namaBulan} {$tahun}.xlsx";

        return Excel::download(new ProyekPerKaryawanExport($bulan, $tahun), $namaFile);
    }
}
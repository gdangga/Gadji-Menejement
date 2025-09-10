<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Proyek;
use App\Models\SlipGaji;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function create(Karyawan $karyawan)
    {
        return view('proyek.create', compact('karyawan'));
    }

    public function store(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'tanggal_pengerjaan' => 'required|date',
            'jumlah_upah' => 'required|numeric|min:0',
        ]);
        $karyawan->proyeks()->create($request->all());
        return redirect()->route('karyawan.show', $karyawan->id)->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function batalkanPembayaran(Proyek $proyek)
    {
        $slipGaji = SlipGaji::find($proyek->slip_gaji_id);

        if ($slipGaji) {
            $slipGaji->total_upah -= $proyek->jumlah_upah;
            
            if ($slipGaji->total_upah <= 0) {
                $slipGaji->delete();
            } else {
                $slipGaji->save();
            }
        }

        $proyek->slip_gaji_id = null;
        $proyek->save();

        return back()->with('success', 'Pembayaran untuk proyek "' . $proyek->nama_proyek . '" berhasil dibatalkan.');
    }

    /**
     * Menghapus data proyek dari database.
     */
    public function destroy(Proyek $proyek)
    {
        $slipGajiId = $proyek->slip_gaji_id;

        // Hapus proyek
        $proyek->delete();

        // Jika proyek ini sebelumnya ada di slip gaji, hitung ulang totalnya
        if ($slipGajiId) {
            $slipGaji = SlipGaji::find($slipGajiId);
            if ($slipGaji) {
                // Hitung ulang total upah slip berdasarkan proyek yang tersisa
                $newTotal = Proyek::where('slip_gaji_id', $slipGajiId)->sum('jumlah_upah');

                // Jika tidak ada lagi proyek di slip ini, hapus slipnya
                if ($newTotal <= 0) {
                    $slipGaji->delete();
                } else {
                    $slipGaji->total_upah = $newTotal;
                    $slipGaji->save();
                }
            }
        }

        return back()->with('success', 'Proyek berhasil dihapus.');
    }
}
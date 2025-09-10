<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model {
    use HasFactory;
    protected $fillable = ['karyawan_id', 'slip_gaji_id', 'nama_proyek', 'tanggal_pengerjaan', 'jumlah_upah'];

    public function karyawan() {
        return $this->belongsTo(Karyawan::class);
    }
}
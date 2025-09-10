<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlipGaji extends Model {
    use HasFactory;
    protected $fillable = ['karyawan_id', 'periode_bulan', 'periode_tahun', 'total_upah'];

    public function karyawan() {
        return $this->belongsTo(Karyawan::class);
    }
}
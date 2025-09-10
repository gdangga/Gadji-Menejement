<?php

namespace App\Exports;

use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ProyekPerKaryawanExport implements WithMultipleSheets
{
    protected $bulan;
    protected $tahun;
    protected $karyawans;

    public function __construct(int $bulan, int $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;

        // Ambil data karyawan yang memiliki proyek pada periode yang dipilih
        $this->karyawans = Karyawan::whereHas('proyeks', function ($query) {
            $query->whereYear('tanggal_pengerjaan', $this->tahun)
                  ->whereMonth('tanggal_pengerjaan', $this->bulan);
        })->with(['proyeks' => function ($query) {
            $query->whereYear('tanggal_pengerjaan', $this->tahun)
                  ->whereMonth('tanggal_pengerjaan', $this->bulan);
        }])->get();
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->karyawans as $karyawan) {
            // Membuat satu sheet untuk setiap karyawan
            $sheets[] = new ProyekSheet($karyawan, $this->bulan, $this->tahun);
        }

        return $sheets;
    }
}

// Class terpisah untuk mengatur setiap sheet
class ProyekSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize, WithEvents, WithColumnFormatting
{
    private $karyawan;
    private $bulan;
    private $tahun;

    public function __construct(Karyawan $karyawan, int $bulan, int $tahun)
    {
        $this->karyawan = $karyawan;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $proyeks = $this->karyawan->proyeks()
            ->whereYear('tanggal_pengerjaan', $this->tahun)
            ->whereMonth('tanggal_pengerjaan', $this->bulan)
            ->get();

        $data = $proyeks->map(function ($proyek) {
            return [
                'nama_karyawan' => $this->karyawan->nama_lengkap,
                'nama_proyek' => $proyek->nama_proyek,
                'upah_proyek' => $proyek->jumlah_upah,
                'tanggal_pengerjaan' => $proyek->tanggal_pengerjaan,
            ];
        });

        // Menambahkan baris total di akhir
        $totalGaji = $proyeks->sum('jumlah_upah');
        $data->push([
            'nama_karyawan' => '',
            'nama_proyek' => 'TOTAL GAJI',
            'upah_proyek' => $totalGaji,
            'tanggal_pengerjaan' => '',
        ]);

        return $data;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        // Memberi nama sheet, misal: "Budi-Setiawan"
        return str_replace(' ', '-', $this->karyawan->nama_lengkap);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Proyek yang Dikerjakan',
            'Harga per Proyek',
            'Waktu Pengerjaan',
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Format kolom harga
            'D' => 'dd-mm-yyyy', // Format kolom tanggal
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Style untuk header
                $event->sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFe0e0e0']],
                ]);

                // Style untuk baris total
                $totalRow = $this->collection()->count();
                $event->sheet->getStyle('B' . $totalRow . ':C' . $totalRow)->applyFromArray([
                    'font' => ['bold' => true],
                ]);
            },
        ];
    }
}
<?php

namespace App\Exports;

use IntlDateFormatter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class SuratMasukExport implements FromCollection, WithHeadings
{
    protected $suratMasuk;
    protected $formatter;

    public function __construct($suratMasuk)
    {
        $this->suratMasuk = $suratMasuk;
        date_default_timezone_set('Asia/Jakarta');

        // Create the formatter with the 'id_ID' Indonesian locale
        $this->formatter = new IntlDateFormatter(
            'id_ID',
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE 
        );
    }
    

    public function headings(): array
    {
        return [
            'Jenis Surat',
            'No Agenda',
            'Tanggal Masuk',
            'Tanggal Surat',
            'Nomor Surat',
            'Surat Dari',
            'Isi',
            'Perihal',
            'Disposisi',
            'Tanggal Agenda',
            'Keterangan Disposisi',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $this->formatter->setPattern('EEEE, d MMMM Y');

        $data = collect($this->suratMasuk)->map(function ($item) {
            return [
                'Jenis' => $item['jns'] == "1" ? 'Undangan' : ($item['jns'] == "2" ? "Non Undangan" : "Usulan Pembangunan"),
                $item['no_agenda'],
                $this->formatter->format(strtotime($item['time'])),
                $this->formatter->format(strtotime($item['tanggal'])),
                $item['no_surat'],
                $item['asal'],
                $item['acara'],
                $item['perihal'],
                $item['disposisi_all'],
                $this->formatter->format(strtotime($item['tgl_agenda'])),
                $item['disposisi_keterangan'],
            ];
        });
        return $data;
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class BpjsService extends Model
{
    //<?php

    // Tentukan nama tabel jika berbeda dari penamaan konvensional Laravel
    protected $table = 'bpjs_services';

    // Jika tabel menggunakan primary key selain 'id', maka tentukan di sini
    protected $primaryKey = 'ID_Pasien';

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'Nama_Pasien', // Nama pasien atau data lain
        'Tanggal_Lahir',
        'Jenis_Kelamin', 
        'No_BPJS', // Nomor BPJS
        'Tanggal_Pelayanan',
        'Jenis_Pelayanan', // Tanggal pengajuan
        'Dokter',
        'Keterangan',
        'file_jasper', // Path file Jasper atau PDF jika disimpan di sini
    ];

    // Jika tabel ini tidak memiliki kolom timestamps (created_at dan updated_at)
    public $timestamps = false;

    // Relasi antar model jika diperlukan
    // public function relasiContoh()
    // {
    //     return $this->belongsTo(RelasiContoh::class);
    // }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'ID_Pasien';
    public $timestamps = true;

    protected $fillable = [
        'nm_pasien',
        'umurdaftar',
        'tgl_lahir',
        'pekerjaan',
        'alamat',
        'no_rkm_medis',
        'ruang',
        'jk',
        'tgl_registrasi',
        'tanggalkeluar',
        'kondisi_pulang',
    ];

     // Relasi ke Diagnosis
     public function diagnosis()
     {
         return $this->hasOne(Diagnosis::class, 'ID_Pasien');
     }
}

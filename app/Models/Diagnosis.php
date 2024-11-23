<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;
    protected $table = 'diagnoses';  // Specify the table name

    protected $fillable = [
        'ID_Pasien',
        'keluhan_utama',
        'jalanya_penyakit',
        'pemeriksaan_penunjang',
        'hasil_laborat',
        'diagnosa_utama',
        'diagnosa_sekunder',
        'diagnosa_sekunder2',
        'diagnosa_sekunder3',
        'diagnosa_sekunder4',
        'prosedur_utama',
        'prosedur_sekunder',
        'prosedur_sekunder2',
        'prosedur_sekunder3',
        'kondisi_pulang',
        'obat_pulang',
        'nm_dokter',
    ];

    // Relasi ke Patient
  // Relasi ke Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'ID_Pasien');
    }
}

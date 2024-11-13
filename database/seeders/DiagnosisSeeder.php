<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Diagnosis;
use App\Models\Patient;
use Faker\Factory as Faker;

class DiagnosisSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $patients = Patient::all();

        foreach ($patients as $patient) {
            Diagnosis::create([
                'ID_Pasien' => $patient->ID_Pasien,
                'keluhan_utama' => $faker->sentence,
                'jalanya_penyakit' => $faker->paragraph,
                'pemeriksaan_penunjang' => $faker->sentence,
                'hasil_laborat' => $faker->sentence,
                'diagnosa_utama' => $faker->word,
                'diagnosa_sekunder' => $faker->word,
                'diagnosa_sekunder2' => $faker->optional()->word,
                'diagnosa_sekunder3' => $faker->optional()->word,
                'diagnosa_sekunder4' => $faker->optional()->word,
                'prosedur_utama' => $faker->word,
                'prosedur_sekunder' => $faker->optional()->word,
                'prosedur_sekunder2' => $faker->optional()->word,
                'prosedur_sekunder3' => $faker->optional()->word,
                'kondisi_pulang' => $faker->word,
                'obat_pulang' => $faker->word,
                'nm_dokter' => $faker->name,
            ]);
        }
    }
}

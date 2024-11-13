<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 50; $i++) {
            DB::table('patients')->insert([
                'nm_pasien' => $faker->name,
                'umurdaftar' => $faker->numberBetween(1, 100),
                'tgl_lahir' => $faker->dateTimeBetween('-60 years', '-20 years')->format('Y-m-d'),
                'pekerjaan' => $faker->jobTitle,
                'alamat' => $faker->address,
                'no_rkm_medis' => $faker->unique()->numerify('RM-#####'),
                'ruang' => $faker->word,
                'jk' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'tgl_registrasi' => $faker->dateTimeThisYear()->format('Y-m-d'),
                'tanggalkeluar' => optional($faker->optional()->dateTimeThisYear())->format('Y-m-d'),
            ]);
        }
    }
}

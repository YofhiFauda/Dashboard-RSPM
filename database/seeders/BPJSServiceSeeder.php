<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BPJSServiceSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $jasperFiles = [
            'C:\xampp\htdocs\dashboard-rs-paru\public\storage\jasper\report.jasper',
            'C:\xampp\htdocs\dashboard-rs-paru\public\storage\jasper\report10.jasper'
        ];

        for ($i = 1; $i <= 50; $i++) {
            DB::table('bpjs_services')->insert([
                'Nama_Pasien' => $faker->name,
                'Tanggal_Lahir' => $faker->dateTimeBetween('-60 years', '-20 years')->format('Y-m-d'),
                'Jenis_Kelamin' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'No_BPJS' => $faker->unique()->numerify('####-####-####-####'),
                'Tanggal_Pelayanan' => $faker->dateTimeThisYear()->format('Y-m-d'),
                'Jenis_Pelayanan' => $faker->randomElement(['Rawat Inap', 'Rawat Jalan', 'Ujian Lab']),
                'Dokter' => $faker->name('male'|'female'),
                'Keterangan' => $faker->sentence,
                'file_jasper' => $faker->randomElement($jasperFiles),
            ]);
        }
    }
}

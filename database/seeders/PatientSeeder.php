<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        // Total pasien
        $totalPatients = 20;

        // Rasio distribusi: 60% untuk bulan ini, 40% untuk bulan sebelumnya
        $patientsThisMonth = (int)($totalPatients * 0.2); // Pasien bulan ini
        $patientsLastMonth = $totalPatients - $patientsThisMonth; // Pasien bulan lalu

        // Generate pasien bulan ini
        for ($i = 1; $i <= $patientsThisMonth; $i++) {
            $no_rkm_medis = $this->generateUniqueMedicalRecord($faker);

            DB::table('patients')->insert([
                'nm_pasien' => $faker->name,
                'umurdaftar' => $faker->numberBetween(1, 100),
                'tgl_lahir' => $faker->dateTimeBetween('-60 years', '-20 years')->format('Y-m-d'),
                'pekerjaan' => $faker->jobTitle,
                'alamat' => $faker->address,
                'no_rkm_medis' => $no_rkm_medis,
                'ruang' => $faker->word,
                'jk' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'tgl_registrasi' => $faker->dateTimeBetween('2024-11-01', '2024-11-17')->format('Y-m-d'),
                'tanggalkeluar' => optional($faker->optional()->dateTimeBetween('2024-11-01', '2024-11-17'))->format('Y-m-d'),
                'kondisi_pulang' => $faker->randomElement(['Pulih', 'Meninggal']),
            ]);
        }

        // Generate pasien bulan sebelumnya
        for ($i = 1; $i <= $patientsLastMonth; $i++) {
            $no_rkm_medis = $this->generateUniqueMedicalRecord($faker);

            DB::table('patients')->insert([
                'nm_pasien' => $faker->name,
                'umurdaftar' => $faker->numberBetween(1, 100),
                'tgl_lahir' => $faker->dateTimeBetween('-60 years', '-20 years')->format('Y-m-d'),
                'pekerjaan' => $faker->jobTitle,
                'alamat' => $faker->address,
                'no_rkm_medis' => $no_rkm_medis,
                'ruang' => $faker->word,
                'jk' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'tgl_registrasi' => $faker->dateTimeBetween('2024-10-01', '2024-10-31')->format('Y-m-d'),
                'tanggalkeluar' => optional($faker->optional()->dateTimeBetween('2024-10-01', '2024-10-31'))->format('Y-m-d'),
                'kondisi_pulang' => $faker->randomElement(['Pulih', 'Meninggal']),
            ]);
        }
    }

    /**
     * Generate a unique medical record number.
     */
    private function generateUniqueMedicalRecord($faker)
    {
        $no_rkm_medis = $faker->unique()->numerify('RM-######');
        while (DB::table('patients')->where('no_rkm_medis', $no_rkm_medis)->exists()) {
            $no_rkm_medis = $faker->unique()->numerify('RM-######');
        }
        return $no_rkm_medis;
    }
}




// <?php

// namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
// use Faker\Factory as Faker;
// class PatientSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run()
//     {
//         $faker = Faker::create();

//         for ($i = 1; $i <= 1400; $i++) {
//             // Loop untuk memastikan no_rkm_medis unik
//             $no_rkm_medis = $faker->unique()->numerify('RM-######');
//             while (DB::table('patients')->where('no_rkm_medis', $no_rkm_medis)->exists()) {
//                 $no_rkm_medis = $faker->unique()->numerify('RM-######');
//             }

//             // Penentuan tahun berdasarkan rasio 2:1
//             $useCurrentYear = rand(1, 3) <= 2; // 2:1 ratio (2 untuk tahun sekarang, 1 untuk tahun kemarin)

//             $tgl_registrasi = $useCurrentYear
//                 ? $faker->dateTimeBetween('2024-01-01', '2024-11-17')->format('Y-m-d') // Tahun sekarang
//                 : $faker->dateTimeBetween('2023-01-01', '2023-12-31')->format('Y-m-d'); // Tahun kemarin

//             $tanggalkeluar = $useCurrentYear
//                 ? optional($faker->optional()->dateTimeBetween('2024-01-01', '2024-11-17'))->format('Y-m-d') // Tahun sekarang
//                 : optional($faker->optional()->dateTimeBetween('2023-01-01', '2023-12-31'))->format('Y-m-d'); // Tahun kemarin

//             DB::table('patients')->insert([
//                 'nm_pasien' => $faker->name,
//                 'umurdaftar' => $faker->numberBetween(1, 100),
//                 'tgl_lahir' => $faker->dateTimeBetween('-60 years', '-20 years')->format('Y-m-d'),
//                 'pekerjaan' => $faker->jobTitle,
//                 'alamat' => $faker->address,
//                 'no_rkm_medis' => $no_rkm_medis,
//                 'ruang' => $faker->word,
//                 'jk' => $faker->randomElement(['Laki-laki', 'Perempuan']),
//                 'tgl_registrasi' => $tgl_registrasi,
//                 'tanggalkeluar' => $tanggalkeluar,
//                 'kondisi_pulang' => $faker->randomElement(['Pulih', 'Meninggal']),
//             ]);
//         }
//     }
// }


//perhari
// <?php

// namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
// use Faker\Factory as Faker;
// class PatientSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run()
//     {
//         $faker = Faker::create();

//         for ($i = 1; $i <= 3; $i++) {
//             // Loop untuk memastikan no_rkm_medis unik
//             $no_rkm_medis = $faker->unique()->numerify('RM-######');
//             while (DB::table('patients')->where('no_rkm_medis', $no_rkm_medis)->exists()) {
//                 $no_rkm_medis = $faker->unique()->numerify('RM-######');
//             }

//             DB::table('patients')->insert([
//                 'nm_pasien' => $faker->name,
//                 'umurdaftar' => $faker->numberBetween(1, 100),
//                 'tgl_lahir' => $faker->dateTimeBetween('-60 years', '-20 years')->format('Y-m-d'),
//                 'pekerjaan' => $faker->jobTitle,
//                 'alamat' => $faker->address,
//                 'no_rkm_medis' => $no_rkm_medis,
//                 'ruang' => $faker->word,
//                 'jk' => $faker->randomElement(['Laki-laki', 'Perempuan']),
//                 'tgl_registrasi' => $faker->dateTimeBetween('2024-11-18', '2024-11-18')->format('Y-m-d'),
//                 'tanggalkeluar' => optional($faker->optional()->dateTimeBetween('2024-11-19', '2024-11-31'))->format('Y-m-d'),
//                 'kondisi_pulang' => $faker->randomElement(['Pulih', 'Meninggal']),
//             ]);
//         }
//     }
// }

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id('ID_Pasien');
            $table->string('nm_pasien');
            $table->integer('umurdaftar');
            $table->date('tgl_lahir');
            $table->string('pekerjaan');
            $table->string('alamat');
            $table->string('no_rkm_medis')->unique();
            $table->string('ruang');
            $table->enum('jk', ['Laki-laki', 'Perempuan']);
            $table->date('tgl_registrasi');
            $table->date('tanggalkeluar')->nullable();
            $table->enum('kondisi_pulang',['Pulih', 'Meninggal']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
}

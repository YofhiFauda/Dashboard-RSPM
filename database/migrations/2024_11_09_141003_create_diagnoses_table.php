<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiagnosesTable extends Migration
{
    public function up()
    {
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->unsignedBigInteger('ID_Pasien');  // Foreign key to patients table
            $table->string('keluhan_utama')->nullable();
            $table->text('jalanya_penyakit')->nullable();
            $table->text('pemeriksaan_penunjang')->nullable();
            $table->text('hasil_laborat')->nullable();
            $table->string('diagnosa_utama')->nullable();
            $table->string('diagnosa_sekunder')->nullable();
            $table->string('diagnosa_sekunder2')->nullable();
            $table->string('diagnosa_sekunder3')->nullable();
            $table->string('diagnosa_sekunder4')->nullable();
            $table->string('prosedur_utama')->nullable();
            $table->string('prosedur_sekunder')->nullable();
            $table->string('prosedur_sekunder2')->nullable();
            $table->string('prosedur_sekunder3')->nullable();
            $table->string('kondisi_pulang')->nullable();
            $table->string('obat_pulang')->nullable();
            $table->string('nm_dokter')->nullable();
            $table->timestamps();

            $table->foreign('ID_Pasien')->references('ID_Pasien')->on('patients')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('diagnoses');
    }
};

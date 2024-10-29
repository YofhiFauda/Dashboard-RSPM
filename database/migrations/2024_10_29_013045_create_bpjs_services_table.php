<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bpjs_services', function (Blueprint $table) {
            $table->id('ID_Pasien');
            $table->string('Nama_Pasien');
            $table->date('Tanggal_Lahir');
            $table->enum('Jenis_Kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('No_BPJS')->unique();
            $table->date('Tanggal_Pelayanan');
            $table->string('Jenis_Pelayanan');
            $table->string('Dokter');
            $table->text('Keterangan')->nullable();
            $table->string('file_jasper'); // Kolom untuk file jasper
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpjs_services');
    }
};

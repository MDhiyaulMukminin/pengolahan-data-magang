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
        Schema::create('alumni_magangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peserta_magang_id')->nullable();
            $table->string('nama_alumni')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('nama_sekolah')->nullable();
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('keterangan')->nullable(); 
            $table->timestamps();
        
            $table->foreign('peserta_magang_id')->references('id')->on('peserta_magangs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumni_magangs');
    }
};

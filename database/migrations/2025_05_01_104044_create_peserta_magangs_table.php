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
        Schema::create('peserta_magangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengajuan_id')->nullable();
            // $table->date('tgl_mulai');
            // $table->date('tgl_selesai');
            $table->enum('status', ['menunggu', 'aktif', 'selesai'])->default('menunggu');
            $table->timestamps();
        
            $table->foreign('pengajuan_id')->references('id')->on('pengajuans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_magangs');
    }
};

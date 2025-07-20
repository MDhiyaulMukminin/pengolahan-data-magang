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
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('surat_pengantar')->nullable(); 
            $table->enum('status', ['menunggu', 'ditolak', 'disetujui'])->default('menunggu');
            $table->string('surat_balasan')->nullable(); 
            $table->timestamps();
        
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
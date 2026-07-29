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
        Schema::create('izin_magangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('magang_id')->constrained('magangs')->onDelete('cascade');
            $table->date('tanggal_izin');
            $table->text('alasan');
            // Status: 'pending' (biru muda), 'disetujui' (kuning), 'ditolak' (merah)
            $table->string('status')->default('pending'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_magangs');
    }
};

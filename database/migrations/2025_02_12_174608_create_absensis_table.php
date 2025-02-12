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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('karyawan_id')->nullable()->default(null);
            $table->unsignedInteger('assigned_by')->nullable()->default(null);
            $table->string('tgl')->nullable()->default(null);
            $table->string('jam_masuk')->nullable()->default(null);
            $table->string('jam_keluar')->nullable()->default(null);
            $table->enum('status', ['hadir', 'alpha', 'cuti', 'sakit', 'izin'])->nullable()->default('alpha');
            $table->timestamps();

            $table->index('assigned_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};

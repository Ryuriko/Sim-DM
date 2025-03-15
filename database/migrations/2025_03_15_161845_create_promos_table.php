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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable()->default(null);
            $table->string('tgl_mulai', 100)->nullable()->default(null);
            $table->string('tgl_selesai', 100)->nullable()->default(null);
            $table->string('ket')->nullable()->default(null);
            $table->enum('status', ['aktif', 'tidak aktif'])->nullable()->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};

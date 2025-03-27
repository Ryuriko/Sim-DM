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
        Schema::create('kamars', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tipe_kamar_id')->nullable()->default(null);
            $table->string('no', 100)->nullable()->default(null);
            $table->string('lantai', 100)->nullable()->default(null);
            $table->string('foto', 100)->nullable()->default(null);
            $table->enum('status', ['tersedia', 'terisi', 'dibersihkan', 'tidak tersedia'])->nullable()->default('tersedia');
            $table->timestamps();

            $table->index('tipe_kamar_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};

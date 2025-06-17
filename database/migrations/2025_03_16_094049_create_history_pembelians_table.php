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
        Schema::create('history_pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->nullable()->default(null);
            $table->string('tgl')->nullable()->default(null);
            $table->string('harga_total')->nullable()->default(0);
            $table->string('ket')->nullable()->default(0);
            $table->enum('status', ['pending', 'selesai'])->nullable()->default('selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};

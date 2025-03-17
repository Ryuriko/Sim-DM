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
            $table->string('kode', 100)->nullable()->default(null);
            $table->unsignedInteger('supplier_id')->nullable()->default(null);
            $table->unsignedInteger('barang_id')->nullable()->default(null);
            $table->string('jumlah', 100)->nullable()->default(null);
            $table->string('harga_satuan', 100)->nullable()->default(null);
            $table->string('tgl', 100)->nullable()->default(null);
            $table->string('harga_total', 100)->nullable()->default(null);
            $table->enum('status', ['pending', 'selesai'])->nullable()->default('selesai');
            $table->timestamps();

            $table->index('supplier_id');
            $table->index('barang_id');
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

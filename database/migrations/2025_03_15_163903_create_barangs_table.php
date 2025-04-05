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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('kategori_id')->nullable()->default(null);
            $table->unsignedInteger('supplier_id')->nullable()->default(null);
            $table->string('kode', 100)->nullable()->default(null);
            $table->string('nama', 100)->nullable()->default(null);
            $table->string('merk', 100)->nullable()->default(null);
            $table->string('stok', 100)->nullable()->default(null);
            $table->string('satuan', 100)->nullable()->default(null);
            $table->string('harga_beli', 100)->nullable()->default(null);
            $table->string('harga_jual', 100)->nullable()->default(null);
            $table->string('lokasi', 100)->nullable()->default(null);
            $table->string('ket')->nullable()->default(null);
            $table->enum('status', ['aktif', 'tidak aktif'])->nullable()->default('aktif');
            $table->timestamps();

            $table->index('kategori_id');
            $table->index('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};

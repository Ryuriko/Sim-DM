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
        Schema::create('history_pembelian_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pembelian_id')->nullable()->default(null);
            $table->unsignedInteger('barang_id')->nullable()->default(null);
            $table->unsignedInteger('supplier_id')->nullable()->default(null);
            $table->string('jumlah', 100)->nullable()->default(null);
            $table->string('harga_satuan', 100)->nullable()->default(null);
            $table->string('subtotal', 100)->nullable()->default(null);
            $table->timestamps();

            $table->index('pembelian_id');
            $table->index('barang_id');
            $table->index('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_pembelian_details');
    }
};

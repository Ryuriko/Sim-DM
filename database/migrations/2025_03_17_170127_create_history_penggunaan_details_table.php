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
        Schema::create('history_penggunaan_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('penggunaan_id')->nullable()->default(null);
            $table->unsignedInteger('barang_id')->nullable()->default(null);
            $table->string('jumlah', 100)->nullable()->default(0);
            $table->timestamps();

            $table->index('penggunaan_id');
            $table->index('barang_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_penggunaan_details');
    }
};

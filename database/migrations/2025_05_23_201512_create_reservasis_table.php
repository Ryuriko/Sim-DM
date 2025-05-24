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
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable()->default(null);
            $table->unsignedInteger('transaksi_id')->nullable()->default(null);
            $table->string('person_qty')->nullable()->default(null);
            $table->string('checkin')->nullable()->default(null);
            $table->string('checkout')->nullable()->default(null);
            $table->string('ket')->nullable()->default(null);
            $table->timestamps();

            $table->index('user_id');
            $table->index('transaksi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};

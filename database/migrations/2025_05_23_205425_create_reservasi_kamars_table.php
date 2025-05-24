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
        Schema::create('reservasi_kamars', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('reservasi_id')->nullable()->default(null);
            $table->unsignedInteger('kamar_id')->nullable()->default(null);
            $table->string('date')->nullable()->default(null);
            $table->timestamps();

            $table->index('reservasi_id');
            $table->index('kamar_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasi_dates');
    }
};

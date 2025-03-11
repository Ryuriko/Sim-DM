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
        Schema::create('penggajians', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable()->default(null);
            $table->string('bulan')->nullable()->default(null);
            $table->string('gaji_pokok')->nullable()->default(null);
            $table->string('bonus')->nullable()->default(null);
            $table->string('potongan')->nullable()->default(null);
            $table->string('gaji_total')->nullable()->default(null);
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajians');
    }
};

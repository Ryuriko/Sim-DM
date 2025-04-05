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
        Schema::create('gym_pakets', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable()->default(null);
            $table->string('harga', 100)->nullable()->default(null);
            $table->string('ket', 100)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_pakets');
    }
};

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
        Schema::create('gym_trainers', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable()->default(null);
            $table->string('email', 100)->nullable()->default(null);
            $table->string('telepon', 100)->nullable()->default(null);
            $table->string('spealisasi', 100)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_trainers');
    }
};

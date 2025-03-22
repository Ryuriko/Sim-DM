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
        Schema::create('tipe_kamars', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable()->default(null);
            $table->string('kapasitas', 100)->nullable()->default(null);
            $table->string('harga', 100)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipe_kamars');
    }
};

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
        Schema::create('gym_members', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable()->default(null);
            $table->string('email', 100)->nullable()->default(null);
            $table->string('telp', 100)->nullable()->default(null);
            $table->string('alamat', 100)->nullable()->default(null);
            $table->string('tgl_lahir', 100)->nullable()->default(null);
            $table->string('tgl_gabung', 100)->nullable()->default(null);
            $table->string('status', 100)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_members');
    }
};

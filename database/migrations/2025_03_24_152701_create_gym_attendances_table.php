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
        Schema::create('gym_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('member_id')->nullable()->default(null);
            $table->string('tgl', 100)->nullable()->default(null);
            $table->string('waktu_masuk', 100)->nullable()->default(null);
            $table->string('waktu_keluar', 100)->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_attendaces');
    }
};

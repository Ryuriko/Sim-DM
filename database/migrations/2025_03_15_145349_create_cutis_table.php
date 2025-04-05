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
        Schema::create('cutis', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable()->default(null);
            $table->string('tgl_mulai', 100)->nullable()->default(null);
            $table->string('tgl_selesai', 100)->nullable()->default(null);
            $table->string('alasan')->nullable()->default(null);
            $table->enum('status', ['disetujui', 'menunggu', 'ditolak'])->nullable()->default('menunggu');
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutis');
    }
};

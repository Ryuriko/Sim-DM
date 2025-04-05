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
        Schema::create('gym_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('member_id')->nullable()->default(null);
            $table->unsignedInteger('paket_id')->nullable()->default(null);
            $table->string('tgl_mulai', 100)->nullable()->default(null);
            $table->string('tgl_selesai', 100)->nullable()->default(null);
            $table->timestamps();

            $table->index('member_id');
            $table->index('paket_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_subscriptions');
    }
};

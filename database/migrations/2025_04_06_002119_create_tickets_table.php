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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable()->default(null);
            $table->string('qty')->nullable()->default(0);
            $table->string('date', 100)->nullable()->default(null);
            $table->string('qrcode', 100)->nullable()->default(null);
            $table->string('orderId', 100)->nullable()->default(null);
            $table->string('paymentUrl')->nullable()->default(null);
            $table->string('reference')->nullable()->default(null);
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};

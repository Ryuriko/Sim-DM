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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('order_date')->nullable()->default(null);
            $table->string('orderId')->nullable()->default(null);
            $table->string('reference')->nullable()->default(null);
            $table->string('paymentUrl')->nullable()->default(null);
            $table->string('qrcode')->nullable()->default(null);
            $table->string('paid_at')->nullable()->default(null);
            $table->string('used_at')->nullable()->default(null);
            $table->string('tipe')->nullable()->default(null);
            $table->enum('status', ['unpaid', 'paid', 'ots'])->nullable()->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};

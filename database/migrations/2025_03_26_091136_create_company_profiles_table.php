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
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->nullable()->default(null);
            $table->string('tagline', 100)->nullable()->default(null);
            $table->string('alamat', 100)->nullable()->default(null);
            $table->string('email', 100)->nullable()->default(null);
            $table->string('website', 100)->nullable()->default(null);
            $table->string('logo', 100)->nullable()->default(null);
            $table->string('facebook', 100)->nullable()->default(null);
            $table->string('instagram', 100)->nullable()->default(null);
            $table->string('tiktok', 100)->nullable()->default(null);
            $table->string('linkedin', 100)->nullable()->default(null);
            $table->string('whatsapp1', 100)->nullable()->default(null);
            $table->string('whatsapp2', 100)->nullable()->default(null);
            $table->string('whatsapp3', 100)->nullable()->default(null);
            $table->string('ket')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};

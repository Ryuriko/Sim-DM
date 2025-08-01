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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('role_id')->nullable()->default(null);
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('nik')->unique()->nullable()->default(null);
            $table->string('rek_vendor')->unique()->nullable()->default(null);
            $table->string('rek_no')->unique()->nullable()->default(null);
            $table->string('rek_nama')->unique()->nullable()->default(null);
            $table->string('ktp')->nullable()->default(null);
            $table->string('foto')->nullable()->default(null);
            $table->string('cv')->nullable()->default(null);
            $table->string('sk')->nullable()->default(null);
            $table->string('bpjs')->nullable()->default(null);
            $table->string('surat_kontrak')->nullable()->default(null);
            $table->string('sertifikat')->nullable()->default(null);
            $table->string('date_of_entry')->nullable()->default(null);
            $table->enum('tipe', ['tetap', 'kontrak', 'vendor'])->nullable()->default('kontrak');
            $table->enum('status', ['aktif', 'cuti', 'keluar'])->nullable()->default('aktif');
            $table->rememberToken();
            $table->timestamps();
            
            $table->index('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

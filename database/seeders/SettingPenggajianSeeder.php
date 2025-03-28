<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SettingPenggajian;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingPenggajianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SettingPenggajian::create([
            'potongan_alpha' => 0,
            'potongan_cuti' => 0,
        ]);
    }
}

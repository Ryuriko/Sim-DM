<?php

namespace Database\Seeders;

use App\Models\Penggajian;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenggajianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bulan = now()->locale('id')->translatedFormat('F');
        $tahun = now()->year;

        $users = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'super_admin');
        })
        ->whereDoesntHave('penggajians', function($query) use($bulan){
            $query->where('bulan', $bulan);
        })->get();

        foreach ($users as $user) {
            Penggajian::create([
                'user_id' => $user->id,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'gaji_pokok' => $user->role->salary,
                'gaji_total' => $user->role->salary,
            ]);
        }
    }
}

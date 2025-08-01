<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $date = Carbon::now()->toDateString();

        $karyawans = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'super_admin');
        })
        ->whereDoesntHave('absensis', function($query) use($date){
            $query->where('tgl', $date);
        })->get();

        foreach ($karyawans as $karyawan) {
            Absensi::create([
                    'user_id' => $karyawan->id,
                    'tgl' => $date,
            ]);
        }
    }
}

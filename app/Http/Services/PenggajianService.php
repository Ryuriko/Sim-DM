<?php

namespace App\Http\Services;

use App\Models\Penggajian;
use App\Models\SettingPenggajian;
use App\Models\User;

class PenggajianService
{
    public static function summarize($tahun, $bulan)
    {
        try {
            $users = User::whereDoesntHave('role', function($query) {
                $query->where('name', 'sistem');
            })
            ->whereHas('penggajians', function ($query) use($tahun, $bulan) {
                $query->where('tahun', $tahun)->where('bulan', $bulan);
            })->get();

            $settingPenggajian = SettingPenggajian::first();
            $settingAlpha = $settingPenggajian['potongan_alpha'];
            $settingCuti = $settingPenggajian['potongan_cuti'];
            $ket = '';


            foreach ($users as $user) {
                $data = $user->penggajians->first();
                $gajiPokok = $user->role->salary;
                $alpha = count($user->absensis->where('status', 'alpha'));
                $cuti = count($user->absensis->where('status', 'cuti'));
                $potongan = $alpha * $settingAlpha + $cuti * $settingCuti;
                if($alpha != 0 && $settingAlpha != 0) {
                    $ket = 'Potongan karena alpha sebanyak ' . $alpha;
                }
                if($cuti != 0 && $settingCuti != 0) {
                    if($alpha != 0) {
                        $ket = '& Potongan karena cuti sebanyak ' . $cuti;
                    } else {
                        $ket = 'Potongan karena cuti sebanyak ' . $cuti;
                    }
                }
                if($alpha == 0 && $cuti == 0) {
                    $ket = '';
                }


                $gajiTotal = $gajiPokok + $data['bonus'] - $potongan;
                
                Penggajian::find($data['id'])->update([
                    'gaji_pokok' => $gajiPokok,
                    'potongan' => $potongan,
                    'gaji_total' => $gajiTotal,
                    'ket' => $ket
                ]);
            }

            return [
                'status' => true
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => $th->getMessage()
            ];
        }
    }
}

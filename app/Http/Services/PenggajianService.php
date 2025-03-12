<?php

namespace App\Http\Services;

use App\Models\Penggajian;
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

            foreach ($users as $user) {
                $data = $user->penggajians->first();
                $gajiTotal = $data['gaji_pokok'] + $data['bonus'] - $data['potongan'];
                
                Penggajian::find($data['id'])->update([
                    'gaji_total' => $gajiTotal
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

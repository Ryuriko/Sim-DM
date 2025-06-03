<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use App\Models\GymSubscription;
use App\Models\Parkir;
use App\Models\Reservasi;
use App\Models\Ticket;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // TiketController.php
    public function verifikasi(Request $request)
    {
        $qr = $request->input('qr');
        $qrcode = explode(' ', $qr);

        $transaksi = Transaksi::where('orderId', $qrcode[2])->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Tiket tidak valid!'], 404);
        }

        if($qrcode[0] == 'gym' || $qrcode[0] == 'gym-ots') {
            
            $gym = GymSubscription::where('transaksi_id', $transaksi->id)->first();
            $gymStart = Carbon::parse($gym->tgl_mulai);
            $gymEnd = Carbon::parse($gym->tgl_selesai);
            $today= Carbon::today();
            if($today->between($gymStart, $gymEnd)) {
                return response()->json(['message' => 'Membership Aktif']);
            } else {
                return response()->json(['message' => 'Membership tidak berlaku!']);
            }
        }    

        if ($transaksi->used_at != null) {
            return response()->json(['message' => 'Tiket sudah digunakan!']);
        }
        
        if($qrcode[0] == 'ticket' || $qrcode[0] == 'ticket-ots') {
            $waterboom = Ticket::where('transaksi_id', $transaksi->id)->first();
            $dateWaterboom = Carbon::parse($waterboom['date']);
            if(!$dateWaterboom->isSameDay(Carbon::today())) {
                return response()->json(['message' => 'Tiket waterboom bukan untuk hari ini!']);
            }
        }

        if($qrcode[0] == 'reservasi' || $qrcode[0] == 'reservasi-ots') {
            
            $reservasi = Reservasi::where('transaksi_id', $transaksi->id)->first();
            $reservasiStart = Carbon::parse($reservasi->tgl_mulai);
            $reservasiEnd = Carbon::parse($reservasi->tgl_selesai);
            $today= Carbon::today();
            if(!$today->between($reservasiStart, $reservasiEnd)) {
                return response()->json(['message' => 'Bukan tanggal reservasi dipesan']);
            }
        }

        if($qrcode[0] == 'parkir' || $qrcode[0] == 'parkir-ots') {
            $waterboom = Parkir::where('transaksi_id', $transaksi->id)->first();
            $dateWaterboom = Carbon::parse($waterboom['date']);
            if(!$dateWaterboom->isSameDay(Carbon::today())) {
                return response()->json(['message' => 'Tiket parkir bukan untuk hari ini!']);
            }
        }

        $transaksi->update(['used_at' => now()]);

        return response()->json(['message' => 'Tiket berhasil digunakan.']);
    }

}

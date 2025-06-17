<?php

namespace App\Models;

use App\Models\User;
use App\Models\GymMember;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GymSubscription extends Model
{
    use HasFactory;

    /**
     * Get the member that owns the GymSubscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

      /**
     * Get the kamar that owns the Reservasi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'id');
    }

    // /**
    //  * Get the paket that owns the GymSubscription
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    //  */
    // public function paket()
    // {
    //     return $this->belongsTo(GymPaket::class, 'paket_id', 'id');
    // }
}

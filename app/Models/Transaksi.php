<?php

namespace App\Models;

use App\Models\Ticket;
use App\Models\Reservasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    /**
     * Get all of the tickets for the Transaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'transaksi_id', 'id');
    }

    /**
     * Get all of the tickets for the Transaksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'transaksi_id', 'id');
    }
}

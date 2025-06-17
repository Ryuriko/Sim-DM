<?php

namespace App\Models;

use App\Models\User;
use App\Models\Kamar;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ManajemenReservasi extends Model
{
    protected $table = 'reservasis';

    use HasFactory;

     /**
     * Get the kamar that owns the Reservasi
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


    /**
     * The kamars that belong to the Reservasi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function kamars()
    {
        return $this->belongsToMany(Kamar::class, 'reservasi_kamars', 'reservasi_id', 'kamar_id')
            ->withPivot('date')
            ->withTimestamps();
    }   
}

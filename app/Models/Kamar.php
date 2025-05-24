<?php

namespace App\Models;

use App\Models\Foto;
use App\Models\Reservasi;
use App\Models\TipeKamar;
use App\Models\ReservasiDate;
use App\Models\FasilitasKamar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kamar extends Model
{
    use HasFactory;

    /**
     * Get the tipe that owns the Kamar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipe()
    {
        return $this->belongsTo(TipeKamar::class, 'tipe_kamar_id', 'id');
    }

    /**
     * The fasilitas that belong to the Kamar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fasilitas()
    {
        return $this->belongsToMany(FasilitasKamar::class, 'kamar_fasilitas', 'kamar_id', 'fasilitas_kamar_id');
    }

    /**
     * The fotos that belong to the Kamar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fotos()
    {
        return $this->belongsToMany(Foto::class, 'kamar_foto', 'kamar_id', 'foto_id');
    }

    /**
     * The reservasis that belong to the Kamar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reservasis()
    {
        return $this->belongsToMany(Reservasi::class, 'reservasi_kamars', 'reservasi_id', 'kamar_id')
            ->withPivot('date')
            ->withTimestamps();
    }
}

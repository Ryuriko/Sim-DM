<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        return $this->belongsTo(TipeKamar::class, 'kamar_id', 'id');
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
}

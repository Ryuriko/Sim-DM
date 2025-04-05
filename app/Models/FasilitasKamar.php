<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FasilitasKamar extends Model
{
    use HasFactory;

    /**
     * The kamars that belong to the FasilitasKamar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function kamars()
    {
        return $this->belongsToMany(Kamar::class, 'kamar_fasilitas_id', 'kamar_id', 'fasilitas_kamar_id');
    }
}

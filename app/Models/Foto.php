<?php

namespace App\Models;

use App\Models\Kamar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Foto extends Model
{
    use HasFactory;

    /**
     * The kamars that belong to the Foto
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function kamars()
    {
        return $this->belongsToMany(Kamar::class, 'kamar_foto', 'kamar_id', 'foto_id');
    }
}

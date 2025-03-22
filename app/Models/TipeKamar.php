<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipeKamar extends Model
{
    use HasFactory;
    
    /**
     * Get all of the kamars for the TipeKamar
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'tipe_id', 'id');
    }
}

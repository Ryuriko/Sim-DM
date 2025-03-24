<?php

namespace App\Models;

use App\Models\GymClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GymTrainer extends Model
{
    use HasFactory;

    /**
        * The kelass that belong to the GymTrainer
        *
        * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
        */
    public function kelass()
    {
        return $this->belongsToMany(GymClass::class, 'trainer_class', 'trainer_id', 'class_id');
    }
}

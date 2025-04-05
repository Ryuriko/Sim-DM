<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GymClass extends Model
{
    use HasFactory;

    /**
     * The pelatihs that belong to the GymClass
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pelatihs()
    {
        return $this->belongsToMany(GymTrainer::class, 'trainer_class', 'trainer_id', 'class_id');
    }
}

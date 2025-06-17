<?php

namespace App\Models;

use App\Models\GymSubscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GymPaket extends Model
{
    use HasFactory;

    // /**
    //  * Get all of the subscriptions for the GymPaket
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function subscriptions()
    // {
    //     return $this->hasMany(GymSubscription::class, 'paket_id', 'id');
    // }
}

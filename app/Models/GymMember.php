<?php

namespace App\Models;

use App\Models\GymAttendace;
use App\Models\GymSubscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GymMember extends Model
{
    use HasFactory;

    /**
     * Get all of the subscriptions for the GymMember
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(GymSubscription::class, 'member_id', 'id');
    }

    /**
     * Get all of the GymAttendance for the GymMember
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kehadirans()
    {
        return $this->hasMany(GymAttendace::class, 'member_id', 'id');
    }
}

<?php

namespace App\Models;

use App\Models\GymMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GymSubscription extends Model
{
    use HasFactory;

    /**
     * Get the member that owns the GymSubscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(GymMember::class, 'member_id', 'id');
    }

    /**
     * Get the paket that owns the GymSubscription
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paket()
    {
        return $this->belongsTo(GymPaket::class, 'paket_id', 'id');
    }
}

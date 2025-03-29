<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenUser extends Model
{
    use HasFactory;

    protected $table = 'absensis';

    /**
     * Get the User that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the karyawan that owns the Karyawan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assigned_by()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }
}

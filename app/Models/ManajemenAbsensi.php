<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManajemenAbsensi extends Model
{
    protected $table = 'absensis';

    use HasFactory;

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

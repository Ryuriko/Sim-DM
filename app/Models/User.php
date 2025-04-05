<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Cuti;
use App\Models\Absensi;
use App\Models\Penggajian;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get all of the absensis for the Karyawan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'user_id', 'id');
    }

    /**
     * Get all of the absensis for the Karyawan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assigned_absensis()
    {
        return $this->hasMany(Absensi::class, 'assigned_by', 'id');
    }

    /**
     * Get all of the penggajians for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function penggajians()
    {
        return $this->hasMany(Penggajian::class, 'user_id', 'id');
    }

    /**
     * Get all of the cutis for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cutis()
    {
        return $this->hasMany(Cuti::class, 'user_id', 'id');
    }
}

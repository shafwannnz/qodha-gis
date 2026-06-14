<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi: Mitra yang DIBUAT oleh admin ini.
     * (admins.id -> mitras.created_by)
     */
    public function mitrasCreated(): HasMany
    {
        return $this->hasMany(Mitra::class, 'created_by');
    }

    /**
     * Relasi: Mitra yang TERAKHIR DIUBAH oleh admin ini.
     * (admins.id -> mitras.updated_by)
     */
    public function mitrasUpdated(): HasMany
    {
        return $this->hasMany(Mitra::class, 'updated_by');
    }
}

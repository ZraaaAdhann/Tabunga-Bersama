<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'phone_number',
        'birth_date',
        'avatar',
        'password',
        'google_id',
        'registration_step',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }

    public function projects(): BelongsToMany
    {
        return $table = $this->belongsToMany(Project::class)->withPivot('role')->withTimestamps();
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(SavingsDeposit::class);
    }
}
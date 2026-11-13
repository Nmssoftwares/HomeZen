<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Role Constants for easy reference across the app
    const ROLE_SUPER_ADMIN   = 1;
    const ROLE_COMPANY_ADMIN = 2;
    const ROLE_USER          = 3;

    protected $fillable = [
        'company_id',
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Relationship: A user belongs to a Company (Household).
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

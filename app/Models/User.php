<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    const UPDATED_AT = 'lastlogin';
    const CREATED_AT = 'created_date';

    protected $table = 'userlogin';
    protected $fillable = [
        'username',
        'new_password',
        'image',
        'role',
        'devisi',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'new_password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'new_password' => 'hashed',
        ];
    }

    /**
     * Get the role name in Indonesian
     */
    public function getRoleNameAttribute()
    {
        $roles = [
            'admin' => 'Admin',
            'kepala_dinas' => 'Kepala Dinas',
            'kepala_bidang' => 'Kepala Bidang',
            'ketua_tim' => 'Ketua Tim / Kapala Seksi',
            'user' => 'User',
        ];

        return $roles[$this->role] ?? 'User';
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is kepala dinas
     */
    public function isKepalaDinas()
    {
        return $this->hasRole('kepala_dinas');
    }

    /**
     * Check if user is kepala bidang
     */
    public function isKepalaBidang()
    {
        return $this->hasRole('kepala_bidang');
    }

    /**
     * Check if user is ketua tim
     */
    public function isKetuaTim()
    {
        return $this->hasRole('ketua_tim');
    }

    /**
     * Check if user is regular user
     */
    public function isUser()
    {
        return $this->hasRole('user');
    }

     public function getAuthPassword()
    {
        return $this->new_password; // Assuming your custom column is 'user_pass'
    }
}

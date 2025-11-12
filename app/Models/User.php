<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'firebase_uid',
        'name',
        'email',
        'password',
        'age',
        'current_lat',
        'current_lng',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
    'password' => 'hashed',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'user_id');
    }

    public function locationHistories()
    {
        return $this->hasMany(LocationHistory::class);
    }
}

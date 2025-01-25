<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

final class User extends Authenticatable
{
    /**
     * @property int $id
     * @property string $email
     * @property string $password
     * @property string $role
     */
    use HasApiTokens;

    use HasFactory;
    use Notifiable;

    public $timestamps = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];
}

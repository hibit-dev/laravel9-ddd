<?php

namespace App\Infrastructure\Laravel\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserModel extends Authenticatable
{
    use HasUuids;

    protected $fillable = [
        'name',
        'email',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $table = 'hibit_users';
}

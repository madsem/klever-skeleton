<?php

namespace Klever\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{

    use SoftDeletes;

    protected $table = 'users';

    protected $guarded = ['id'];

    /**
     * Hash password when new user is generated
     * or password is updated
     *
     * @param  string $password
     * @return void
     */
    function setPasswordAttribute($password)
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_DEFAULT);
    }
}
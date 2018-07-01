<?php

namespace Klever\Models;

class User extends Model
{

    protected $table = 'users';

    protected $guarded = ['id'];

    public function setPassword($password)
    {
        $this->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'enabled',
        'roles'
    ];

    protected $attributes = [
        'first_name' => '',
        'last_name' => '',
        'username' => '',
        'password' => '',
        'enabled' => 1,
        'roles' => '',
    ];

    protected $hidden = ['password'];


    public function setPassword($password)
    {
        $this->password = Hash::make($password);
    }

    public function verifyPassword($password)
    {
        if (Hash::check($password, $this->password) > 0) {
            return true;
        }
        return false;
    }

    public function testRole($role)
    {
        return \array_search($role, \explode(',', $this->roles));
    }
}

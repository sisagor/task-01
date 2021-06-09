<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    protected $fillable = [
        'name',
        'phone',
        'email',
        'dob',
        'gender',
        'address',
    ];

    protected function user()
    {
        return $this->hasOne(User::class);
    }

}

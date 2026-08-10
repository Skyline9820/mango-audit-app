<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de ejemplo. Así se ve un modelo con Eloquent:
 * User::all(), User::find($id), User::where('email', $email)->first(), etc.
 */
class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}

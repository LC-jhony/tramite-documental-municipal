<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'dni',
        'email',
        'phone',
    ];

    protected $casts = [
        'dni' => 'integer',
    ];
}

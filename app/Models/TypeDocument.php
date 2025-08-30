<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeDocument extends Model
{
    /** @use HasFactory<\Database\Factories\TypeDocumentFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'status',
        'response_time_days'
    ];
    protected $casts = [
        'status' => 'boolean',
        'response_time_days' => 'integer',
    ];
}

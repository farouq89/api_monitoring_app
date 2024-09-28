<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiEndpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'method',
        'description',
        'headers',
        'payload_format',
        'parameters',
        'status',
    ];

    // Cast 'headers' and 'parameters' to arrays
    protected $casts = [
        'headers' => 'array',
        'parameters' => 'array',
        'status' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validationmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'email',
        'state'
    ];

    
}

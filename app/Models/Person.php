<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';
    protected $with = [
        'user',
        'scout'
    ];

    public function user(){
        return $this->hasOne(User::class);
    }
    public function scout(){
        return $this->hasOne(Scout::class);
    }
}

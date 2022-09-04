<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'persons';
    protected $fillable = [
        'name',
        'last_name',
        'born_date',
        'phone',
        'gender',
        'image',
        'nacionality',
        'type_blood',
        'dni'
    ];
    protected $with = [
        'user',
        'scout'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function scout()
    {
        return $this->hasOne(Scout::class);
    }
    public function directing()
    {
        return $this->hasOne(Directing::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'addres',
        'image',
    ];

    public function directings(){
        return $this->hasMany(Directing::class);
    }

    public function scouts(){
        return $this->hasMany(Scout::class);
    }
}

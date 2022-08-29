<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directing extends Model
{
    use HasFactory;
    protected $fillable = [
        'unit_id',
        'user_id',
        'person_id'
    ];

    public function user(){
        return $this->hasOne(User::class);
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }
    
    public function person(){
        return $this->belongsTo(Person::class);
    }

    public function roles(){
        return $this->belongsToMany(Role::class);
    }
}

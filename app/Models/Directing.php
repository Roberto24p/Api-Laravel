<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directing extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'last_name',
        'age',
        'user_id',
        'group_id',
        'unit_id',
    ];

    public function user(){
        return $this->hasOne(User::class);
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function roles(){
        return $this->belongsToMany(Role::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scout extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'last_name',
        'group_id',
        'unit_id',
        'born_date',
        'user_id',
        'phone',
        'gender',
        'email',
        'image',
        'nacionality',
        'user_id',
        'type_blood',
        'dni'
    ];


    public function insignias(){
        return $this->belongsToMany(Insignia::class);
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function unit(){
        return $this->belongsTo(Unit::class);
    }
}

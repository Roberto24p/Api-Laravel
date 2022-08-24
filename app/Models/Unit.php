<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'group_id',
        'image',
        'state'
    ];
    protected $with = [
        'group'
    ];
    public function directings(){
        return $this->hasMany(Directing::class);
    }

    public function scouts(){
        return $this->hasMany(Scout::class);
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function teams(){
        return $this->hasMany(Team::class);
    }


}

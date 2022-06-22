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
        'img_url',
        'association_id',
    ];

    public function association(){
        return $this->belongsTo(Association::class);
    }

    public function directings(){
        return $this->hasMany(Directing::class);
    }

    public function scouts(){
        return $this->hasMany(Scout::class);
    }
}

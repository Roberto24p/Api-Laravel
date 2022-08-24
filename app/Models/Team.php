<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Team extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'unit_id',
        'state'
    ];
    public function unit(){
        return $this->belongsTo(Unit::class);
    }

    public function scouts(){
        return $this->belongsToMany(Scout::class);
    }

    public static function teamByUnit($unit){
       
    }
}
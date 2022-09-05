<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    // public function group(){
    //     return $this->belongsTo(Group::class);
    // }
    
    public function person(){
        return $this->belongsTo(Person::class);
    }

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function unit(){
        return $this->belongsTo(Unit::class);
    }

    public static function directingsByGroup($groupId){
        $result = DB::table('directings as d')
            ->join('persons as p', 'p.id', '=', 'd.person_id')
            ->join('units as u', 'u.id', '=', 'd.unit_id')
            ->join('groups as g', 'g.id', '=', 'u.group_id')
            ->where('g.id', $groupId)
            ->get();
        return $result;
    }

    public static function getAllDirecting(){
        $result = DB::table('directings as d')
            ->join('persons as p', 'p.id', '=', 'd.person_id')
            ->join('users as us', 'us.person_id', '=','p.id')
            ->join('units as u', 'u.id', '=', 'd.unit_id')
            ->join('groups as g', 'g.id', '=', 'u.group_id')
            ->select('p.name', 'p.last_name', 'g.name as group_name', 'p.born_date', 'p.dni', 'us.id', 'us.state')
            ->get();
        return $result;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Scout extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'type'
    ];




    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public static function scoutsInscritosByGroup($group)
    {
        $result = DB::table('inscription_scout as is')
            ->join('periods as p', 'p.id', '=', 'is.period_id')
            ->join('scouts as s', 's.id', '=', 'is.scout_id')
            ->join('groups as g', 'g.id', '=', 'is.group_id')
            ->join('persons as per', 'per.id', '=', 's.person_id')
            ->where('g.state', 'A')
            ->where('p.state', 'Activo')
            ->where('is.state_inscription', 'confirmado')
            ->where('g.id', $group)
            ->select('g.name as group_name', 'per.name', 'per.born_date', 's.type', 's.id', 'per.last_name')
            ->get();
        return $result;
    }

    public static function scoutsByTeam($unit){
        $result = DB::table('scout_team as st')
            ->join('scouts as s', 's.id', '=', 'st.scout_id')
            ->join('persons as p', 'p.id', '=', 's.person_id')
            ->join('teams as t', 't.id', '=', 'st.team_id')
            ->join('units as u', 'u.id', '=', 't.unit_id')
            ->where('u.id', $unit)
            ->select('p.name','p.last_name', 't.id as team_id', 'u.id as unit_id', 't.name as team_name', 's.id as scout_id')
            ->get();

        return $result;
    }

    public static function scoutsByUnit($unit){
        $result = DB::table('scouts as s')
            ->join('scout_team as st', 'st.scout_id', '=', 's.id')
            ->join('teams as t', 't.id', '=', 'st.team_id')
            ->join('units as u', 'u.id', '=', 't.unit_id')
            ->join('persons as p', 'p.id', '=', 's.person_id')
            ->where('u.id', $unit)
            ->select( 'p.name', 'p.born_date', 's.type', 's.id', 'p.last_name')
            ->get();
        return $result;
    }

  
}

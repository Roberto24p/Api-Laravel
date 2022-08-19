<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_start',
        'date_end',
        'description'
    ];

    public static function register($scout, $group)
    {
        $period = DB::select(DB::raw('SELECT * FROM periods WHERE NOW() between periods.date_start and periods.date_end '));
        $data = DB::table('inscription_scout')
            ->updateOrInsert(
                [
                    'period_id' => $period[0]->id,
                    'scout_id' => $scout,
                ],
                [
                    'period_id' => $period[0]->id,
                    'group_id' => $group,
                    'scout_id' => $scout,
                    'observations' => '',
                    'state_inscription' => 'espera'
                ]
            );
        return $data;
    }

    public static function scoutGroupInscription($scoutId)
    {
        $now =  DB::select(DB::raw('SELECT NOW() as date'));
        $now = $now[0]->date;
        $group = DB::select(DB::raw('SELECT * FROM inscription_scout INNER JOIN groups on groups.id = inscription_scout.group_id inner join periods on periods.id = inscription_scout.period_id where NOW() between periods.date_start and periods.date_end and inscription_scout.scout_id = ' . $scoutId));
        if ($group == null) {
            return 0;
        } else {
            return $group[0];
        }
    }

    public static function allInscriptions()
    {
        $inscriptions = DB::table('inscription_scout')
            ->join('scouts', 'scouts.id', '=', 'inscription_scout.scout_id')
            ->join('persons', 'persons.id', '=', 'scouts.person_id')
            ->join('groups', 'groups.id', '=', 'inscription_scout.group_id')
            ->select('groups.name as group', 'inscription_scout.state_inscription as state_inscription', 'persons.name as name', 'scouts.type as type', 'inscription_scout.id', 'scouts.image_permissions', 'scouts.image_pay')->get();
        return $inscriptions;
    }

    public static function putScoutInscription($state, $observations, $id)
    {
        if ($observations == '')
            $observations = 'NO HAY OBSERVACIONES';
        $validate = DB::table('inscription_scout')
            ->where('id', $id)
            ->update([
                'observations' => $observations,
                'state_inscription' => $state
            ]);
        return $validate;
    }
}

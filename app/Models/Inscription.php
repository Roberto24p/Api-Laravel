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

    public static function register($scout, $group, $permission, $photo, $pay)
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
                    'state_inscription' => 'espera',
                    'image_pay' => $pay,
                    'image_permission' => $permission,
                    'image_photo' => $photo
                ]
            );
        return $data;
    }

    public static function scoutGroupInscription($scoutId)
    {
        $now =  DB::select(DB::raw('SELECT NOW() as date'));
        $now = $now[0]->date;
        // $group = DB::select(DB::raw('SELECT * FROM inscription_scout INNER JOIN groups on groups.id = inscription_scout.group_id inner join periods on periods.id = inscription_scout.period_id where NOW() between periods.date_start and periods.date_end and inscription_scout.scout_id = ' . $scoutId));
        $group = DB::select(DB::raw('SELECT * FROM inscription_scout INNER JOIN groups on groups.id = inscription_scout.group_id inner join periods on periods.id = inscription_scout.period_id where periods.state = "Activo" and inscription_scout.scout_id = ' . $scoutId));
        $group = DB::table('inscription_scout')
            ->join('groups as g', 'g.id', '=', 'inscription_scout.group_id')
            ->join('periods as p', 'p.id', '=', 'inscription_scout.period_id')
            ->where('p.state', 'Activo')->where('inscription_scout.scout_id', $scoutId)->first();
        if ($group == null) {
            return 0;
        } else {
            return $group;
        }
    }

    public static function allInscriptions()
    {
        $inscriptions = DB::table('inscription_scout')
            ->join('scouts', 'scouts.id', '=', 'inscription_scout.scout_id')
            ->join('persons', 'persons.id', '=', 'scouts.person_id')
            ->join('groups', 'groups.id', '=', 'inscription_scout.group_id')
            ->select('inscription_scout.image_photo', 'inscription_scout.image_permission', 'inscription_scout.image_pay', 'groups.name as group', 'inscription_scout.state_inscription as state_inscription', 'persons.name as name', 'scouts.type as type', 'inscription_scout.id', 'persons.last_name', 'persons.dni', 'persons.type_blood', 'persons.phone', 'persons.gender', 'persons.born_date')->get();
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


    public static function getGroupByScoutInscription($scout_id)
    {
        $result = DB::table('inscription_scout as ins')
            ->join('periods as p', 'p.id', '=', 'ins.period_id')
            ->where('ins.scout_id', $scout_id)
            ->where('p.state', 'Activo')->first();
        return $result;
    }

    public static function cantidadInscritosxgrupos($perioId)
    {
        $result = DB::table('inscription_scout')
            ->join('groups', 'groups.id', '=', 'inscription_scout.group_id')
            ->where('inscription_scout.state_inscription', 'confirmado')
            ->where('inscription_scout.period_id', $perioId)
            ->orderBy('name')
            ->get();
        return $result;
    }

    public static function inscriptionsByUnit($group)
    {
        $result = DB::table('inscription_scout as ins')
            ->join('scouts as s', 's.id', '=', 'ins.scout_id')
            ->where('ins.group_id', $group)
            ->orderBy('s.type')->get();
        return $result;
    }
}

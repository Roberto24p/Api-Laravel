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

    public static function register($scout, $group){
        $data = DB::table('inscription_scout')
            ->insert([
                'period_id' => 1,
                'group_id' => $group,
                'scout_id' => $scout
            ]);
        return $data;
    }

    public static function scoutGroupInscription($scoutId){
        $group = DB::table('inscription_scout')
            ->join('groups', 'groups.id', '=', 'inscription_scout.group_id')->where('inscription_scout.scout_id', $scoutId)->first(); 
        return $group;
    }
}

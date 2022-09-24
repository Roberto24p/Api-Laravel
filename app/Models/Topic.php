<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Topic extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'recognition_id'
    ];

    public function scouts()
    {
        return $this->belongsToMany(Scout::class);
    }
    public function recognition()
    {
        return $this->belongsTo(Recognition::class);
    }

    public static function topicsScout($scoutId)
    {
        $data = DB::table('scout_topic as st')
            ->join('topics as t', 't.id', '=', 'st.topic_id')
            ->where('scout_id', $scoutId)
            ->select('st.id as id', 'st.scout_id', 'st.topic_id', 't.recognition_id')
            ->get();
        return $data;
    }

    public static function attachScoutTeam($scoutId, $teamId, $topicId)
    {
        $db = DB::table('scout_topic')->where('topic_id', $topicId)->where('scout_id', $scoutId)->where('team_id', $teamId)->delete();
        if ($db == 0) {
            $db = DB::table('scout_topic')
                ->insert([
                    'topic_id' => $topicId,
                    'team_id' => $teamId,
                    'scout_id' => $scoutId
                ]);
        }

        return $db;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AdvancePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tittle',
        'Description',
        'state'
    ];

    public function recognitions()
    {
        return $this->hasMany(Recognition::class);
    }

    public static function tScout($id)
    {
        $topic = DB::select(DB::raw('SELECT * FROM scout_topic INNER JOIN scouts on scouts.id = scout_topic.scout_id inner join topics on topics.id = scout_topic.topic_id inner join recognitions on recognitions.id = topics.recognition_id inner join advance_plans on advance_plans.id = recognitions.advance_plan_id where scouts.type = advance_plans.type and scouts.id = ' . $id));
        return $topic;
    }
}

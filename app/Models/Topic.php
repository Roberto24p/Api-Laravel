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

    public static function topicsScout($scoutId){
        $data = DB::table('scout_topic')->where('scout_id', $scoutId)->get();
        return $data;
    }
}

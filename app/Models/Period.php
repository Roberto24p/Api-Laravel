<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_start',
        'date_end',
        'description',
        'state',
        'price'
    ];

    public static function periodNow(){
        $result = DB::select(DB::raw('SELECT * FROM periods WHERE NOW() between periods.date_start and periods.date_end '));
        return $result[0];
    }
}

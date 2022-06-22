<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scout extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'group_id',
        'unit_id',
        'age',
        'user_id',
    ];
    public function insignias(){
        return $this->belongsToMany(Insignia::class);
    }
}

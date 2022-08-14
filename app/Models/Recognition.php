<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recognition extends Model
{
    use HasFactory;

    protected $with = [
        'topics'
    ];
    protected $fillable = [
        'name',
        'advance_plan_id'
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}

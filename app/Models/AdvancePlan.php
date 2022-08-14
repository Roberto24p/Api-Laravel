<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvancePlan extends Model
{
    use HasFactory;
 
    public function recognitions(){
        return $this->hasMany(Recognition::class);
    }


}

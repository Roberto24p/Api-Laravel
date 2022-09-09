<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Range;
use App\Models\AdvancePlan;
use Illuminate\Support\Facades\Auth;

class RangeController extends Controller
{
    public function index()
    {
        $range = Range::all();
        return response()->json([
            'success' => 1,
            'ranges' => $range
        ]);
    }

    public function store(Request $request)
    {
        $range = Range::create([
            'name' => $request->name,
            'max' => $request->max,
            'min' => $request->min
        ]);
        $advancePlan = AdvancePlan::create([
            'tittle' => 'Plan de adelanto de '. $request->name,
            'Description' => '',
            'type' => $request->name
        ]);
        return response()->json([
            'success' => 1,
            'range' => $range
        ]);
    }
    
}

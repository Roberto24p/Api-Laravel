<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Range;
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

        return response()->json([
            'success' => 1,
            'range' => $range
        ]);
    }
    
}

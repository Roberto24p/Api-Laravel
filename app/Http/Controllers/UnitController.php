<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function store(Request $request){
        $unit = Unit::create($request->all());

        return response()->json([
            'data' => $unit,
            'message' => 'Unidad creada exitosamente'
        ]);
    }

    public function index(){
        $units = Unit::all();
        return response()->json(
            $units
        );
    }

    public function show($id){
        $unit = Unit::find($id);
        return response()->json(
            $unit
        );
    }

    public function destroy($id){
        $unit = Unit::find($id);
        $unit->delete();

        return response()->json(
            ['message' => 'Unidad eliminada']
        );
    }

    public function showByGroup($id){
        $unitsByGroup = Unit::where('group_id', $id)->get();
        return $unitsByGroup;
 
    }
}

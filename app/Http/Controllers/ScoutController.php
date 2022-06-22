<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Scout;
use Illuminate\Http\Request;

class ScoutController extends Controller
{

    public function show($id){
        $scout = Scout::find($id);
        response()->json([
            'data' => $scout
        ]);
    }

    public function index(){
        $scouts = Scout::all();

        return response()->json([
            'data' => $scouts
        ]);
    }

    public function showByGroup($id){
        $scout = Scout::where('group_id', $id)->get();

        return response()->json([
            'data' => $scout
        ]);
    }

    public function showByUnit($id){
        $scout = Scout::where('unit_id', $id)->get();

        return response()->json([
            'data' => $scout
        ]);
    }
    public function store(Request $request){
        $scout = Scout::create($request->all());

        return response()->json([
            'data' => $scout,
            'message' => 'Scout creado con exito'
        ], 201);
    }

    public function update(Request $request, $id){
        $scout = Scout::find($id);

        $scout->update($request->all());

        return response()->json([
            "data" => $scout,
            "message" => "Scout actualizado con exito"
        ]);
    }

    public function destroy($id){
        $scout = Scout::find($id);
        $scout->delete();

        return response()->json([
            'message' => 'Scout eliminado con exito'
        ]);
    }
}

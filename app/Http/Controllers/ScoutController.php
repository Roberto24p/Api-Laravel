<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Scout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ScoutController extends Controller
{

    public function show($id)
    {
        $scout = Scout::find($id);
        response()->json([
            'data' => $scout
        ]);
    }

    public function index()
    {
        $scouts = Scout::with('group')->with('unit')->get();

        return response()->json([
            'data' => $scouts
        ]);
    }

    public function showByGroup($id)
    {
        $scout = Scout::where('group_id', $id)->get();

        return response()->json([
            'data' => $scout
        ]);
    }

    public function showByUnit($id)
    {
        $scout = Scout::where('unit_id', $id)->get();

        return response()->json([
            'data' => $scout
        ]);
    }
    public function store(Request $request)
    {
        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
        		'password' => Hash::make($request->dni),
            ]
        );
        $request->merge(['user_id' => $user->id]) ;
        $scout = Scout::create($request->all());
        return response()->json([
            'data' => $scout,
            'message' => 'Scout creado con exito',
            'success' => 1
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $scout = Scout::find($id);

        $scout->update($request->all());

        return response()->json([
            "data" => $scout,
            "message" => "Scout actualizado con exito",
            "success" => 1
        ]);
    }

    public function destroy($id)
    {
        $scout = Scout::find($id);
        $scout->delete();

        return response()->json([
            'message' => 'Scout eliminado con exito'
        ]);
    }
}

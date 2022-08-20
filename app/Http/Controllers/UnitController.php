<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Team;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function store(Request $request)
    {
        $unit = Unit::create([
            'name' => $request['name'],
            'group_id' => $request['group_id'],
            'image' => $request['image'],
            'type' => $request['type'],
        ]);

        foreach ($request->equipos as $equipo) {
            Team::create(
                [
                    'name' => $equipo['name'],
                    'unit_id' => $unit->id,
                ]
            );
        }
        return response()->json(
            [
                'data' => $unit,
                'message' => 'Unidad creada con exito'
            ],
            201
        );
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::find($id);
        $unit->update($request->all());
        return response()->json(
            [
                'data' => $unit,
                'message' => 'Unitdad actualizado con exito'
            ],
            200
        );
    }

    public function index()
    {
        $units = Unit::with('group')->get();
        return response()->json(
            $units
        );
    }

    public function show($id)
    {
        $unit = Unit::find($id);
        return response()->json(
            $unit
        );
    }

    public function destroy($id)
    {
        $unit = Unit::find($id);
        $unit->delete();

        return response()->json(
            ['message' => 'Unidad eliminada']
        );
    }

    public function showByGroup($id)
    {
        $unitsByGroup = Unit::where('group_id', $id)->get();
        return $unitsByGroup;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Team;
use App\Models\Inscription;
use App\Models\Scout;
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
        $units = Unit::where('state', 'A')->with('group')->get();
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
        $unit->update([
            'state' => 'D'
        ]);

        return response()->json(
            [
                'message' => 'Unidad eliminada',
                'group' => $unit
            ]
        );
    }

    public function showByGroup($id)
    {
        $unitsByGroup = Unit::where('group_id', $id)->get();
        return $unitsByGroup;
    }

    public function showByScout($id)
    {
        $profile = Scout::find($id);
        $group = Inscription::getGroupByScoutInscription($id);
        $unitsByGroup = Unit::where('group_id', $group->group_id)->where('type', $profile->type)->with('teams')->get();

        return $unitsByGroup;
    }
}

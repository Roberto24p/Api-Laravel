<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Team;
use App\Models\Inscription;
use App\Models\Scout;
use App\Models\Person;
use App\Models\Directing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $units = Unit::with('group')->orderBy('state')->get();
        return response()->json(
            $units
        );
    }

    public function indexByDirecting()
    {
        $user = Auth::user();
        $directing = Person::with('directing')->where('id', $user->person_id)->first()->directing;
        $unit = Unit::find($directing->unit_id);
        $units = Unit::where('group_id', $unit->group_id)->get();
        return $units;
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

    public function activate($unitId)
    {
        $unit = Unit::find($unitId);
        $unit->update([
            'state' => 'A'
        ]);
        $unit->save();

        return response()->json([
            'success' => 1
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Unit;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        return Group::orderBy('state')->get();
    }

    public function show($id)
    {
        $group = Group::find($id);

        return response()->json([
            'data' => $group
        ]);
    }

    public function storePrueba(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'addres' => 'required',
            'img_url' => 'required',
        ]);
        $group = Group::create([
            'name' => $validate['name'],
            'addres' => $validate['addres'],
            'img_url' => $validate['img_url'],
        ]);
        return response()->json(
            [
                'data' => $group,
                'message' => 'Grupo creado con exito'
            ],
            201
        );
    }
    public function store(Request $request)
    {

        $group = Group::create([
            'name' => $request['name'],
            'addres' => $request['addres'],
            'image' => $request['image'],
        ]);

        foreach ($request->units as $unidad) {
            $unit = Unit::create(
                [
                    'name' => $unidad['name'],
                    'group_id' => $group->id,
                    'image' => $unidad['img'],
                    'type' => $unidad['type'],
                ]
            );
            foreach ($unidad['teams'] as $equipo) {
                Team::create(
                    [
                        'name' => $equipo['name'],
                        'unit_id' => $unit->id,
                    ]
                );
            }
        }
        return response()->json(
            [
                'data' => $group,
                'message' => 'Grupo creado con exito'
            ],
            201
        );
    }

    public function update(Request $request, $id)
    {
        $group = Group::find($id);

        $group->update($request->all());

        return response()->json(
            [
                'data' => $group,
                'message' => 'Grupo actualizado con exito'
            ],
            200
        );
    }

    public function destroy($id)
    {
        // $group = Group::find($id);
        $group = Group::where('id', $id)->with('units')->first();
        $aux = false;
        foreach ($group->units as $u) {
            if ($u->state == 'A')
                $aux = true;
        }

        if($aux != true){
            $group->update([
                'state' => 'D'
            ]);
            return response()->json([
                'message' => 'Grupo eliminado',
                'success' => 1
            ]);
        }else{
            return response()->json([
                'message' => 'Este grupo cuenta con unidades activas',
                'success' => 0
            ]);
        }

    }

    public function validateGroup($codigo)
    {
        $group = Group::where('cod_grupo', $codigo)->get();
        return response()->json([
            'group' => $group
        ]);
    }

    public function activate($groupid)
    {
        $group = Group::find($groupid);
        $group->update([
            'state' => 'A'
        ]);
        $group->save();
        return response()->json([
            'success' => 1,
            'group' => $group
        ]);
    }
}

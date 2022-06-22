<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        return Group::all();
    }

    public function show($id){
        $group = Group::find($id);

        return response()->json([
            'data' => $group
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'addres' => 'required',
            'img_url' => 'required',
            'association' => 'required'
        ]);
        $group = Group::create([
            'name' => $validate['name'],
            'addres' => $validate['addres'],
            'img_url' => $validate['img_url'],
            'association_id' => $validate['association']
        ]);
        return response()->json(
            [
                'data' => $group,
                'message' => 'Grupo creado con exito'
            ],
            201
        );
    }

    public function update(Request $request, $id){
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

    public function destroy($id){
        $group = Group::find($id);
        $group->delete();

        return response()->json([
            'message'=> 'Grupo Eliminado'
        ]);
    }
}

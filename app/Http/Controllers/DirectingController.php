<?php

namespace App\Http\Controllers;

use App\Models\Directing;
use Illuminate\Http\Request;

class DirectingController extends Controller
{
    public function store(Request $request)
    {

        $directing = Directing::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'age' => $request->age,
            'user_id' => $request->user_id,
            'group_id' => $request->group_id,
            'unit_id' => $request->unit_id
        ]);

        $directing->roles()->attach($request->role_id);

        return response()->json(
            [
                'data' => $directing,
                'message' => 'Dirigente creado con exito'
            ],
            201
        );
    }

    public function index()
    {
        $directings = Directing::all();
        return $directings;
    }

    public function update(Request $request, $id)
    {
        $directing = Directing::find($id);
        $directing->update($request->all());

        return response()->json(
            [
                'data' => $directing
            ],
            200
        );
    }

    public function destroy($id){
        $directing = Directing::find($id);
        $directing->delete();

        return response()->json([
            'message' => 'Dirigente eliminado'
        ]);

    }
}

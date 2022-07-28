<?php

namespace App\Http\Controllers;

use App\Models\Directing;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class DirectingController extends Controller
{
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
        $directing = Directing::create($request->all());

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

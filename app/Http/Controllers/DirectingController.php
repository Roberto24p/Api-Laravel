<?php

namespace App\Http\Controllers;

use App\Models\Directing;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $request->merge(['user_id' => $user->id]);
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
        $user = Auth::user();
        $role = User::find($user->id)->roles()->first();
        if ($role->id == 1 || $role->id == 2) {
            $directings = Directing::getAllDirecting();
        } else {
            $directing =  User::with('person')->where('id', $user->id)->first();
            $directings = Directing::directingsByGroup($directing->unit->group->id);
        }
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

    public function destroy($id)
    {
        $user = User::find($id);

        $user->update([
            'state' => 'D'
        ]);
        $user->update();
        $user->save();
        return response()->json([
            'message' => 'Dirigente eliminado',
            'success' => 1
        ]);
    }

    public function activate($id)
    {
        $user = User::find($id);
        $user->update([
            'state' => 'A'
        ]);
        $user->save();
        return response()->json([
            'message' => 'Dirigente Activado',
            'success' => 1
        ]);
    }

    public function directingProfile()
    {
        $user = Auth::user();
        $directing = Directing::where('person_id', $user->person_id)->first();
        return response()->json([
            'success' => 1,
            'profile' => $directing->unit
        ]);
    }

    public function directingsByUnit($unit){
        $directings = Directing::with('person')->where('unit_id', $unit)->get();
        return response()->json([
            'success'=>1,
            'directings' => $directings 
        ]);
    }

    // public function directingsByGroup()
    // {
    //     $user = Auth::user();
    //     $unit = Directing::where('person_id', $user->person_id)->first()->unit;

    //     return response()->json([
    //         'success'=> 1,
    //         'directings' => $unit
    //     ]);
    // }
}

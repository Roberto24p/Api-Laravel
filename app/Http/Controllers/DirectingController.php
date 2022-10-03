<?php

namespace App\Http\Controllers;

use App\Models\Directing;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Unit;
use App\Models\Person;
use App\Models\Group;
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
        $directings = Directing::with('person')->with('unit')->where('unit_id', $unit)->get();
        
        $units = Unit::where('group_id', Unit::find($unit)->group_id )->get();
        return response()->json([
            'success'=>1,
            'directings' => $directings,
            'units' => $units
        ]);
    }

    public function setDirectingUnit(Request $request){
        $directing = Directing::find($request->directingId);
        $directing->update([
            'unit_id' => $request->unitId
        ]);
        $directing->save();

        return response()->json([
            'message' => 'Dirigente actualizado correctamente',
            'success'=> 1,
            'directing' => $directing
        ]);
    }

    public function directingsByGroup($groupId){
        $group = Group::with('units')->where('id', $groupId)->first();
        $directingsResult = [];

        foreach($group->units as $unit){
            foreach($unit->directings as $dir){
                $directing = Directing::with('person')->with('unit')->where('person_id', $dir->person_id)->first();
                array_push($directingsResult, $directing);
            }
        }

        return response()->json([
            'success' => 1,
            'directings' => $directingsResult,
            'units' => $group->units
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

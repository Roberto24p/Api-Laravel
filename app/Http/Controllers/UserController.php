<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Person;
use App\Models\Scout;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::with(['person', 'roles'])->get();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $personId = Person::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'dni' => $request->dni,
            'born_date' => $request->date_born,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'nacionality' => $request->nacionality
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->dni),
            'person_id' => $personId->id
        ]);
        $user->roles()->attach($request->role);
        if ($request->role == 6) {
            Scout::create([
                'person_id' => $personId->id,
                'type' => 'tropa'
            ]);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Usuario creado con exito'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'name' => $request->name
        ]);
        $user->save();
        $person = Person::find($user->person()->first()->id);
        $person->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'dni' => $request->dni,
            'born_date' => $request->date_born,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'nacionality' => $request->nacionality
        ]);
        $person->save();
        $user->roles()->attach($request->role);
        
        return response()->json([
            'success' => 1,
            'message' => 'Actualizado con exito'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\Person;
use App\Models\Scout;
use Illuminate\Support\Facades\Auth;

class InscriptionController extends Controller
{
    public function store(Request $request)
    {
        $response = Inscription::create($request->all());

        return response()->json($response);
    }

    public function index()
    {
        $response = Inscription::all();

        return response()->json([
            'data' => $response,
        ]);
    }

    public function update(Request $request, $id)
    {
        $inscription = Inscription::find($id);
        $inscription->update($request->all());
        return response()->json([
            'data' => $inscription
        ]);
    }

    public function inscription(Request $request)
    {
        $user = Auth::user();
        $profile = Person::find($user->person_id);
        $scout = Scout::where('person_id', $profile->id)->get();
        $resp = Inscription::register($scout[0]->id, $request->group_id);
        if ($resp)
            return response()->json([
                'message' => 'Inscripcion Enviada'
            ]);
        else
            return response()->json([
                'message' => 'Problemas registrando la inscripcion'
            ]);
    }

    public function getScoutInscription()
    {
        $user = Auth::user();
        $profile = Person::find($user->person_id);
        $scout = Scout::where('person_id', $profile->id)->get();
        $data = Inscription::scoutGroupInscription($scout[0]->id);
        return response()->json(
            [
                'success' => 1,
                'data' => $data
            ]
        );
    }
}

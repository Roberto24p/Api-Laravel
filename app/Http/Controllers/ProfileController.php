<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Scout;
use App\Models\Inscription;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = Person::find($user->person_id);
        $scout = Scout::where('person_id', $profile->id)->get();
        return $profile;
    }

    public function infoInscription()
    {
        $user = Auth::user();
        $profile = Person::find($user->person_id);
        $scout = Scout::where('person_id', $profile->id)->first();
        $statusInscription = Inscription::scoutGroupInscription($scout->id);
        if ($statusInscription == null)
            return response()->json([
                'solo' => $statusInscription,
                'personalInfo' => $profile,
            ]);
        else
            return response()->json([
                'personalInfo' => $profile,
                'statusInscription' => $statusInscription
            ]);
    }

    public function store(Request $request)
    {
        $validateCi = validarCI($request->dni);
        if (!$validateCi) {
            return response()->json([
                'success' => 0,
                'profile' => 'Ingresar un cédula valida'
            ]);
        }
        $user = Auth::user();
        $profile = Person::find($user->person_id);
        $profile->update($request->all());
        $profile->save();
        return response()->json([
            'success' => 1,
            'profile' => $profile
        ]);
    }
}

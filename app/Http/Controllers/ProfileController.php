<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Scout;
use App\Models\Inscription;
use App\Models\AdvancePlan;
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

    public function profileScout()
    {
        $contd = 0;
        $user = Auth::user();
        $profile = Person::find($user->person_id);
        $scout = Scout::where('person_id', $profile->id)->first();
        $scoutTopic = $scout->topics;
        $data = Inscription::scoutGroupInscription($scout->id);
        $adv = AdvancePlan::where('type', $scout->type)->first();
        $recog = $adv->recognitions;
        $recogCumplidos = [];
        $recogIncumplidos = [];
        foreach ($recog as $r) {
            $acum = 0;
            foreach ($r->topics as $topic) {
                foreach ($scoutTopic as $st) {
                    if ($topic->id == $st->id) {
                        $acum++;
                    }
                }
            }
            if ($acum == count($r->topics)) {
                array_push($recogCumplidos, $r);
            }else{
                array_push($recogIncumplidos, $r);
            }
            $acum = 0;
        }

        $topict = count(AdvancePlan::tScout($scout->id));
        $planAvance = AdvancePlan::where('type', $scout->type)->with('recognitions')->first();
        $recognitions = $planAvance->recognitions;
        foreach ($recognitions as $recog) {
            $contd = $recog->topics->count() + $contd;
        }
        $topic = $scout->topics->first();
        $advancePlan = AdvancePlan::where('id', $topic->recognition->advance_plan_id)->with('recognitions')->get();
        return response()->json([
            'profile' => $profile,
            'data' => $data,
            'recog' => $recogCumplidos,
            'percent' => number_format($topict / $contd, 1),
            'advancePlan' => $advancePlan,
            'success' => 1

        ]);
    }
}

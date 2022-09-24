<?php

namespace App\Http\Controllers;

use App\Models\AdvancePlan;
use App\Models\Topic;
use App\Models\Scout;
use App\Models\User;
use App\Models\Recognition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvancePlanController extends Controller
{
    public function index()
    {
        return AdvancePlan::all();
    }

    public function store(Request $request)
    {
        $advancePlan = AdvancePlan::create([
            'tittle' => $request->tittle,
            'Description' => $request->description,
            'type' => $request->type
        ]);
        return response()->json([
            'success' => 1,
            'message' => 'Creado con exito',
            'advancePlan' => $advancePlan
        ]);
    }

    public function recognitionsWithItems($id)
    {
        $data = AdvancePlan::where('id', $id)->with('recognitions')->first();

        return response()->json([
            'success' => 1,
            'advancePlan' => $data
        ]);
    }



    public function updateAdvancePlan(Request $request)
    {
        //validar si se puede actualizar el Plan de Adelanto
        $advancePlan = AdvancePlan::with('recognitions')->where('id', $request->advancePlanId)->first();

        foreach ($advancePlan->recognitions as $recog) {
            foreach ($recog->topics as $t) {
                if (count($t->scouts) > 0) {
                    return response()->json([
                        'success' => 0,
                        'message' => 'No es posible actualizar el plan de adelanto porque ya se esta tramitando el plan de adelanto'
                    ]);
                }
            }
        }
        $response = AdvancePlan::where('id', $request->advancePlanId)
            ->update([
                'Description' => $request->description,
                'tittle' => $request->tittle,
            ]);
        foreach ($request->recognitions as $recognition) {
            if (isset($recognition['id']))
                $recog = Recognition::find($recognition['id']);
            else
                $recog = null;

            if ($recog == null) {
                $recog = Recognition::create(
                    [
                        'advance_plan_id' => $request->advancePlanId,
                        'name' => $recognition['name'],
                    ]
                );
            } else {
                $recog->update([
                    'name' => $recognition['name'],
                ]);
                $recog->save();
            }
            foreach ($recognition['topics'] as $item) {
                if (isset($item['id']))
                    $topic = Topic::find($item['id']);
                else
                    $topic = null;

                if ($topic == null) {
                    Topic::create([
                        'recognition_id' => $recog->id,
                        'name' => $item['name']
                    ]);
                } else {
                    $topic->update([
                        'name' => $item['name']
                    ]);
                    $topic->save();
                }
            }
        }
        foreach ($request->itemDelete as $item) {
            Topic::where('id', $item)->delete();
        }

        return response()->json([
            'success' => 1,
            'advancePlan' => $response
        ]);
    }

    public function deleteRecognition($recognitionId, $advancePlanId)
    {
        $advancePlan = AdvancePlan::with('recognitions')->where('id', $advancePlanId)->first();

        foreach ($advancePlan->recognitions as $recog) {
            foreach ($recog->topics as $t) {
                if (count($t->scouts) > 0) {
                    return response()->json([
                        'success' => 0,
                        'message' => 'No es posible actualizar el plan de adelanto porque ya se esta tramitando el plan de adelanto'
                    ]);
                }
            }
        }
        $recognition = Recognition::find($recognitionId);
        $recognition->delete();
        return response()->json([
            'success' => 1,
            'message' => 'Eliminado correctamente'
        ]);
    }

    public function getTopicsChecked($scoutId)
    {
        $topics = Topic::topicsScout($scoutId);
        return response()->json([
            'topics' => $topics
        ]);
    }

    public function checkAdvancePlan(Request $request)
    {
        $scout = Scout::find($request->scout_id);
        foreach ($request->topics as $topic) {
            $val = $scout->topics()->detach($topic);
            if ($val == 0) {
                $scout->topics()->attach($topic);
            }
        }

        $topics = Topic::topicsScout($request->scout_id);

        return response()->json([
            'topics' => $topics,
            'message' => 'Registrado el Tema'
        ]);
    }

    public function recognitionsWithItemsByScout($scout) //No olvidar validar
    {
        $scout = Scout::find($scout);
        if (count($scout->topics) == 0) {
            $advancePlan = AdvancePlan::where('type', $scout->type)->where('state', 'A')->orderBy('created_at', 'DESC')->with('recognitions')->get();
        } else {
            $topic = $scout->topics->first();
            $advancePlan = AdvancePlan::where('id', $topic->recognition->advance_plan_id)->with('recognitions')->get();
        }
        return $advancePlan;
    }
    public function recognitionsWithItemsByScoutProfile() //No olvidar validar
    {
        $scoutId = User::with('person')->where('person_id', Auth::user()->person_id)->first()->person->scout->id;

        $scout = Scout::find($scoutId);
        if (count($scout->topics) == 0) {
            $advancePlan = AdvancePlan::where('type', $scout->type)->where('state', 'A')->orderBy('created_at', 'DESC')->with('recognitions')->get();
        } else {
            $topic = $scout->topics->first();
            $advancePlan = AdvancePlan::where('id', $topic->recognition->advance_plan_id)->with('recognitions')->get();
        }
        return $advancePlan;
    }

    public function percentAdvancePlan($id)
    {
        $contd = 0;
        $scout = Scout::find($id);
        $topict = count(AdvancePlan::tScout($id));
        $planAvance = AdvancePlan::where('type', $scout->type)->with('recognitions')->first();
        $recognitions = $planAvance->recognitions;
        foreach ($recognitions as $recog) {
            $contd = $recog->topics->count() + $contd;
        }
        return number_format($topict / $contd, 1);
    }

    public function percentAdvancePlanByScoyt()
    {
        $contd = 0;
        // $user = User::find(Auth::user()->person_id);
        $id = User::with('person')->where('person_id', Auth::user()->person_id)->first()->person->scout->id;
        $scout = Scout::find($id);
        $topict = count(AdvancePlan::tScout($id));
        $planAvance = AdvancePlan::where('type', $scout->type)->with('recognitions')->first();
        $recognitions = $planAvance->recognitions;
        foreach ($recognitions as $recog) {
            $contd = $recog->topics->count() + $contd;
        }
        return number_format($topict / $contd, 1);
    }

    public function getRecognationsComplete($scoutid)
    {
        $scout = Scout::where('id', $scoutid)->first();
        $scoutTopic = $scout->topics;
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

        return response()->json([
            'recog' => $recogCumplidos,
            'recogIncump' => $recogIncumplidos,
            'success' => 1
        ]);
    }

    public function getRecognitionsCompleteScout()
    {
        $scoutId = User::with('person')->where('person_id', Auth::user()->person_id)->first()->person->scout->id;
        $scout = Scout::find($scoutId);
        $scoutTopic = $scout->topics;
        $adv = AdvancePlan::where('type', $scout->type)->first();
        $recog = $adv->recognitions;
        $recogCumplidos = [];
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
            }
            $acum = 0;
        }

        return response()->json([
            'recog' => $recogCumplidos,
            'success' => 1
        ]);
    }

    public function disableAdvancePlan($advancePlan)
    {
        $advancePlan = AdvancePlan::find($advancePlan);
        $advancePlan->update([
            'state' => 'D'
        ]);
        $advancePlan->save();
        return response()->json([
            'success' => 1,
            'message' => 'Desactivado correctamente'
        ]);
    }

    public function enableAdvancePlan($advancePlan)
    {
        $advancePlan = AdvancePlan::find($advancePlan);
        $advancePlan->update([
            'state' => 'A'
        ]);
        $advancePlan->save();
        return response()->json([
            'success' => 1,
            'message' => 'Habilitado correctamente'
        ]);
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\AdvancePlan;
use App\Models\Topic;
use App\Models\Scout;
use App\Models\Recognition;
use Illuminate\Http\Request;

class AdvancePlanController extends Controller
{
    public function index()
    {
        return AdvancePlan::all();
    }

    public function recognitionsWithItems($id)
    {
        $group = AdvancePlan::where('id', $id)->with('recognitions')->get();

        return $group;
    }

    public function updateAdvancePlan(Request $request)
    {
        $response = AdvancePlan::where('id', $request->advancePlanId)
            ->update([
                'Description' => $request->description,
                'tittle' => $request->tittle,
            ]);
        foreach ($request->recognitions as $recognition) {
            Recognition::updateOrCreate(
                [
                    'id' => $recognition['id']
                ],
                [
                    'advance_plan_id' => $request->advancePlanId,
                    'name' => $recognition['name'],
                ]
            );
            foreach ($recognition['topics'] as $item) {
                Topic::updateOrCreate(
                    [
                        'id' => $item['id']
                    ],
                    [
                        'recognition_id' => $recognition['id'],
                        'name' => $item['name']
                    ]
                );
            }
        }
        foreach ($request->itemDelete as $item) {
            Topic::where('id', $item)->delete();
        }

        return $response;
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

    public function recognitionsWithItemsByScout($scout)
    {
        $scout = Scout::find($scout);
        $advancePlan = AdvancePlan::where('type', $scout->type)->with('recognitions')->get();
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

    public function getRecognationsComplete($scoutid){
        $scout = Scout::where('id', $scoutid)->first();
        $scoutTopic = $scout->topics;
        $adv = AdvancePlan::where('type', $scout->type)->first();
        $recog = $adv->recognitions;
        $recogCumplidos = [];
        foreach($recog as $r){
            $acum = 0;
            foreach($r->topics as $topic){
                foreach($scoutTopic as $st){
                    if($topic->id == $st->id){
                        $acum++;
                    }
                }
            }
            if($acum == count($r->topics)){
                array_push($recogCumplidos, $r);
            }
            $acum = 0;
        }

        return response()->json([
            'recog' => $recogCumplidos,
            'success' => 1
        ]);
        


    }

    // public function checkAdvancePlan(Request $request){
    //     $scout = Scout::find($request->scout_id);
    //     Topic::attachScoutTeam($scout->id, $request->team_id, $request->topic_id);
    //     // $scout->topics()->attach($request->topic_id);
    //     $topics = Topic::topicsScout($request->scout_id);
    //     return response()->json([
    //         'topics' => $topics,
    //         'message' => 'Registrado el Tema'
    //     ]);
    // }
}

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
        foreach($request->itemDelete as $item){
            Topic::where('id', $item)->delete();
        }

        return $response;
    }

    public function checkAdvancePlan(Request $request){
        $scout = Scout::find($request->scout_id);
        $scout->topics()->attach($request->topic_id);
        $topics = Topic::topicsScout($request->scout_id);
        return response()->json([
            'topics' => $topics,
            'message' => 'Registrado el Tema'
        ]);
    }
}

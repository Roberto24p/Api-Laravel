<?php

namespace App\Http\Controllers;

use App\Models\AdvancePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Scout;
use App\Models\Inscription;
use App\Models\Range;
use App\Models\Group;
use App\Models\Recognition;
use Barryvdh\DomPDF\Facade\Pdf;


class ReportController extends Controller
{
    public function scoutsInscriptionsUnit($groupId)
    {
        $inscriptionsScout = Inscription::inscriptionsByUnit($groupId);
        $units = Range::all();
        $acum = 0;
        $nameAux = '';
        $report = [];
        foreach ($units as $u) {
            $obj =  new \stdClass();
            $nameAux = $u->name;
            foreach ($inscriptionsScout as $insScout) {
                if (strnatcasecmp($insScout->type, $nameAux) == 0) {
                    $acum++;
                }
            }
            $obj->type = $nameAux;
            $obj->size = $acum;
            array_push($report, $obj);
            $acum = 0;
        }
        $group = Group::find($groupId);
        $dateNow = date('l jS \of F Y ', time());
        $pdf = Pdf::LoadView('reports.scoutsUnit', [
            'units' => $report,
            'date' => fechaLatino($dateNow),
            'group' => $group 
        ]);
        return $pdf->stream();
    }
    public function advancePlanComplete($scoutId)
    {
        $scout = Scout::find($scoutId);
        $topicsComplete = $scout->topics()->get();
        // $recognition = Recognition::find($topicsComplete[0]->recognition_id);
        // $advancePlan = AdvancePlan::find($recognition->advance_plan_id);
        $advancePlan = AdvancePlan::where('type', $scout->type)->first();
        $report = [];
        $obj =  new \stdClass();

        foreach ($advancePlan->recognitions as  $recognition) {

            $obj =  new \stdClass();
            $obj->name_recognition = $recognition->name;
            $obj->topics = [];

            foreach ($recognition->topics as $topic) {
                $objTopic =  new \stdClass();
                $objTopic->name = $topic->name;
                $objTopic->status = false;
                foreach ($topicsComplete as $tc) {
                    if ($tc->id == $topic->id) {
                        $objTopic->status = true;
                        $objTopic->dateGet = fechaLatino($tc->pivot->created_at);
                    }
                }
                array_push($obj->topics, $objTopic);
            }
            array_push($report, $obj);
        }
        $dateNow = date('l jS \of F Y ', time());

        $pdf = Pdf::LoadView('reports.advancePlanScout', ['advancePlan' => $report, 'date' => fechaLatino($dateNow), 'scout' => $scout->person]);

        return $pdf->stream();
    }
}

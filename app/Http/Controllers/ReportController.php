<?php

namespace App\Http\Controllers;

use App\Exports\InscriptionsExport;
use App\Models\AdvancePlan;
use App\Models\Directing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Scout;
use App\Models\Inscription;
use App\Models\Range;
use App\Models\Group;
use App\Models\Period;
use App\Models\Person;
use App\Models\Recognition;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class ReportController extends Controller
{
    public function teamScoutsDetails()
    {
        
    }

    public function directingDetails()
    {
        $groups = Group::with('units')->get();
        $dateNow = date('l jS \of F Y ', time());

        $pdf = Pdf::LoadView('reports.directingsDetails', [
            'groups' => $groups,
            'date' => fechaLatino($dateNow)
        ]);
        // return $pdf->download('Detalles_directing.pdf');
        return $pdf->stream();
    }
    public function directingsByGroup()
    {
        $directings = Directing::getDirectingsActive();
        $groups = Group::where('state', 'A')->get();
        $reporte = [];
        foreach ($groups as $group) {
            $groupObj = new stdClass();

            $count = 0;
            $groupObj->name = $group->name;
            foreach ($directings as $directing) {
                if ($group->id == $directing->group_id) {
                    $count++;
                }
            }
            $groupObj->size = $count;
            array_push($reporte, $groupObj);
        }
        $dateNow = date('l jS \of F Y ', time());

        $pdf = Pdf::LoadView('reports.directingsByGroup', [
            'groups' => $reporte,
            'date' => fechaLatino($dateNow)
        ]);
        return $pdf->download('directingsByGroup.pdf');
    }

    public function graphicDirectingsByGroup()
    {
        $directings = Directing::getDirectingsActive();
        $groups = Group::where('state', 'A')->get();
        $reporte = [];
        foreach ($groups as $group) {
            $groupObj = new stdClass();

            $count = 0;
            $groupObj->name = $group->name;
            foreach ($directings as $directing) {
                if ($group->id == $directing->group_id) {
                    $count++;
                }
            }
            $groupObj->size = $count;
            array_push($reporte, $groupObj);
        }

        return response()->json([
            'groups' => $reporte
        ]);
    }

    public function scoutsInscriptionsUnit()
    {
        $user = Auth::user();
        $personId = User::find($user->id)->person_id;
        $directing = Directing::where('person_id', $personId)->first();
        $groupId = $directing->unit->group->id;
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
        $dateNow = getCurrentDate();
        $pdf = Pdf::LoadView('reports.scoutsUnit', [
            'units' => $report,
            'date' => fechaLatino($dateNow),
            'group' => $group
        ]);
        return $pdf->stream();
    }

    public function graphicScoutsInscriptionsUnit()
    {
        $user = Auth::user();
        $personId = User::find($user->id)->person_id;
        $directing = Directing::where('person_id', $personId)->first();
        $groupId = $directing->unit->group->id;
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

        return $report;
    }

    public function advancePlanComplete()
    {
        $user = Auth::user();
        $person = Person::where('id', $user->person_id)->first();
        $scout = Scout::find($person->scout->id);
        $topicsComplete = $scout->topics()->get();
        if (count($scout->topics) == 0) {
            $advancePlan = AdvancePlan::where('type', $scout->type)->where('state', 'A')->first();
        } else {
            $topic = $scout->topics->first();
            $advancePlan = AdvancePlan::where('id', $topic->recognition->advance_plan_id)->first();
        }
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
        $dateNow = getCurrentDate();


        $pdf = Pdf::LoadView('reports.advancePlanScout', ['advancePlan' => $report, 'date' => ($dateNow), 'scout' => $scout->person]);

        return $pdf->stream();
    }

    public function pdfInscriptionsGroups($periodId)
    {
        $inscriptions = Inscription::cantidadInscritosxgrupos($periodId);
        $periodInfo = Period::find($periodId);
        $groups = Group::all();
        $acum = 0;
        $teamGroup = '';
        $list = [];
        foreach ($inscriptions as $clave => $i) {
            $obj =  new \stdClass();
            if ($clave == 0) {
                $acum = $acum + 1;
                $teamGroup = $i->name;
            } else {
                if (count($inscriptions) == intval($clave) + 1) {
                    $obj->id = $i->group_id;
                    $obj->name = $teamGroup;
                    $obj->size = $acum + 1;
                    array_push($list, $obj);
                } else {
                    if ($teamGroup != $i->name) {
                        $obj->id = $i->group_id;
                        $obj->name = $teamGroup;
                        $obj->size = $acum;
                        $acum = 1;
                        array_push($list, $obj);
                        $teamGroup = $i->name;
                    } else {
                        $acum = $acum + 1;
                    }
                }
            }
        }
        $auxL = $list;
        $aux = false;
        // foreach ($groups as $g) {
        //     foreach ($list as $l) {
        //         if ($l->id == $g->id) {
        //             $aux = true;
        //         }
        //     }
        //     if ($aux == false) {
        //         $obj =  new \stdClass();
        //         $obj->name = $g->name;
        //         $obj->size = 0;
        //         $obj->id = $g->id;
        //         array_push($list, $obj);
        //     }
        //     $aux = false;
        // }

        $dateNow = getCurrentDate();

        $pdf = Pdf::LoadView('reports.scoutsGroup', [
            'groups' => $list,
            'date' => $dateNow,
            'period' => $periodInfo,
            'aux' => $auxL
        ]);
        return $pdf->stream();
    }
    public function graphicInscriptionsGroups()
    {
        $period = Period::where('state', 'Activo')->first();
        $inscriptions = Inscription::cantidadInscritosxgrupos($period->id);
        $groups = Group::all();
        $acum = 0;
        $teamGroup = '';
        $listGroup = [];
        $listSize = [];
        foreach ($inscriptions as $clave => $i) {
            $obj =  new \stdClass();
            if ($clave == 0) {
                $acum = $acum + 1;
                $teamGroup = $i->name;
            } else {
                if (count($inscriptions) == intval($clave) + 1) {
                    $obj->id = $i->id;
                    $obj->name = $teamGroup;
                    $obj->size = $acum + 1;
                    array_push($listGroup, $obj->name);
                    array_push($listSize, $obj->size);
                } else {
                    if ($teamGroup != $i->name) {
                        $obj->id = $i->id;
                        $obj->name = $teamGroup;
                        $obj->size = $acum;
                        $acum = 1;
                        array_push($listGroup, $obj->name);
                        array_push($listSize, $obj->size);
                        $teamGroup = $i->name;
                    } else {
                        $acum = $acum + 1;
                    }
                }
            }
        }
        $aux = false;
        foreach ($groups as $g) {
            foreach ($listGroup as $l) {
                if ($l == $g->name) {
                    $aux = true;
                }
            }
            if ($aux == false) {
                $obj =  new \stdClass();
                $obj->name = $g->name;
                $obj->size = 0;
                $obj->id = $g->id;
                array_push($listGroup, $obj->name);
                array_push($listSize, $obj->size);
            }
            $aux = false;
        }


        return response()->json([
            "groups" => $listGroup,
            "size" => $listSize
        ]);
    }

    public function graphicScoutsRecognitions(){

    }

    public function graphicsMoneyInscriptionsByPeriod($periodId){
        $period = Period::where('id', $periodId)->first();
        $inscriptions = Inscription::cantidadInscritosxgrupos($period->id);
        $groups = Group::all();
        $acum = 0;
        $teamGroup = '';
        $listGroup = [];
        $listSize = [];
        foreach ($inscriptions as $clave => $i) {
            $obj =  new \stdClass();
            if ($clave == 0) {
                $acum = $acum + 1;
                $teamGroup = $i->name;
            } else {
                if (count($inscriptions) == intval($clave) + 1) {
                    $obj->id = $i->id;
                    $obj->name = $teamGroup;
                    $obj->size = ($acum + 1 )*($period->price);
                    array_push($listGroup, $obj->name);
                    array_push($listSize, $obj->size);
                } else {
                    if ($teamGroup != $i->name) {
                        $obj->id = $i->id;
                        $obj->name = $teamGroup;
                        $obj->size = $acum*($period->price);
                        $acum = 1;
                        array_push($listGroup, $obj->name);
                        array_push($listSize, $obj->size);
                        $teamGroup = $i->name;
                    } else {
                        $acum = $acum + 1;
                    }
                }
            }
        }
        $aux = false;
        foreach ($groups as $g) {
            foreach ($listGroup as $l) {
                if ($l == $g->name) {
                    $aux = true;
                }
            }
            if ($aux == false) {
                $obj =  new \stdClass();
                $obj->name = $g->name;
                $obj->size = 0;
                $obj->id = $g->id;
                array_push($listGroup, $obj->name);
                array_push($listSize, $obj->size);
            }
            $aux = false;
        }


        return response()->json([
            "groups" => $listGroup,
            "size" => $listSize
        ]);
    }
    public function AllInscriptionsByPeriod()
    {
        return Excel::download(new InscriptionsExport, 'users.xlsx');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\Person;
use App\Models\Scout;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;

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
        $scout = Scout::where('person_id', $profile->id)->first();
        $resp = Inscription::register($scout->id, $request->group_id, $request->imagePermission, $request->imagePhoto, $request->imagePay);
        if ($resp)
            return response()->json([
                'message' => 'Inscripcion Enviada',
                'success' => 1
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

    public function getAllInscriptions()
    {
        $inscriptions = Inscription::allInscriptions();
        return response()->json([
            'data' => $inscriptions,
            'succcess' => 1
        ]);
    }

    public function putScoutInscription(Request $request)
    {
        Inscription::putScoutInscription($request->state_inscription, $request->observations, $request->id);
        return response()->json([
            'success' => 1
        ]);
    }

    public function pdfInscriptionsGroups()
    {
        $inscriptions = Inscription::cantidadInscritosxgrupos();
        $acum = 0;
        $teamGroup = '';
        $list = [];
        foreach ($inscriptions as $clave => $i) {
            $obj =  new \stdClass();
            if ($clave == 0) {
                $acum = $acum + 1;
                $teamGroup = $i->name;
            } else {
                if (count($inscriptions) == $clave + 1) {
                    $obj->image = $i->image;
                    $obj->name = $teamGroup;
                    $obj->size = $acum + 1;
                    array_push($list, $obj);
                } else {
                    if ($teamGroup != $i->name) {
                        $obj->name = $teamGroup;
                        $obj->size = $acum;
                        $obj->image = $i->image;

                        $acum = 1;
                        array_push($list, $obj);
                        $teamGroup = $i->name;
                    } else {
                        $acum = $acum + 1;
                    }
                }
            }
        }
        $dateNow = date('l jS \of F Y ', time());
        $pdf = Pdf::LoadView('reports.scoutsGroup', ['groups' => $list, 'date' => $dateNow]);
        return $pdf->stream();
    }
}

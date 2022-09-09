<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period;

class PeriodController extends Controller
{
    public function store(Request $request)
    {
        $response = Period::create($request->all());

        return response()->json($response);
    }

    public function index()
    {
        $response = Period::orderBy('state', 'asc')->get();

        return response()->json([
            'data' => $response,
            'success' => 1
        ]);
    }

    public function update(Request $request, $id)
    {
        $inscription = Period::find($id);
        $inscription->update($request->all());
        return response()->json([
            'data' => $inscription
        ]);
    }

    public function showPeriodNow(){
        $period = Period::periodNow();
        return response()->json([
            'response' => $period,
            'success' => 1
        ]);
    }

    public function activate($periodId){
        $period = Period::all();
        foreach($period as $p){
            if($p->id == $periodId){
                $p->update([
                    'state' => 'Activo'
                ]);
                $p->save();
            }else{
                $p->update([
                    'state' => 'Inactivo'
                ]);
                $p->save();
            }
        }

        return response()->json([
            'success' =>1,
            'message' => 'Periodos actualizados'
        ]);
    }
}
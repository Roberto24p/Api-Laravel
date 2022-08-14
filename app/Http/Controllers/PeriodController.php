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
        $response = Period::all();

        return response()->json([
            'data' => $response,
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
}
<?php
namespace App\Http\Controllers;
use App\Models\Team;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TeamController extends Controller
{
 
    public function index(){
        $teams = Team::with('unit')->where('state', 'A')->get();
        return response()->json([
            'response' => 1,
            'teams' => $teams
        ]);
    }

    public function teamByDirecting(){
        $user = Auth::user();
        $directing = Person::with('directing')->where('id', $user->person_id)->first()->directing;
        $teams = Team::with('unit')->where('unit_id', $directing->unit_id)->get();
        return response()->json([
            'response' => 1,
            'teams' => $teams
        ]);
    }

    public function store(Request $request){
        $team = Team::create([
            'name' => $request->name,
            'unit_id' => $request->unitId
        ]);
        return response()->json([
            'success' => 1,
            'team' => $team
        ]);
    }

    public function update(Request $request, $id){
        $team = Team::find($id);
        $team->update($request->all());
        $team->save();
        return response()->json([
            'success' => 1,
            'team' => $team
        ]);
    }

    public function destroy($id){
        $team = Team::find($id);
        $team->update([
            'state' => 'D'
        ]);
        $team->save();
        return response()->json([
            'success' => 1,
            'team' => $team
        ]);
    }

    public function getByUnit($unit){
        $teams = Team::where('unit_id', $unit)
            ->where('state', 'A')->get();
        return response()->json([
            'response' => 1,
            'teams' => $teams
        ]);
    }

    public function teamScout(Request $request){
        $team = Team::find($request->team_id);
        $team = $team->scouts()->attach($request->scout_id);
        return response()->json([
            'success'=> 1,
            'team' => $team
        ]);
    }

  
}
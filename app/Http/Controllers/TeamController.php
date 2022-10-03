<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Person;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TeamController extends Controller
{

    public function index()
    {
        $teams = Team::with('unit')->orderBy('state', 'ASC')->get();
        return response()->json([
            'response' => 1,
            'teams' => $teams
        ]);
    }

    public function teamByDirecting()
    {
        $user = Auth::user();
        $directing = Person::with('directing')->where('id', $user->person_id)->first()->directing;
        $teams = Team::with('unit')->where('unit_id', $directing->unit_id)->where('state', 'A')->get();
        $unit = $directing->unit;
        return response()->json([
            'response' => 1,
            'teams' => $teams,
            'unit' => $unit,
        ]);
    }

    public function store(Request $request)
    {
        $team = Team::create([
            'name' => $request->name,
            'unit_id' => $request->unitId
        ]);
        return response()->json([
            'success' => 1,
            'team' => $team
        ]);
    }

    public function update(Request $request, $id)
    {
        $team = Team::find($id);
        $team->update($request->all());
        $team->save();
        return response()->json([
            'success' => 1,
            'team' => $team
        ]);
    }

    public function destroy($id)
    {
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

    public function getByUnit($unit)
    {
        $teams = Team::where('unit_id', $unit)
            ->where('state', 'A')->get();
        return response()->json([
            'response' => 1,
            'teams' => $teams
        ]);
    }

    public function teamScout(Request $request)
    {
        $team = Team::find($request->team_id);
        $team = $team->scouts()->attach($request->scout_id);
        return response()->json([
            'success' => 1,
            'team' => $team
        ]);
    }

    public function activate($team)
    {
        $team = Team::find($team);
        $team->update([
            'state' => 'A'
        ]);
        $team->save();

        return response()->json([
            'success' => 1,
            'team' => $team
        ]);
    }

    public function allTeamsByDirectingGroup()
    {
        $user = Auth::user();
        $directing = Person::with('directing')->where('id', $user->person_id)->first()->directing;
        $units = Group::with('units')->where('id', $directing->unit->group_id)->first()->units;
        $group = Group::find($directing->unit->group_id);
        $teams = [];
        foreach ($units as $u) {
            foreach ($u->teams as $t) {
                if ($t->state == 'A') {
                    $team = Team::with('unit')->where('id', $t->id)->first();
                    array_push($teams, $team);
                }
            }
        }
        return response()->json([
            'response' => 1,
            'teams' => $teams,
            'group' => $group->id
        ]);
    }
}

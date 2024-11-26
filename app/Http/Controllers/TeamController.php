<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        return response()->json(Team::all(), 200);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:teams',
            'country' => 'required|string',
            'year_founded' => 'required|integer|min:1800|max:' . date('Y'),
            'team_principal' => 'required|string',
        ]);

        $team = Team::create($validated);

        return response()->json($team, 201);
    }
    public function pilots(Team $team)
    {
        return response()->json(
            $team->pilots()->select('id', 'country', 'name')->get(),
            200
        );
    }

    public function search(Request $request)
    {
        $query = Team::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('country')) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        if ($request->has('year_founded')) {
            $query->where('year_founded', $request->year_founded);
        }

        $teams = $query->get();
        return response()->json($teams, 200);
    }

    public function statistics(Team $team)
    {
        $stats = [
            'total_pilots' => $team->pilots()->count(),
            'average_pilot_age' => round($team->pilots()->avg('age'), 1),
            'countries_represented' => $team->pilots()->distinct('country')->count('country'),
        ];

        return response()->json($stats, 200);
    }

    public function show(Team $team)
    {
        if (!$team->exists) {
            return response()->json(['message' => 'Team not found'], 404);
        }
        
        return response()->json($team, 200);
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|unique:teams,name,' . $team->id,
            'country' => 'sometimes|string',
            'year_founded' => 'sometimes|integer|min:1800|max:' . date('Y'),
            'team_principal' => 'sometimes|string',
        ]);

        $team->update($validated);

        return response()->json($team, 200);
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return response()->json(['message' => 'Team deleted successfully'], 200);
    }
}

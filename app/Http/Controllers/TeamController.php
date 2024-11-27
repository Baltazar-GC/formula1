<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::withCount('pilots')->get();
        return view('teams.index', compact('teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'director' => 'required|string|max:255',
            'logo' => 'nullable|image|max:1024',
            'year_founded' => 'required|integer',
            'team_principal' => 'required|string|max:255'
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('teams', 'public');
        }

        Team::create($validated);

        return redirect()->route('teams.index')
            ->with('success', 'Equipo creado exitosamente');
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'director' => 'required|string|max:255',
            'logo' => 'nullable|image|max:1024',
            'year_founded' => 'required|integer',
            'team_principal' => 'required|string|max:255'
        ]);

        if ($request->hasFile('logo')) {
            if ($team->logo) {
                Storage::disk('public')->delete($team->logo);
            }
            $validated['logo'] = $request->file('logo')->store('teams', 'public');
        }

        $team->update($validated);

        return redirect()->route('teams.index')
            ->with('success', 'Equipo actualizado exitosamente');
    }

    public function destroy(Team $team)
    {
        if ($team->logo) {
            Storage::disk('public')->delete($team->logo);
        }
        
        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', 'Equipo eliminado exitosamente');
    }
}

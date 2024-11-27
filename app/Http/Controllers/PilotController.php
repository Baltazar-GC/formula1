<?php

namespace App\Http\Controllers;

use App\Models\Pilot;
use App\Models\Team;
use Illuminate\Http\Request;

class PilotController extends Controller
{
    public function index()
    {
        $pilots = Pilot::with('team')->get();
        $teams = Team::all();
        return view('pilots.index', compact('pilots', 'teams'));
    }

    public function create()
    {
        $teams = Team::all();
        return view('pilots.form', compact('teams'));
    }

    public function edit($id)
    {
        $pilot = Pilot::findOrFail($id);
        $teams = Team::all();
        return view('pilots.form', compact('pilot', 'teams'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'team_id' => 'required|exists:teams,id',
                'age' => 'required|integer|min:16|max:100',
                'country' => 'required|string|max:255',
            ]);

            Pilot::create($validated);

            return redirect()->route('pilots.index')
                ->with('success', 'Piloto creado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el piloto: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $pilot = Pilot::findOrFail($id);
        return response()->json($pilot);
    }

    public function update(Request $request, Pilot $pilot)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'team_id' => 'required|exists:teams,id',
                'age' => 'required|integer|min:16|max:100',
                'country' => 'required|string|max:255',
            ]);

            $pilot->update($validated);

            return redirect()->route('pilots.index')
                ->with('success', 'Piloto actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el piloto: ' . $e->getMessage())
                ->withInput();
        }
    }
    

    public function destroy(Pilot $pilot)
    {
        try {
            $pilot->delete();
            return redirect()->route('pilots.index')
                ->with('success', 'Piloto eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el piloto: ' . $e->getMessage());
        }
    }
}

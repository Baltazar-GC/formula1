<?php

namespace App\Http\Controllers;

use App\Models\Pilot;
use Illuminate\Http\Request;

class PilotController extends Controller
{
    public function index()
    {
        // Obtener todos los pilotos con sus equipos
        $pilots = Pilot::with('team')->get();

        // Retornar como respuesta JSON
        return response()->json($pilots);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'required|integer',
            'age' => 'required|integer',
            'country' => 'required|string|max:255',
        ]);

        $pilot = Pilot::create($validated);
        return response()->json($pilot, 201); // Retorna el piloto creado
    }

    public function show($id)
    {
        $pilot = Pilot::findOrFail($id);
        return response()->json($pilot);
    }

    public function update(Request $request, $id)
    {
        $pilot = Pilot::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'required|integer',
            'age' => 'required|integer',
            'country' => 'required|string|max:255',
        ]);

        $pilot->update($validated);
        return response()->json($pilot);
    }
    

    public function destroy($id)
    {
        $pilot = Pilot::findOrFail($id);
        $pilot->delete();
        return response()->json(null, 204); // Retorna respuesta 204 (sin contenido)
    }
}

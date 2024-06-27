<?php

namespace App\Http\Controllers;

use App\Models\Managers;
use Illuminate\Http\Request;

class ManagersController extends Controller
{
    public function index()
    {
        // Obtener todos los managers con sus cargos
        $managers = Managers::with('managers_cargos')->get();
        return response()->json($managers);
    }

    public function store(Request $request)
    {
        // Validación
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mother_last_name' => 'required|string|max:255',
            'abbreviated_title' => 'required|string|max:10',
            'gender' => 'required|string|max:1',
            'signature' => 'required|string',
            'cargos' => 'required|array',
            'cargos.*' => 'integer|exists:cargos,id',
        ]);

        // Crear manager
        $manager = Managers::create($validatedData);

        // Asignar cargos
        if (isset($validatedData['cargos'])) {
            $manager->managers_cargos()->sync($validatedData['cargos']);
        }

        return response()->json($manager, 201);
    }

    public function show($id)
    {
        // Obtener manager con sus cargos
        $manager = Managers::with('managers_cargos')->findOrFail($id);
        return response()->json($manager);
    }

    public function update(Request $request, $id)
    {
        // Validación
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'mother_last_name' => 'sometimes|required|string|max:255',
            'abbreviated_title' => 'sometimes|required|string|max:10',
            'gender' => 'sometimes|required|string|max:1',
            'signature' => 'sometimes|required|string',
            'cargos' => 'sometimes|required|array',
            'cargos.*' => 'integer|exists:cargos,id',
        ]);

        // Encontrar manager y actualizar
        $manager = Managers::findOrFail($id);
        $manager->update($validatedData);

        // Actualizar cargos
        if (isset($validatedData['cargos'])) {
            $manager->managers_cargos()->sync($validatedData['cargos']);
        }

        return response()->json($manager);
    }

    public function destroy($id)
    {
        // Eliminar manager
        $manager = Managers::findOrFail($id);
        $manager->delete();

        return response()->json(null, 204);
    }
}

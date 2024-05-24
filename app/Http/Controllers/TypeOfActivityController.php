<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeOfActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TypeOfActivityController extends Controller
{
    public function index()
    {
        try {
            $typesOfActivities = TypeOfActivity::all();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Tipos de actividades obtenidos correctamente',
                'data' => $typesOfActivities
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener tipos de actividades: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status' => 422,
                'message' => 'Error de validación',
                'data' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $typeOfActivity = TypeOfActivity::create($request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Tipo de actividad creado correctamente',
                'data' => $typeOfActivity
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al crear tipo de actividad: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $typeOfActivity = TypeOfActivity::findOrFail($id);
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Tipo de actividad obtenido correctamente',
                'data' => $typeOfActivity
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Tipo de actividad no encontrado: ' . $e->getMessage(),
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status' => 422,
                'message' => 'Error de validación',
                'data' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $typeOfActivity = TypeOfActivity::findOrFail($id);
            $typeOfActivity->update($request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Tipo de actividad actualizado correctamente',
                'data' => $typeOfActivity
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al actualizar tipo de actividad: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $typeOfActivity = TypeOfActivity::findOrFail($id);
            $typeOfActivity->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Tipo de actividad eliminado correctamente',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al eliminar tipo de actividad: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}

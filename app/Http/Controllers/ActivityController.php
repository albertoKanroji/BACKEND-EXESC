<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    public function index()
    {
        try {
            $activities = Activity::with('typeOfActivity')->get();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Actividades obtenidas correctamente',
                'data' => $activities
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener actividades: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:45',
            'image' => 'nullable',
            'type_of_activities_id' => 'required|integer|exists:type_of_activities,id'
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
            $activity = Activity::create($request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Actividad creada correctamente',
                'data' => $activity->load('typeOfActivity')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al crear actividad: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $activity = Activity::with('typeOfActivity')->findOrFail($id);
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Actividad obtenida correctamente',
                'data' => $activity
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Actividad no encontrada: ' . $e->getMessage(),
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:45',
            'image' => 'nullable|longtext',
            'type_of_activities_id' => 'sometimes|required|integer|exists:type_of_activities,id'
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
            $activity = Activity::findOrFail($id);
            $activity->update($request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Actividad actualizada correctamente',
                'data' => $activity->load('typeOfActivity')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al actualizar actividad: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $activity = Activity::findOrFail($id);
            $activity->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Actividad eliminada correctamente',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al eliminar actividad: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
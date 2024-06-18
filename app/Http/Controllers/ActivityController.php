<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Group;

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
            $name = $request->input('name');
            $code = $this->generateCode($name);
            $activity = Activity::create([
                'name' => $request->input('name'),
                'image' => $request->input('image'),
                'type_of_activities_id' => $request->input('type_of_activities_id'),
                'code' => $code
            ]);
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
    private function generateCode($name)
    {
        // Convertir el nombre a un código base (por ejemplo, convertir a mayúsculas y tomar las primeras letras)
        $codeBase = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 3));

        // Obtener el último código similar de la base de datos
        $lastActivity = Activity::where('code', 'like', $codeBase . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($lastActivity) {
            // Extraer el número del último código y sumar 1
            $lastNumber = (int)substr($lastActivity->code, strlen($codeBase));
            $newNumber = $lastNumber + 1;
        } else {
            // Si no hay actividades con un código similar, comenzar con 1
            $newNumber = 1;
        }

        // Formatear el nuevo número a dos dígitos (01, 02, etc.)
        $newCode = $codeBase . str_pad($newNumber, 2, '0', STR_PAD_LEFT);

        return $newCode;
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

            'image' => 'nullable',
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
    public function getActivitiesWithParticipants()
    {
        try {
            $groups = Group::with('students')->get();

            $groupData = $groups->map(function ($group) {
                $totalParticipants = $group->students->count();
                $maleParticipants = $group->students->where('gender', 'male')->count();
                $femaleParticipants = $group->students->where('gender', 'female')->count();

                return [
                    'id' => $group->id,
                    'name' => $group->activity->name ?? 'N/A',
                    'total_participants' => $totalParticipants,
                    'male_participants' => $maleParticipants,
                    'female_participants' => $femaleParticipants,
                ];
            });

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Grupos obtenidos correctamente',
                'data' => $groupData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener los grupos: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
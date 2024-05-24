<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function index()
    {
        try {
            $groups = Group::with(['period', 'teacher', 'activity', 'typeOfGroup', 'schedules'])->get();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Grupos obtenidos correctamente',
                'data' => $groups
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener grupos: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quota_limit' => 'required|integer',
            'location' => 'required|string|max:255',
            'periods_id' => 'required|integer|exists:periods,id',
            'teachers_id' => 'required|integer|exists:teachers,id',
            'activities_id' => 'required|integer|exists:activities,id',
            'type_of_groups_id' => 'required|integer|exists:type_of_groups,id',
            'schedules' => 'required|array',
            'schedules.*' => 'integer|exists:schedules,id',
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
            $group = Group::create($request->all());
            $group->schedules()->sync($request->schedules);
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Grupo creado correctamente',
                'data' => $group->load(['period', 'teacher', 'activity', 'typeOfGroup', 'schedules'])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al crear grupo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $group = Group::with(['period', 'teacher', 'activity', 'typeOfGroup', 'schedules'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Grupo obtenido correctamente',
                'data' => $group
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Grupo no encontrado: ' . $e->getMessage(),
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quota_limit' => 'required|integer',
            'location' => 'required|string|max:255',
            'periods_id' => 'required|integer|exists:periods,id',
            'teachers_id' => 'required|integer|exists:teachers,id',
            'activities_id' => 'required|integer|exists:activities,id',
            'type_of_groups_id' => 'required|integer|exists:type_of_groups,id',
            'schedules' => 'required|array',
            'schedules.*' => 'integer|exists:schedules,id',
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
            $group = Group::findOrFail($id);
            $group->update($request->all());
            $group->schedules()->sync($request->schedules);
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Grupo actualizado correctamente',
                'data' => $group->load(['period', 'teacher', 'activity', 'typeOfGroup', 'schedules'])
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al actualizar grupo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $group = Group::findOrFail($id);
            $group->schedules()->detach();
            $group->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Grupo eliminado correctamente',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al eliminar grupo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}

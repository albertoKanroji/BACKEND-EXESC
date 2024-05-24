<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeOfGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TypeOfGroupController extends Controller
{
    public function index()
    {
        try {
            $typesOfGroups = TypeOfGroup::all();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Tipos de grupos obtenidos correctamente',
                'data' => $typesOfGroups
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener tipos de grupos: ' . $e->getMessage(),
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
            $typeOfGroup = TypeOfGroup::create($request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Tipo de grupo creado correctamente',
                'data' => $typeOfGroup
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al crear tipo de grupo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $typeOfGroup = TypeOfGroup::findOrFail($id);
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Tipo de grupo obtenido correctamente',
                'data' => $typeOfGroup
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Tipo de grupo no encontrado: ' . $e->getMessage(),
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
            $typeOfGroup = TypeOfGroup::findOrFail($id);
            $typeOfGroup->update($request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Tipo de grupo actualizado correctamente',
                'data' => $typeOfGroup
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al actualizar tipo de grupo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $typeOfGroup = TypeOfGroup::findOrFail($id);
            $typeOfGroup->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Tipo de grupo eliminado correctamente',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al eliminar tipo de grupo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}

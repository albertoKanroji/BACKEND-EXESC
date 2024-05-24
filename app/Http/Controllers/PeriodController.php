<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PeriodController extends Controller
{
    public function index()
    {
        try {
            $periods = Period::all();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Periodos obtenidos correctamente',
                'data' => $periods
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener periodos: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_period' => 'required|date',
            'end_period' => 'required|date',
            'period' => 'required|string|max:255',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date',
            'selectiv_start' => 'required|date',
            'selectiv_end' => 'required|date',
            'periodscol' => 'nullable|string|max:255',
            'status' => 'required|integer',
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
            $period = Period::create($request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Periodo creado correctamente',
                'data' => $period
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al crear periodo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $period = Period::findOrFail($id);
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Periodo obtenido correctamente',
                'data' => $period
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Periodo no encontrado: ' . $e->getMessage(),
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'start_period' => 'required|date',
            'end_period' => 'required|date',
            'period' => 'required|string|max:255',
            'registration_start' => 'required|date',
            'registration_end' => 'required|date',
            'selectiv_start' => 'required|date',
            'selectiv_end' => 'required|date',
            'periodscol' => 'nullable|string|max:255',
            'status' => 'required|integer',
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
            $period = Period::findOrFail($id);
            $period->update($request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Periodo actualizado correctamente',
                'data' => $period
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al actualizar periodo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $period = Period::findOrFail($id);
            $period->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Periodo eliminado correctamente',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al eliminar periodo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}

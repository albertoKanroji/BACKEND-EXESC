<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Response;

class SurveyResponseController extends Controller
{
    public function index()
    {
        try {
            $surveyResponses = SurveyResponse::with(['survey', 'response', 'teacher', 'student'])->get();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Respuestas de encuestas obtenidas correctamente',
                'data' => $surveyResponses
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener respuestas de encuestas: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surveys_id' => 'required|integer|exists:surveys,id',
            'responses' => 'required|array',
            'responses.*.question_id' => 'required|integer|exists:questions,id',
            'responses.*.option_id' => 'required|integer|exists:options,id',
            'teachers_id' => 'required|integer|exists:teachers,id',
            'students_id' => 'required|integer|exists:students,id',
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
            foreach ($request->responses as $response) {
                $surveyResponse = SurveyResponse::create([
                    'surveys_id' => $request->surveys_id,
                    'responses_id' => Response::create([
                        'options_id' => $response['option_id'],
                        'questions_id' => $response['question_id']
                    ])->id,
                    'teachers_id' => $request->teachers_id,
                    'students_id' => $request->students_id,
                ]);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Respuestas de encuesta enviadas correctamente',
                'data' => null
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al enviar respuestas de encuesta: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $surveyResponse = SurveyResponse::with(['survey', 'response', 'teacher', 'student'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Respuesta de encuesta obtenida correctamente',
                'data' => $surveyResponse
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Respuesta de encuesta no encontrada: ' . $e->getMessage(),
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'surveys_id' => 'required|integer|exists:surveys,id',
            'responses_id' => 'required|integer|exists:responses,id',
            'teachers_id' => 'required|integer|exists:teachers,id',
            'students_id' => 'required|integer|exists:students,id',
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
            $surveyResponse = SurveyResponse::findOrFail($id);
            $surveyResponse->update($request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Respuesta de encuesta actualizada correctamente',
                'data' => $surveyResponse->load(['survey', 'response', 'teacher', 'student'])
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al actualizar respuesta de encuesta: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $surveyResponse = SurveyResponse::findOrFail($id);
            $surveyResponse->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Respuesta de encuesta eliminada correctamente',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al eliminar respuesta de encuesta: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}

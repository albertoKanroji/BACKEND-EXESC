<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function index()
    {
        try {
            $questions = Question::with('survey')->get();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Preguntas obtenidas correctamente',
                'data' => $questions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener preguntas: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'surveys_id' => 'required|integer|exists:surveys,id',
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
            $question = Question::create($request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Pregunta creada correctamente',
                'data' => $question->load('survey')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al crear pregunta: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $question = Question::with('survey')->findOrFail($id);
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Pregunta obtenida correctamente',
                'data' => $question
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Pregunta no encontrada: ' . $e->getMessage(),
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'surveys_id' => 'required|integer|exists:surveys,id',
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
            $question = Question::findOrFail($id);
            $question->update($request->all());
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Pregunta actualizada correctamente',
                'data' => $question->load('survey')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al actualizar pregunta: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $question = Question::findOrFail($id);
            $question->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Pregunta eliminada correctamente',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al eliminar pregunta: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}

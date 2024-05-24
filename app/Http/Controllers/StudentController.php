<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        try {
            $students = Student::with('career')->get();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Estudiantes obtenidos correctamente',
                'data' => $students
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener estudiantes: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'username' => 'required|string|max:255|unique:students',
            'password' => 'required|string|min:6',
            'last_name' => 'required|string|max:255',
            'mother_last_name' => 'required|string|max:255',
            'control_number' => 'required|string|max:45|unique:students',
            'status' => 'required|integer',
            'profile_picture' => 'nullable',
            'phone' => 'nullable|string|max:10|unique:students',
            'profile' => 'nullable|string|max:45',
            'semester' => 'required|integer',
            'gender' => 'required|string|max:45',
            'careers_id' => 'required|integer|exists:careers,id',
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
            $data = $request->all();
            $data['password'] = Hash::make($request->password);
            $student = Student::create($data);
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Estudiante creado correctamente',
                'data' => $student->load('career')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al crear estudiante: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $student = Student::with('career')->findOrFail($id);
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Estudiante obtenido correctamente',
                'data' => $student
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Estudiante no encontrado: ' . $e->getMessage(),
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:students,email,' . $id,
            'username' => 'sometimes|required|string|max:255|unique:students,username,' . $id,
            'password' => 'sometimes|required|string|min:6',
            'last_name' => 'sometimes|required|string|max:255',
            'mother_last_name' => 'sometimes|required|string|max:255',
            'control_number' => 'sometimes|required|string|max:45|unique:students,control_number,' . $id,
            'status' => 'sometimes|required|integer',
            'profile_picture' => 'nullable|longtext',
            'phone' => 'nullable|string|max:10|unique:students,phone,' . $id,
            'profile' => 'nullable|string|max:45',
            'semester' => 'sometimes|required|integer',
            'gender' => 'sometimes|required|string|max:45',
            'careers_id' => 'sometimes|required|integer|exists:careers,id',
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
            $student = Student::findOrFail($id);
            $data = $request->all();

            if ($request->has('password')) {
                $data['password'] = Hash::make($request->password); // Encriptar la contraseña
            } else {
                unset($data['password']);
            }

            $student->update($data);
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Estudiante actualizado correctamente',
                'data' => $student->load('career')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al actualizar estudiante: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $student = Student::findOrFail($id);
            $student->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Estudiante eliminado correctamente',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al eliminar estudiante: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}

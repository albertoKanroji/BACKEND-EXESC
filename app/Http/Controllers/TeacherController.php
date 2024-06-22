<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        try {
            $teachers = Teacher::all();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Docentes obtenidos correctamente',
                'data' => $teachers
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener docentes: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mother_last_name' => 'required|string|max:255',
            'gender' => 'required|string|max:45',
            'abbreviated_title' => 'required|string|max:45',
            'curp' => 'required|string|max:45|unique:teachers',
            'rfc' => 'required|string|max:45|unique:teachers',
            'username' => 'required|string|max:45|unique:teachers',
            'password' => 'required|string|min:6',
            'email' => 'required|string|email|max:255|unique:teachers',
            'profile_picture' => 'nullable',
            'signature' => 'nullable',
            'profile' => 'nullable|string|max:45',
            'departments_id' => 'required|integer|exists:departments,id'
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
            $data['password'] = Hash::make($request->password); // Encriptar la contraseña
            $teacher = Teacher::create($data);
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Docente creado correctamente',
                'data' => $teacher
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al crear docente: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Docente obtenido correctamente',
                'data' => $teacher
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Docente no encontrado: ' . $e->getMessage(),
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'mother_last_name' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|string|max:45',
            'abbreviated_title' => 'sometimes|required|string|max:45',
            'curp' => 'sometimes|required|string|max:45|unique:teachers,curp,' . $id,
            'rfc' => 'sometimes|required|string|max:45|unique:teachers,rfc,' . $id,
            'username' => 'sometimes|required|string|max:45|unique:teachers,username,' . $id,
            'password' => 'sometimes|required|string|min:6',
            'email' => 'sometimes|required|string|email|max:255|unique:teachers,email,' . $id,
            'profile_picture' => 'nullable|longtext',
            'signature' => 'nullable|longtext',
            'profile' => 'nullable|string|max:45',
            'departments_id' => 'sometimes|required|integer|exists:departments,id'
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
            $teacher = Teacher::findOrFail($id);
            $data = $request->all();

            if ($request->has('password')) {
                $data['password'] = Hash::make($request->password); // Encriptar la contraseña
            } else {
                unset($data['password']);
            }

            $teacher->update($data);
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Docente actualizado correctamente',
                'data' => $teacher
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al actualizar docente: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Docente eliminado correctamente',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al eliminar docente: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status' => 422,
                'message' => 'Error de validación',
                'data' => $validator->errors()
            ], 422);
        }

        try {
            $cliente = Teacher::where('email', $request->email)->first();

            if (!$cliente) {
                return response()->json([
                    'success' => false,
                    'status' => 401,
                    'message' => 'Correo electrónico no encontrado',
                    'data' => null
                ], 401);
            }

            if ($cliente->status !== 1) {
                return response()->json([
                    'success' => false,
                    'status' => 403,
                    'message' => 'Usuario inactivo',
                    'data' => null
                ], 403);
            }

            if (!Hash::check($request->password, $cliente->password)) {
                return response()->json([
                    'success' => false,
                    'status' => 401,
                    'message' => 'Contraseña incorrecta',
                    'data' => null
                ], 401);
            }

            $token = $cliente->createToken('authToken')->plainTextToken;
            $modulos = $cliente->modulos()->get(['name', 'uid', 'path']);
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Inicio de sesión exitoso',
                'data' => [
                    'cliente' => $cliente,
                    'token' => $token,
                    'modulos' => $modulos
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al iniciar sesión: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }
}

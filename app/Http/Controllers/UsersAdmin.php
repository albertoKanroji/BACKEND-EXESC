<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersAdmin extends Controller
{
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
            $cliente = User::where('email', $request->email)->first();

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'string|min:6',

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
            $usuarioData = $request->all();
            $usuarioData['password'] = Hash::make($request->password);
            $usuario = User::create($usuarioData);
            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Usuario creado correctamente',
                'data' => $usuario
            ], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            // Error de la base de datos
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error en la base de datos al crear usuario: ' . $e->getMessage(),
                'data' => null
            ]);
        } catch (\Exception $e) {
            // Otro tipo de error
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al crear usuario: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:6',
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
            $usuario = User::findOrFail($id);
            $usuarioData = $request->only(['name', 'email', 'password']);

            if ($request->has('password')) {
                $usuarioData['password'] = Hash::make($request->password);
            } else {
                unset($usuarioData['password']);
            }

            $usuario->update($usuarioData);

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Usuario actualizado correctamente',
                'data' => $usuario
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al actualizar usuario: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $usuario = User::findOrFail($id);
            $usuario->delete();

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Usuario eliminado correctamente',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al eliminar usuario: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }

    public function deactivate($id)
    {
        try {
            $usuario = User::findOrFail($id);
            $usuario->update(['status' => 0]);

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Usuario desactivado correctamente',
                'data' => $usuario
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al desactivar usuario: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }
    public function reactivate($id)
    {
        try {
            $usuario = User::findOrFail($id);
            $usuario->update(['status' => 1]);

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Usuario reactivado correctamente',
                'data' => $usuario
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al reactivar usuario: ' . $e->getMessage(),
                'data' => null
            ]);
        }
    }

    public function show($id)
    {
        try {
            $usuario = User::findOrFail($id);
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Usuario encontrado',
                'data' => $usuario
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Usuario no encontrado: ' . $e->getMessage(),
                'data' => null
            ], 404);
        }
    }

    public function index()
    {
        try {
            $usuarios = User::all();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Usuarios encontrados',
                'data' => $usuarios
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener usuarios: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
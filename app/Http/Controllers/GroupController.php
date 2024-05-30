<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Period;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function index()
    {
        try {
            $groups = Group::with(['period', 'teacher', 'activity', 'typeOfGroup'])->get();
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
    public function getGroupByPeriodAndTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period_id' => 'required|integer|exists:periods,id',
            'teacher_id' => 'required|integer|exists:teachers,id',
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
            $periodId = $request->input('period_id');
            $teacherId = $request->input('teacher_id');

            $group = Group::with([
                'period',
                'teacher',
                'activity',
                'typeOfGroup',
            ])
                ->where('periods_id', $periodId)
                ->where('teachers_id', $teacherId)
                ->get();

            if ($group) {
                return response()->json([
                    'success' => true,
                    'status' => 200,
                    'message' => 'Grupo obtenido correctamente',
                    'data' => $group
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => 'Grupo no encontrado',
                    'data' => null
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener grupo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
    public function getStudentsByGroupId($groupId)
    {
        try {
            $group = Group::with('students')->find($groupId);

            if (!$group) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => 'No se encontró el grupo',
                    'data' => null
                ], 404);
            }

            $students = $group->students->map(function ($student) use ($group) {
                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'username' => $student->username,
                    'last_name' => $student->last_name,
                    'mother_last_name' => $student->mother_last_name,
                    'control_number' => $student->control_number,
                    'status' => $student->status,
                    'profile_picture' => $student->profile_picture,
                    'phone' => $student->phone,
                    'profile' => $student->profile,
                    'semester' => $student->semester,
                    'gender' => $student->gender,
                    'careers_id' => $student->careers_id,
                    'group_name' => $group->activity->name // Agregar el nombre del grupo
                ];
            });

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Estudiantes del grupo obtenidos correctamente',
                'data' => $students
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener estudiantes del grupo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
    public function getStudentsByPeriod($periodId)
    {
        try {
            $period = Period::with('groups.students.career')->find($periodId);

            if (!$period) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => 'No se encontró el periodo',
                    'data' => null
                ], 404);
            }

            $studentsByCareer = [];

            foreach ($period->groups as $group) {
                foreach ($group->students as $student) {
                    $careerName = $student->career->name;
                    if (!isset($studentsByCareer[$careerName])) {
                        $studentsByCareer[$careerName] = [
                            'career' => $careerName,
                            'students' => []
                        ];
                    }
                    $studentsByCareer[$careerName]['students'][] = [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'username' => $student->username,
                        'last_name' => $student->last_name,
                        'mother_last_name' => $student->mother_last_name,
                        'control_number' => $student->control_number,
                        'status' => $student->status,
                        'profile_picture' => $student->profile_picture,
                        'phone' => $student->phone,
                        'profile' => $student->profile,
                        'semester' => $student->semester,
                        'gender' => $student->gender,
                        'careers_id' => $student->careers_id,
                        'group_name' => $group->name // Agregar el nombre del grupo
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Estudiantes del periodo obtenidos correctamente',
                'data' => array_values($studentsByCareer)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener estudiantes del periodo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
    public function getStudentsByPeriodAndGroup(Request $request)
    {
        $periodId = $request->input('periodId');
        $groupId = $request->input('groupId');

        try {
            // Encuentra el periodo con los grupos, estudiantes y sus carreras
            $period = Period::with(['groups' => function ($query) use ($groupId) {
                if ($groupId) {
                    $query->where('id', $groupId);
                }
            }, 'groups.students.career'])->find($periodId);

            if (!$period) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => 'No se encontró el periodo',
                    'data' => null
                ], 404);
            }

            $studentsByCareer = [];

            foreach ($period->groups as $group) {
                foreach ($group->students as $student) {
                    $careerName = $student->career->name;
                    if (!isset($studentsByCareer[$careerName])) {
                        $studentsByCareer[$careerName] = [
                            'career' => $careerName,
                            'students' => []
                        ];
                    }
                    $studentsByCareer[$careerName]['students'][] = [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'username' => $student->username,
                        'last_name' => $student->last_name,
                        'mother_last_name' => $student->mother_last_name,
                        'control_number' => $student->control_number,
                        'status' => $student->status,
                        'profile_picture' => $student->profile_picture,
                        'phone' => $student->phone,
                        'profile' => $student->profile,
                        'semester' => $student->semester,
                        'gender' => $student->gender,
                        'careers_id' => $student->careers_id,
                        'group_name' => $group->name // Agregar el nombre del grupo
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Estudiantes del periodo obtenidos correctamente',
                'data' => array_values($studentsByCareer)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener estudiantes del periodo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
    public function getStudentsByPeriodFiltered(Request $request)
    {
        $periodId = $request->input('periodId');
        $filterBy = $request->input('filterBy');
        $filterId = $request->input('filterId');

        try {
            $period = Period::with(['groups.students.career', 'groups.activity'])->find($periodId);

            if (!$period) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => 'No se encontró el periodo',
                    'data' => null
                ], 404);
            }

            $studentsByCareer = [];

            foreach ($period->groups as $group) {
                foreach ($group->students as $student) {
                    $careerName = $student->career->name;

                    // Filtra por carrera si filterBy es 'career'
                    if ($filterBy === 'career' && $student->careers_id != $filterId) {
                        continue;
                    }

                    // Filtra por actividad si filterBy es 'activity'
                    if ($filterBy === 'activity' && $group->id != $filterId) {
                        continue;
                    }

                    if (!isset($studentsByCareer[$careerName])) {
                        $studentsByCareer[$careerName] = [
                            'career' => $careerName,
                            'students' => []
                        ];
                    }
                    $studentsByCareer[$careerName]['students'][] = [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'username' => $student->username,
                        'last_name' => $student->last_name,
                        'mother_last_name' => $student->mother_last_name,
                        'control_number' => $student->control_number,
                        'status' => $student->status,
                        'profile_picture' => $student->profile_picture,
                        'phone' => $student->phone,
                        'profile' => $student->profile,
                        'semester' => $student->semester,
                        'gender' => $student->gender,
                        'careers_id' => $student->careers_id,
                        'group_name' => $group->name, // Agregar el nombre del grupo
                        'activity_name' => $group->activity->name // Agregar el nombre de la actividad
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Estudiantes del periodo obtenidos correctamente',
                'data' => array_values($studentsByCareer)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Error al obtener estudiantes del periodo: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersAdmin;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TypeOfActivityController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TypeOfGroupController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\SurveyResponseController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    Route::prefix('users')->group(function () {
        Route::post('/login', [UsersAdmin::class, 'login']);
        Route::post('/create', [UsersAdmin::class, 'store']);
        Route::put('/update/{id}', [UsersAdmin::class, 'update']);
        Route::delete('/delete/{id}', [UsersAdmin::class, 'destroy']);
        Route::put('/deactivate/{id}', [UsersAdmin::class, 'deactivate']);
        Route::get('/{id}', [UsersAdmin::class, 'show']);
        Route::get('/', [UsersAdmin::class, 'index']);
        Route::put('/reactivate/{id}', [UsersAdmin::class, 'reactivate']);
    });
    Route::prefix('careers')->group(function () {
        Route::get('/', [CareerController::class, 'index']);
        Route::post('/', [CareerController::class, 'store']);
        Route::get('/{id}', [CareerController::class, 'show']);
        Route::put('/{id}', [CareerController::class, 'update']);
        Route::delete('/{id}', [CareerController::class, 'destroy']);
    });
    Route::prefix('departments')->group(function () {
        Route::get('/', [DepartmentController::class, 'index']);
        Route::post('/', [DepartmentController::class, 'store']);
        Route::get('/{id}', [DepartmentController::class, 'show']);
        Route::put('/{id}', [DepartmentController::class, 'update']);
        Route::delete('/{id}', [DepartmentController::class, 'destroy']);
    });
    Route::prefix('types-of-activities')->group(function () {
        Route::get('/', [TypeOfActivityController::class, 'index']);
        Route::post('/', [TypeOfActivityController::class, 'store']);
        Route::get('/{id}', [TypeOfActivityController::class, 'show']);
        Route::put('/{id}', [TypeOfActivityController::class, 'update']);
        Route::delete('/{id}', [TypeOfActivityController::class, 'destroy']);
    });
    Route::prefix('periods')->group(function () {
        Route::get('/', [PeriodController::class, 'index']);
        Route::post('/', [PeriodController::class, 'store']);
        Route::get('/{id}', [PeriodController::class, 'show']);
        Route::put('/{id}', [PeriodController::class, 'update']);
        Route::delete('/{id}', [PeriodController::class, 'destroy']);
    });
    Route::prefix('teachers')->group(function () {
        Route::get('/', [TeacherController::class, 'index']);
        Route::post('/', [TeacherController::class, 'store']);
        Route::get('/{id}', [TeacherController::class, 'show']);
        Route::put('/{id}', [TeacherController::class, 'update']);
        Route::delete('/{id}', [TeacherController::class, 'destroy']);
    });
    Route::prefix('types-of-groups')->group(function () {
        Route::get('/', [TypeOfGroupController::class, 'index']);
        Route::post('/', [TypeOfGroupController::class, 'store']);
        Route::get('/{id}', [TypeOfGroupController::class, 'show']);
        Route::put('/{id}', [TypeOfGroupController::class, 'update']);
        Route::delete('/{id}', [TypeOfGroupController::class, 'destroy']);
    });
    //hola mundo
    Route::prefix('groups')->group(function () {
        Route::get('/', [GroupController::class, 'index']);
        Route::post('/', [GroupController::class, 'store']);
        Route::get('/{id}', [GroupController::class, 'show']);
        Route::put('/{id}', [GroupController::class, 'update']);
        Route::delete('/{id}', [GroupController::class, 'destroy']);
        Route::post('/send-selection', [GroupController::class, 'getGroupByPeriodAndTeacher']);
        Route::get('/students-group/{groupId}', [GroupController::class, 'getStudentsByGroupId']);
        Route::get('/students-period/{periodId}', [GroupController::class, 'getStudentsByPeriod']);
        Route::post('/students-period/', [GroupController::class, 'getStudentsByPeriodAndGroup']);
        Route::post('/students-by-filter', [GroupController::class, 'getStudentsByPeriodFiltered']);
    });
    Route::prefix('students')->group(function () {
        Route::post('/login', [StudentController::class, 'login']);
        Route::get('/', [StudentController::class, 'index']);
        Route::post('/', [StudentController::class, 'store']);
        Route::get('/{id}', [StudentController::class, 'show']);
        Route::put('/{id}', [StudentController::class, 'update']);
        Route::delete('/{id}', [StudentController::class, 'destroy']);
    });
    Route::prefix('activities')->group(function () {
        Route::get('/', [ActivityController::class, 'index']);
        Route::post('/', [ActivityController::class, 'store']);
        Route::get('/{id}', [ActivityController::class, 'show']);
        Route::put('/{id}', [ActivityController::class, 'update']);
        Route::delete('/{id}', [ActivityController::class, 'destroy']);
    });
    Route::prefix('surveys')->group(function () {
        Route::get('/', [SurveyController::class, 'index']);
        Route::post('/', [SurveyController::class, 'store']);
        Route::get('/{id}', [SurveyController::class, 'show']);
        Route::put('/{id}', [SurveyController::class, 'update']);
        Route::delete('/{id}', [SurveyController::class, 'destroy']);
    });
    Route::prefix('questions')->group(function () {
        Route::get('/', [QuestionController::class, 'index']);
        Route::post('/', [QuestionController::class, 'store']);
        Route::get('/{id}', [QuestionController::class, 'show']);
        Route::put('/{id}', [QuestionController::class, 'update']);
        Route::delete('/{id}', [QuestionController::class, 'destroy']);
    });
    Route::prefix('options')->group(function () {
        Route::get('/', [OptionController::class, 'index']);
        Route::post('/', [OptionController::class, 'store']);
        Route::get('/{id}', [OptionController::class, 'show']);
        Route::put('/{id}', [OptionController::class, 'update']);
        Route::delete('/{id}', [OptionController::class, 'destroy']);
    });
    Route::prefix('survey-responses')->group(function () {
        Route::get('/', [SurveyResponseController::class, 'index']);
        Route::post('/', [SurveyResponseController::class, 'store']);
        Route::get('/{id}', [SurveyResponseController::class, 'show']);
        Route::put('/{id}', [SurveyResponseController::class, 'update']);
        Route::delete('/{id}', [SurveyResponseController::class, 'destroy']);
    });
});

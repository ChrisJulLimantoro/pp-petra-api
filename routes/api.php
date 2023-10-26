<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\AssistantPracticumController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PracticumController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPracticumController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RBAC\RoleController;
use App\Http\Controllers\RBAC\UserController;
use App\Http\Controllers\RBAC\UserRoleController;
use App\Http\Controllers\RBAC\RoleRoutesController;
use App\Http\Controllers\RoomController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('hydra', [HydraController::class, 'hydra']);
// Route::get('hydra/version', [HydraController::class, 'version']);

Route::apiResource('users', UserController::class)->except(['edit', 'create', 'store', 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::post('users', [UserController::class, 'store'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::match(['put','post','patch'],'users/{user}', [UserController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::get('me', [UserController::class, 'me'])->middleware('auth:sanctum');
Route::post('login', [UserController::class, 'login']);

Route::apiResource('roles', RoleController::class)->except(['create', 'edit'])->middleware(['auth:sanctum', 'ability:admin,super-admin,user']);
Route::apiResource('user-roles', UserRoleController::class)->except(['create','store' ,'edit', 'show', 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::post('user-roles/{user}', [UserRoleController::class, 'store'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::delete('users/{user}/roles/{role}', [UserRoleController::class, 'unassignRole'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
// equal as Get User By Id karena udah dapet role juga dri sana
// Route::get('user-roles/{user}', [UserRoleController::class, 'getByUser'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::apiResource('role-routes', RoleRoutesController::class)->except(['create', 'edit', 'show', 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::match(['put','post','patch'],'role-routes/{role_route}', [RoleRoutesController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);

// Rooms
Route::apiResource('rooms', RoomController::class)->except(['create', 'edit','update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::put('rooms/{room}', [RoomController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::patch('rooms/{room}', [RoomController::class, 'updatePartial'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::get('rooms-practicums',[RoomController::class,'getPracticum'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);

// Subjects
Route::apiResource('subjects', SubjectController::class)->except(['create', 'edit','update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::put('subjects/{subject}', [SubjectController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::patch('subjects/{subject}', [SubjectController::class, 'updatePartial'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);

// Practicums
Route::apiResource('practicums', PracticumController::class)->except(['create', 'edit','update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::put('practicums/{practicum}', [PracticumController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::patch('practicums/{practicum}', [PracticumController::class, 'updatePartial'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);

// Assistants
Route::apiResource('assistants', AssistantController::class)->except(['create', 'edit','update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::put('assistants/{assistant}', [AssistantController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::patch('assistants/{assistant}', [AssistantController::class, 'updatePartial'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);

// Assistant Practicums
Route::apiResource('assistant-practicums', AssistantPracticumController::class)->except(['create', 'edit','update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::put('assistant-practicums/{assistant_practicum}', [AssistantPracticumController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::patch('assistant-practicums/{assistant_practicum}', [AssistantPracticumController::class, 'updatePartial'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);

// Students
Route::apiResource('students', StudentController::class)->except(['create', 'edit','update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::put('students/{student}', [StudentController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::patch('students/{student}', [StudentController::class, 'updatePartial'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::get('students/{student}/available-schedules',[StudentController::class, 'getAvailableSchedule'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);

// Student Practicums
Route::apiResource('student-practicums', StudentPracticumController::class)->except(['create', 'edit','update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::put('student-practicums/{student_practicum}', [StudentPracticumController::class, 'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::patch('student-practicums/{student_practicum}', [StudentPracticumController::class, 'updatePartial'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::get('student-practicums/{student}/by-student',[StudentPracticumController::class, 'getByStudentId'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);

// Events
Route::apiResource('events', EventController::class)->except(['create', 'edit','update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::put('events/{event}', [EventController::class,'update'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
Route::patch('events/{event}',[EventController::class,'updatePartial'])->middleware(['auth:sanctum', 'ability:admin,super-admin']);
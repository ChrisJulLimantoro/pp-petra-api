<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RBAC\RoleController;
use App\Http\Controllers\RBAC\UserController;
use App\Http\Controllers\RBAC\UserRoleController;
use App\Http\Controllers\RBAC\RoleRoutesController;
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
Route::get('test', function(){
    return response()->json(['message' => 'Hello World!'], 200);
})->middleware(['auth:sanctum', 'ability:admin']);
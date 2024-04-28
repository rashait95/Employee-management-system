<?php

use function;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register',[AuthController::class,'register'])->name('register')->middleware(Authenticate::class);
Route::post('/login', [AuthController::class, 'login'])->name('login')->middleware(Authenticate::class);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware(Authenticate::class);
//Route::post('/logout',[AuthController::class],'logout');

Route::middleware('api')->group(function () {
Route::apiResource('departments',DepartmentController::class);
Route::apiResource('employees',EmployeeController::class);
Route::apiResource('/projects', ProjectController::class);



Route::get('/department-deleted', [DepartmentController::class, 'showSoftDeletedDepartments']);
Route::put('/department-restore/{department}', [DepartmentController::class, 'restore']);
Route::delete('/department-force-delete/{department}', [DepartmentController::class, 'forceDelete']);
});

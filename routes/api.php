<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;

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


Route::post('/register',[AuthController::class,'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware(Authenticate::class);
//Route::post('/logout',[AuthController::class],'logout');

Route::middleware('api')->group(function () {
Route::apiResource('departments',DepartmentController::class);
Route::apiResource('employees',EmployeeController::class);
Route::apiResource('projects', ProjectController::class);


Route::get('department-deleted', [DepartmentController::class, 'showSoftDeletedDepartments'])->name('department-deleted');
Route::put('department-restore/{department}', [DepartmentController::class, 'restore'])->name('department-restore');
Route::delete('department-force-delete/{department}', [DepartmentController::class, 'forceDelete'])->name('department-force-delete');

Route::get('employee-deleted', [EmployeeController::class, 'showSoftDeletedEmployees'])->name('employee-deleted');
Route::put('employee-restore/{employee}', [EmployeeController::class, 'restore'])->name('employee-restore');
Route::delete('employee-force-delete/{employee}', [EmployeeController::class, 'forceDelete'])->name('employee-force-delete');


Route::get('notes', [NoteController::class, 'index']);

Route::post('employee-note/{employee}', [NoteController::class, 'storeEmployeeNote'])->name('employee-note');
});

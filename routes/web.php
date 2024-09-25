<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/add', [EmployeeController::class, 'index']);
Route::post('/add', [EmployeeController::class, 'store']);
Route::get('/fetch_employees', [EmployeeController::class, 'fetchEmployees']);
Route::get('/edit-employee/{id}', [EmployeeController::class, 'edit']);
Route::post('/update-employee/{id}', [EmployeeController::class, 'update']);
Route::delete('/delete-employee/{id}', [EmployeeController::class, 'destroy']);




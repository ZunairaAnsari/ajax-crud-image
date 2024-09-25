<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/add', [EmployeeController::class, 'index']);
Route::post('/add', [EmployeeController::class, 'store']);
Route::get('/fetch_employees', [EmployeeController::class, 'fetchEmployees']);
Route::get('/edit-employees/{id}', [EmployeeController::class, 'edit']);




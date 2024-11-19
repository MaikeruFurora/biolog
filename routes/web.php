<?php

use App\Http\Controllers\BioLogController;
use App\Http\Controllers\EmployeeController;
use App\Models\Branch;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $branches = Branch::get('name');
    return view('welcome',compact('branches'));
});

Route::get('bio/list', [BioLogController::class, 'list'])->name('bio.log');
Route::get('bio/employee', [BioLogController::class, 'employee'])->name('bio.employee');
Route::post('bio/employee/store', [BioLogController::class, 'employeeStore'])->name('bio.employee.store');

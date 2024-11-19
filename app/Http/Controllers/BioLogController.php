<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BioLogController extends Controller
{
    public function list(Request $request){
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        return DB::table('employee_logs')
            ->whereDate('TimeStampLog', '>=', $dateFrom)
            ->whereDate('TimeStampLog', '<=', $dateTo)
            ->where('name',  $request->input('warehouse'))
            ->get();

    }

    public function employee(Request $request){
        return DB::table('employees')
        ->select(['fullname','enrolid'])
        ->join('branch', 'branch.srnum', '=', 'employees.srnum')
        ->where('branch.name', $request->input('warehouse'))
        ->orderBy('fullname', 'asc')
        ->get();
    }

    public function employeeStore(Request $request){
        
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'enrolid' => 'required|integer',
            'warehouse' => 'required',
        ]);

        $branch = DB::table('branch')
            ->where('name', $validatedData['warehouse'])
            ->first();

        if (!$branch) {
            return response()->json(['error' => 'Branch not found'], 404);
        }

        $employeeId = DB::table('employees')->insertGetId([
            'fullname' => $validatedData['fullname'],
            'enrolid' => $validatedData['enrolid'],
            'srnum' => $branch->srnum,
        ]);

        return response()->json(['success' => 'Employee stored successfully', 'employee_id' => $employeeId], 201);
    }
}

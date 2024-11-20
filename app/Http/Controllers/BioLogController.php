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
        ->select(['fullname','enrolid','employees.id'])
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

        if (!empty($request->input('id'))) {
            $employee = DB::table('employees')->where('id', $request->input('id'))->first();
            if (!$employee) {
                return response()->json(['error' => 'Employee not found'], 404);
            }

            DB::table('employees')->where('id', $request->input('id'))->update([
                'fullname' => $validatedData['fullname'],
                'enrolid' => $validatedData['enrolid'],
                'srnum' => $branch->srnum,
            ]);

            return response()->json(['success' => 'Employee updated successfully']);
        }

        $employeeId = DB::table('employees')->insertGetId([
            'fullname' => $validatedData['fullname'],
            'enrolid' => $validatedData['enrolid'],
            'srnum' => $branch->srnum,
        ]);

        return response()->json(['success' => 'Employee stored successfully', 'employee_id' => $employeeId], 201);
    }

    public function employeeDelete(Request $request){

        $employeeId = $request->input('id');

        if (!$employeeId) {
            return response()->json(['error' => 'Employee ID is required'], 400);
        }

        $employee = DB::table('employees')->where('id', $employeeId)->first();

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        DB::table('employees')->where('id', $employeeId)->delete();

        return response()->json(['success' => 'Employee deleted successfully']);
        
    }
}

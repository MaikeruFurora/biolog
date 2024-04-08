<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BioLogController extends Controller
{
    public function list(Request $request){
        $dateFrom  = $request->input('date_from');
        $dateTo    = $request->input('date_to');
        $warehouse = $request->input('warehouse');

        return DB::table('employee_logs')
            ->select(
            'employee_logs.enrolid',
            'employee_logs.fullname',
            DB::raw('MIN(CASE WHEN employee_logs.checklog = "Duty On" THEN TimeStampLog END) AS on_duty'),
            DB::raw('MAX(CASE WHEN employee_logs.checklog = "Duty Off" THEN TimeStampLog END) AS off_duty'),
            DB::raw('ABS(TIMESTAMPDIFF(HOUR, MIN(CASE WHEN employee_logs.checklog = "Duty On" THEN TimeStampLog END), MAX(CASE WHEN employee_logs.checklog = "Duty Off" THEN TimeStampLog END))) AS total_hours'),
            DB::raw('ABS(TIMESTAMPDIFF(DAY, MIN(CASE WHEN employee_logs.checklog = "Duty On" THEN TimeStampLog END), MAX(CASE WHEN employee_logs.checklog = "Duty Off" THEN TimeStampLog END))) AS total_days'),
            'employee_logs.name',)
            ->whereDate('TimeStampLog', '>=', $dateFrom)
            ->whereDate('TimeStampLog', '<=',  $dateTo)
            ->where('name', 'like', '%' . $warehouse . '%')
            ->groupBy(['employee_logs.enrolid','employee_logs.fullname','employee_logs.name'])
            ->orderBy('employee_logs.fullname','asc')
            ->get();


        // return DB::table('employee_logs')->whereDate('TimeStampLog', '=', '2024-03-07')->get();
            // return $request->all();
            // return DB::table('employee_logs')->get();
            // $draw = $request->input('draw');
            // $start = $request->input('start');
            // $length = $request->input('length');
            // $column = $request->input('columns');
            // $order = $request->input('order');
            // $search = $request->input('search');
            // $search = $search['value']; // for free text search
            
            // $recordsTotal = DB::table('employee_logs')->count();
            // $recordsFiltered = 0;
            // $data = array();

        

            //     // $query
            //     // ->orWhereBetween('TimeStampLog', [$request->input('date_from').' 00:00:00',$request->input('date_to').' 00:00:00'])
            //     // ->orWhere('name', 'like', '%' . $request->input('warehouse') . '%');


            //     // $query
            //             // ->whereBetween('TimeStampLog', [$request->input('date_from'), $request->input('date_to')])
            //             // ->orwhereDate('TimeStampLog', '=', $request->input('date_from'))
            //             // ->orWhere('name', 'like', '%' . $request->input('warehouse') . '%');
            
            // $recordsFiltered = $query->count();
            // $employees = $query->offset($start)->limit($length)->get();

            // if (count($employees) > 0) {
            //     foreach ($employees as $employee) {
            //         $data[] = array(
            //             'fullname' =>$employee->fullname,
            //             'name' =>$employee->name,
            //             'checklog' =>$employee->checklog,
            //             'TimeStampLog' =>$employee->TimeStampLog,
            //         );
            //     }
            // }
            // return response()->json([
            //     'draw' => $draw,
            //     'recordsTotal' => $recordsTotal,
            //     'recordsFiltered' => $recordsFiltered,
            //     'data' => $data,
        // ]);
    
    }
}

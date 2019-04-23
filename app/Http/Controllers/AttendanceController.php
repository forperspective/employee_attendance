<?php

namespace App\Http\Controllers;

use App\Attendance;
use Carbon\Carbon;
use http\Params;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class AttendanceController extends Controller
{
    public function saveInTime()
    {

        $attendance = new Attendance();
        $currentTime = Carbon::now();

        $attendance->employee_id = Auth::user()->id;
        $attendance->office_enter = Carbon::now();
        $saveStatus = $attendance->save();

        if ($saveStatus) {
            return response()->json($currentTime);
        }


    }

    public function saveOutTime()
    {

        $attendance = Attendance::whereDate('created_at', Carbon::today())
            ->where('employee_id', Auth::user()->id)->first();

        $attendance->office_out = Carbon::now();

        $attendance->save();

    }

    public function attendanceRecord(Request $request)
    {
        $yearMonth = $request->get('selectedMonth');
        $selectedUserId = $request->get('id');

        if($selectedUserId) {
            $id = $selectedUserId;
        } else {
            $id = Auth::user()->id;
        }
        $yearMonthArray = explode("-", $yearMonth);

        $year = $yearMonthArray[1];

        $month = $yearMonthArray[0];

        $attendances = Attendance::whereMonth('created_at', Carbon::createFromDate($year, $month)->month)
            ->where('employee_id', $id)->get();


        return response()->json($attendances);
    }

    public function checkAttendance() {
        $attendance = Attendance::whereDate('created_at', Carbon::today())->where('employee_id',Auth::user()->id)->first();
        $attendanceCount = $attendance->count();

        if($attendanceCount < 1) {
            return response()->json(['intime'=>false, 'outtime'=>false]);
        } else {
            if($attendance->office_enter && $attendance->office_out) {
                return response()->json(['intime'=>true, 'outtime'=>true]);
            } else if($attendance->office_enter) {
                return response()->json(['intime'=>true, 'outtime'=>false]);
            }
        }
}
}

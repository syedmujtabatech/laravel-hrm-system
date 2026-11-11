<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService
    ) {}

    public function index(Request $request)
    {
        $user = Auth::user();
        $todayAttendance = $this->attendanceService->getTodayRecord($user->id);

        if ($user->isAdmin()) {
            $query = Attendance::with('user')->latest('date');

            if ($request->filled('date')) {
                $query->where('date', $request->date);
            }

            $attendances = $query->paginate(20)->withQueryString();
            return view('attendance.index', compact('attendances', 'todayAttendance', 'user'));
        }

        // Employee ESS: Only their own attendance!
        $attendances = Attendance::where('user_id', $user->id)
            ->latest('date')
            ->paginate(20);

        return view('attendance.index', compact('attendances', 'todayAttendance', 'user'));
    }

    public function punch()
    {
        $user = Auth::user();
        $record = $this->attendanceService->punchClock($user);

        $msg = $record->check_out 
            ? "Punch Out recorded successfully at {$record->check_out}!"
            : "Punch In registered successfully at {$record->check_in}!";

        return back()->with('success', $msg);
    }
}

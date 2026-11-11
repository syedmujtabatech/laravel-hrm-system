<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\User;
use App\Services\AttendanceService;
use App\Services\LeaveService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService,
        protected LeaveService $leaveService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        $todayAttendance = $this->attendanceService->getTodayRecord($user->id);

        if ($user->isAdmin()) {
            $totalStaff = User::count();
            $activeStaff = User::where('status', 'Active')->count();
            $pendingLeaves = LeaveRequest::where('status', 'Pending')->count();
            $presentToday = Attendance::where('date', $today)->whereNotNull('check_in')->count();
            $recentStaff = User::latest()->take(6)->get();

            return view('dashboard.admin', compact(
                'user',
                'totalStaff',
                'activeStaff',
                'pendingLeaves',
                'presentToday',
                'recentStaff',
                'todayAttendance'
            ));
        }

        // Employee ESS View
        $leaveQuota = $this->leaveService->getQuota($user);
        $recentAttendances = Attendance::where('user_id', $user->id)->latest('date')->take(5)->get();
        $lastPayroll = Payroll::where('user_id', $user->id)->latest('month_year')->first();

        return view('dashboard.employee', compact(
            'user',
            'todayAttendance',
            'leaveQuota',
            'recentAttendances',
            'lastPayroll'
        ));
    }
}

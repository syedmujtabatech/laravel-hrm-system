<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollController extends Controller
{
    public function __construct(
        protected PayrollService $payrollService
    ) {}

    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $payrolls = Payroll::with('user')->latest('month_year')->paginate(20);
            $currentMonth = Carbon::now()->format('Y-m');
            $totalDisbursed = Payroll::where('payment_status', 'Paid')->sum('net_salary');

            return view('payroll.index', compact('payrolls', 'currentMonth', 'totalDisbursed', 'user'));
        }

        // Employee ESS: Only own salary records!
        $payrolls = Payroll::where('user_id', $user->id)
            ->latest('month_year')
            ->paginate(20);

        return view('payroll.index', compact('payrolls', 'user'));
    }

    public function disburse(Request $request)
    {
        $month = $request->input('month_year', Carbon::now()->format('Y-m'));
        $count = $this->payrollService->generateMonthlyPayroll($month);

        return back()->with('success', "Batch payroll for {$month} calculated and disbursed to {$count} active employees!");
    }

    public function slip(Payroll $payroll)
    {
        $user = Auth::user();

        // Security check: Employee can only view their own slip!
        if (!$user->isAdmin() && $payroll->user_id !== $user->id) {
            abort(403, 'Unauthorized access to confidential salary slip.');
        }

        $payroll->load('user.department');
        return view('payroll.slip', compact('payroll'));
    }
}

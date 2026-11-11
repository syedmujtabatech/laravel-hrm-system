<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Services\EmployeeService;
use App\Services\SmsNotificationService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService,
        protected SmsNotificationService $smsService
    ) {}

    public function index(Request $request)
    {
        $query = User::with('department')->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('employee_code', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('employment_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $employees = $query->paginate(15)->withQueryString();
        $departments = Department::all();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required|string|max:100',
            'employment_type' => 'required|in:FullTime,Contract,PartTime,Intern',
            'base_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
        ]);

        $employee = $this->employeeService->createEmployee($validated);

        return redirect()->route('employees.index')->with('success', "Employee {$employee->name} onboarded! Mobile SMS activation sent to {$employee->phone}.");
    }

    public function offboard(Request $request, User $user)
    {
        $validated = $request->validate([
            'exit_date' => 'required|date',
            'exit_reason' => 'required|string|max:255',
        ]);

        $this->employeeService->offboardEmployee($user, $validated);

        return redirect()->route('employees.index')->with('success', "Employee {$user->name} has been offboarded and status marked as Inactive. Login access revoked.");
    }

    public function resendSms(User $user)
    {
        $this->smsService->sendActivationSms($user);
        return redirect()->route('employees.index')->with('success', "SMS activation re-dispatched via Gateway to {$user->phone}!");
    }
}

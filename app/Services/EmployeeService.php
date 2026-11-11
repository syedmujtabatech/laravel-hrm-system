<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeService
{
    public function __construct(
        protected SmsNotificationService $smsService
    ) {}

    /**
     * Create a new employee in Pending_Activation state and send SMS.
     */
    public function createEmployee(array $data): User
    {
        // Generate next employee code
        $lastCode = User::whereNotNull('employee_code')->orderBy('id', 'desc')->value('employee_code');
        $nextNum = 101;
        if ($lastCode && preg_match('/EMP-(\d+)/', $lastCode, $matches)) {
            $nextNum = intval($matches[1]) + 1;
        }
        $employeeCode = "EMP-{$nextNum}";

        $name = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));

        $user = User::create([
            'name' => $name,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'role' => 'Employee',
            'employee_code' => $employeeCode,
            'department_id' => $data['department_id'] ?? null,
            'designation' => $data['designation'] ?? 'Staff Associate',
            'employment_type' => $data['employment_type'] ?? 'FullTime',
            'base_salary' => $data['base_salary'] ?? 0,
            'allowances' => $data['allowances'] ?? 0,
            'status' => 'Pending_Activation',
            'join_date' => now()->toDateString(),
            'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=256&q=80',
        ]);

        // Trigger SMS notification
        $this->smsService->sendActivationSms($user);

        return $user;
    }

    /**
     * Offboard employee (Mark inactive, set exit date & reason, revoke login).
     */
    public function offboardEmployee(User $user, array $data): bool
    {
        return $user->update([
            'status' => 'Inactive',
            'exit_date' => $data['exit_date'] ?? now()->toDateString(),
            'exit_reason' => $data['exit_reason'] ?? 'Separation / Resignation',
        ]);
    }

    /**
     * Activate employee via SMS Token and set new password.
     */
    public function activateAccount(string $token, string $password): User
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->update([
            'password' => Hash::make($password),
            'status' => 'Active',
            'activation_token' => null, // One-time token consumed
        ]);

        return $user;
    }
}

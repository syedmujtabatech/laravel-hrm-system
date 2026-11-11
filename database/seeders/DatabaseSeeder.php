<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Departments
        $deptEng = Department::create(['name' => 'Engineering & IT', 'code' => 'ENG', 'budget' => 4500000, 'description' => 'Software development & Cloud DevOps']);
        $deptDes = Department::create(['name' => 'Product Design', 'code' => 'DES', 'budget' => 1800000, 'description' => 'UI/UX & Brand identity']);
        $deptHr = Department::create(['name' => 'People & Culture', 'code' => 'HR', 'budget' => 1200000, 'description' => 'Talent acquisition & Employee engagement']);
        $deptFin = Department::create(['name' => 'Finance & Accounts', 'code' => 'FIN', 'budget' => 2200000, 'description' => 'Corporate accounts, taxation & payroll']);
        $deptMkt = Department::create(['name' => 'Growth & Marketing', 'code' => 'MKT', 'budget' => 1500000, 'description' => 'B2B Sales & Digital Marketing']);

        // 2. Users / Employees
        // Admin
        $admin = User::create([
            'name' => 'Sara Khan',
            'first_name' => 'Sara',
            'last_name' => 'Khan',
            'email' => 'admin@zenithhr.com',
            'phone' => '+92 300 0000000',
            'password' => Hash::make('admin123'),
            'role' => 'Admin',
            'employee_code' => 'EMP-001',
            'department_id' => $deptHr->id,
            'designation' => 'Chief Human Resources Officer (CHRO)',
            'employment_type' => 'FullTime',
            'base_salary' => 450000,
            'allowances' => 50000,
            'status' => 'Active',
            'join_date' => '2023-01-15',
            'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=256&q=80',
        ]);

        // Employee 1: Full-Time
        $zayd = User::create([
            'name' => 'Zayd Ahmed',
            'first_name' => 'Zayd',
            'last_name' => 'Ahmed',
            'email' => 'zayd@zenithhr.com',
            'phone' => '+92 300 1234567',
            'password' => Hash::make('password123'),
            'role' => 'Employee',
            'employee_code' => 'EMP-102',
            'department_id' => $deptEng->id,
            'designation' => 'Principal Full-Stack Engineer',
            'employment_type' => 'FullTime',
            'base_salary' => 280000,
            'allowances' => 25000,
            'status' => 'Active',
            'join_date' => '2024-03-01',
            'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=256&q=80',
        ]);

        // Employee 2: Contract
        $fatima = User::create([
            'name' => 'Fatima Zahra',
            'first_name' => 'Fatima',
            'last_name' => 'Zahra',
            'email' => 'fatima@zenithhr.com',
            'phone' => '+92 321 9876543',
            'password' => Hash::make('password123'),
            'role' => 'Employee',
            'employee_code' => 'EMP-103',
            'department_id' => $deptDes->id,
            'designation' => 'Lead UI/UX Product Designer',
            'employment_type' => 'Contract',
            'base_salary' => 195000,
            'allowances' => 15000,
            'status' => 'Active',
            'join_date' => '2024-06-15',
            'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=256&q=80',
        ]);

        // Employee 3: Part-Time
        $bilal = User::create([
            'name' => 'Bilal Tariq',
            'first_name' => 'Bilal',
            'last_name' => 'Tariq',
            'email' => 'bilal@zenithhr.com',
            'phone' => '+92 333 5566778',
            'password' => Hash::make('password123'),
            'role' => 'Employee',
            'employee_code' => 'EMP-104',
            'department_id' => $deptFin->id,
            'designation' => 'Payroll & Tax Analyst',
            'employment_type' => 'PartTime',
            'base_salary' => 125000,
            'allowances' => 10000,
            'status' => 'Active',
            'join_date' => '2024-09-01',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=256&q=80',
        ]);

        // Employee 4: Pending SMS Activation Intern
        $danyal = User::create([
            'name' => 'Danyal Raza',
            'first_name' => 'Danyal',
            'last_name' => 'Raza',
            'email' => 'danyal@zenithhr.com',
            'phone' => '+92 331 4455667',
            'password' => null, // Not set yet!
            'role' => 'Employee',
            'employee_code' => 'EMP-105',
            'department_id' => $deptEng->id,
            'designation' => 'Software Engineering Intern',
            'employment_type' => 'Intern',
            'base_salary' => 45000,
            'allowances' => 5000,
            'status' => 'Pending_Activation',
            'activation_token' => 'sms-token-danyal-9988',
            'join_date' => '2026-11-01',
            'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=256&q=80',
        ]);

        // Employee 5: Offboarded / Inactive Employee (For testing lockout!)
        $usman = User::create([
            'name' => 'Usman Ghani',
            'first_name' => 'Usman',
            'last_name' => 'Ghani',
            'email' => 'usman@zenithhr.com',
            'phone' => '+92 322 8899001',
            'password' => Hash::make('password123'),
            'role' => 'Employee',
            'employee_code' => 'EMP-106',
            'department_id' => $deptEng->id,
            'designation' => 'DevOps Engineer',
            'employment_type' => 'FullTime',
            'base_salary' => 210000,
            'allowances' => 20000,
            'status' => 'Inactive',
            'exit_date' => '2026-10-31',
            'exit_reason' => 'Resignation - Relocated to Dubai',
            'join_date' => '2023-08-01',
            'avatar' => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=256&q=80',
        ]);

        // 3. Attendances
        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        Attendance::create([
            'user_id' => $zayd->id,
            'date' => $today,
            'check_in' => '09:05:00',
            'check_out' => null,
            'work_hours' => 0,
            'status' => 'Present',
            'ip_address' => '192.168.1.45',
            'device' => 'Chrome / Windows',
        ]);

        Attendance::create([
            'user_id' => $fatima->id,
            'date' => $today,
            'check_in' => '09:12:00',
            'check_out' => null,
            'work_hours' => 0,
            'status' => 'Present',
            'ip_address' => '192.168.1.60',
            'device' => 'Edge / Windows',
        ]);

        Attendance::create([
            'user_id' => $zayd->id,
            'date' => $yesterday,
            'check_in' => '08:58:00',
            'check_out' => '17:35:00',
            'work_hours' => 8.6,
            'status' => 'Present',
            'ip_address' => '192.168.1.45',
            'device' => 'Chrome / Windows',
        ]);

        Attendance::create([
            'user_id' => $fatima->id,
            'date' => $yesterday,
            'check_in' => '09:45:00',
            'check_out' => '17:10:00',
            'work_hours' => 7.4,
            'status' => 'Late',
            'ip_address' => '192.168.1.60',
            'device' => 'Edge / Windows',
        ]);

        // 4. Leave Requests
        LeaveRequest::create([
            'user_id' => $zayd->id,
            'leave_type' => 'Annual',
            'start_date' => '2026-11-15',
            'end_date' => '2026-11-18',
            'days_count' => 4,
            'reason' => 'Family vacation in northern areas',
            'status' => 'Approved',
            'approved_by' => $admin->id,
        ]);

        LeaveRequest::create([
            'user_id' => $fatima->id,
            'leave_type' => 'Sick',
            'start_date' => '2026-11-10',
            'end_date' => '2026-11-11',
            'days_count' => 2,
            'reason' => 'Doctor appointment & medical rest',
            'status' => 'Pending',
        ]);

        // 5. Past Payroll Register (October 2026)
        Payroll::create([
            'user_id' => $zayd->id,
            'month_year' => '2026-10',
            'base_salary' => 280000,
            'allowances' => 25000,
            'gross_salary' => 305000,
            'tax_deduction' => 45750, // 15% bracket
            'eobi_deduction' => 1000,
            'unpaid_leave_deduction' => 0,
            'bonus' => 15000,
            'net_salary' => 273250,
            'payment_status' => 'Paid',
            'disbursed_at' => '2026-10-31 16:00:00',
        ]);

        Payroll::create([
            'user_id' => $fatima->id,
            'month_year' => '2026-10',
            'base_salary' => 195000,
            'allowances' => 15000,
            'gross_salary' => 210000,
            'tax_deduction' => 16800, // 8% bracket
            'eobi_deduction' => 1000,
            'unpaid_leave_deduction' => 0,
            'bonus' => 0,
            'net_salary' => 192200,
            'payment_status' => 'Paid',
            'disbursed_at' => '2026-10-31 16:00:00',
        ]);
    }
}

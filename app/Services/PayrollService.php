<?php

namespace App\Services;

use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;

class PayrollService
{
    /**
     * Calculate and disburse monthly payroll for all active employees.
     */
    public function generateMonthlyPayroll(string $monthYear): int
    {
        $activeEmployees = User::where('status', 'Active')->get();
        $count = 0;

        foreach ($activeEmployees as $emp) {
            $base = $emp->base_salary;
            $allowances = $emp->allowances;
            $gross = $base + $allowances;

            // Real-world tax calculation bracket
            $annualGross = $gross * 12;
            $taxRate = 0.0;
            if ($annualGross > 2400000) {
                $taxRate = 0.15;
            } elseif ($annualGross > 1200000) {
                $taxRate = 0.08;
            } elseif ($annualGross > 600000) {
                $taxRate = 0.025;
            }
            $taxDeduction = round(($gross * $taxRate), 2);
            $eobiDeduction = ($gross > 25000) ? 1000.00 : 0.00;
            $unpaidLeaveDeduction = 0.00; // Calculated if unpaid leaves approved
            $bonus = 0.00;

            $netSalary = $gross - $taxDeduction - $eobiDeduction - $unpaidLeaveDeduction + $bonus;

            Payroll::updateOrCreate(
                [
                    'user_id' => $emp->id,
                    'month_year' => $monthYear,
                ],
                [
                    'base_salary' => $base,
                    'allowances' => $allowances,
                    'gross_salary' => $gross,
                    'tax_deduction' => $taxDeduction,
                    'eobi_deduction' => $eobiDeduction,
                    'unpaid_leave_deduction' => $unpaidLeaveDeduction,
                    'bonus' => $bonus,
                    'net_salary' => $netSalary,
                    'payment_status' => 'Paid',
                    'disbursed_at' => Carbon::now(),
                ]
            );
            $count++;
        }

        return $count;
    }
}

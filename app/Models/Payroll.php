<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month_year',
        'base_salary',
        'allowances',
        'gross_salary',
        'tax_deduction',
        'eobi_deduction',
        'unpaid_leave_deduction',
        'bonus',
        'net_salary',
        'payment_status',
        'disbursed_at',
    ];

    protected function casts(): array
    {
        return [
            'base_salary' => 'decimal:2',
            'allowances' => 'decimal:2',
            'gross_salary' => 'decimal:2',
            'tax_deduction' => 'decimal:2',
            'eobi_deduction' => 'decimal:2',
            'unpaid_leave_deduction' => 'decimal:2',
            'bonus' => 'decimal:2',
            'net_salary' => 'decimal:2',
            'disbursed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

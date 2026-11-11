<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
        'employee_code',
        'department_id',
        'designation',
        'employment_type',
        'base_salary',
        'allowances',
        'status',
        'activation_token',
        'join_date',
        'exit_date',
        'exit_reason',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'base_salary' => 'decimal:2',
            'allowances' => 'decimal:2',
            'join_date' => 'date',
            'exit_date' => 'date',
        ];
    }

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    // Role & Status Checks
    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    public function isPendingActivation(): bool
    {
        return $this->status === 'Pending_Activation';
    }

    public function isOffboarded(): bool
    {
        return in_array($this->status, ['Inactive', 'Resigned', 'Terminated']);
    }

    // Type Badge Helper
    public function getTypeBadge(): array
    {
        return match ($this->employment_type) {
            'FullTime' => ['label' => 'Full-Time (Core)', 'color' => 'primary'],
            'Contract' => ['label' => 'Contractor (Milestone)', 'color' => 'dark'],
            'PartTime' => ['label' => 'Part-Time', 'color' => 'info'],
            'Intern' => ['label' => 'Intern (Stipend)', 'color' => 'secondary'],
            default => ['label' => 'Staff', 'color' => 'secondary'],
        };
    }
}

<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;

class LeaveService
{
    /**
     * Submit a leave application.
     */
    public function applyLeave(User $user, array $data): LeaveRequest
    {
        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);
        $daysCount = max(1, $start->diffInDays($end) + 1);

        return LeaveRequest::create([
            'user_id' => $user->id,
            'leave_type' => $data['leave_type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'days_count' => $daysCount,
            'reason' => $data['reason'] ?? '',
            'status' => 'Pending',
        ]);
    }

    public function updateStatus(LeaveRequest $leave, string $status, User $admin): bool
    {
        return $leave->update([
            'status' => $status,
            'approved_by' => $admin->id,
        ]);
    }

    public function getQuota(User $user): array
    {
        $approvedLeaves = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'Approved')
            ->get();

        $annualTaken = $approvedLeaves->where('leave_type', 'Annual')->sum('days_count');
        $sickTaken = $approvedLeaves->where('leave_type', 'Sick')->sum('days_count');
        $casualTaken = $approvedLeaves->where('leave_type', 'Casual')->sum('days_count');

        return [
            'annual' => ['total' => 14, 'used' => $annualTaken, 'balance' => max(0, 14 - $annualTaken)],
            'sick' => ['total' => 10, 'used' => $sickTaken, 'balance' => max(0, 10 - $sickTaken)],
            'casual' => ['total' => 10, 'used' => $casualTaken, 'balance' => max(0, 10 - $casualTaken)],
            'unpaid' => ['total' => 'Unlimited', 'used' => $approvedLeaves->where('leave_type', 'Unpaid')->sum('days_count'), 'balance' => '-'],
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Services\LeaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function __construct(
        protected LeaveService $leaveService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $quota = $this->leaveService->getQuota($user);

        if ($user->isAdmin()) {
            $leaves = LeaveRequest::with('user')->latest()->paginate(20);
            return view('leaves.index', compact('leaves', 'quota', 'user'));
        }

        // Employee ESS: Only own leaves!
        $leaves = LeaveRequest::where('user_id', $user->id)->latest()->paginate(20);
        return view('leaves.index', compact('leaves', 'quota', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|in:Annual,Sick,Casual,Unpaid',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
        ]);

        $this->leaveService->applyLeave(Auth::user(), $validated);

        return back()->with('success', 'Leave request submitted successfully for HR review!');
    }

    public function updateStatus(Request $request, LeaveRequest $leave)
    {
        $validated = $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        $this->leaveService->updateStatus($leave, $validated['status'], Auth::user());

        return back()->with('success', "Leave request #{$leave->id} has been marked as {$validated['status']}.");
    }
}

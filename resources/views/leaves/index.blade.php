@extends('layouts.app')

@section('title', 'Leave Management & Quota Balance')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Corporate Leave Management</h3>
        <p class="text-muted small mb-0">Policy-driven vacation, medical and casual leave workflow</p>
    </div>
    <div>
        <button class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#applyLeaveModal">
            <i class="fas fa-plus me-1"></i> Apply for Leave
        </button>
    </div>
</div>

<!-- Leave Quotas -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card p-3 border-0 shadow-sm bg-white border-start border-4 border-primary">
            <div class="d-flex justify-content-between">
                <span class="small fw-bold text-primary text-uppercase">Annual Leave</span>
                <span class="badge bg-primary">Paid</span>
            </div>
            <div class="h3 fw-bold text-dark my-2">{{ $quota['annual']['balance'] }} <span class="fs-6 text-muted font-normal">days left</span></div>
            <div class="small text-muted">Total: {{ $quota['annual']['total'] }} | Consumed: {{ $quota['annual']['used'] }}</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card p-3 border-0 shadow-sm bg-white border-start border-4 border-success">
            <div class="d-flex justify-content-between">
                <span class="small fw-bold text-success text-uppercase">Sick / Medical</span>
                <span class="badge bg-success">Paid</span>
            </div>
            <div class="h3 fw-bold text-dark my-2">{{ $quota['sick']['balance'] }} <span class="fs-6 text-muted font-normal">days left</span></div>
            <div class="small text-muted">Total: {{ $quota['sick']['total'] }} | Consumed: {{ $quota['sick']['used'] }}</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card p-3 border-0 shadow-sm bg-white border-start border-4 border-info">
            <div class="d-flex justify-content-between">
                <span class="small fw-bold text-info text-uppercase">Casual / Urgent</span>
                <span class="badge bg-info text-dark">Emergency</span>
            </div>
            <div class="h3 fw-bold text-dark my-2">{{ $quota['casual']['balance'] }} <span class="fs-6 text-muted font-normal">days left</span></div>
            <div class="small text-muted">Total: {{ $quota['casual']['total'] }} | Consumed: {{ $quota['casual']['used'] }}</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card p-3 border-0 shadow-sm bg-white border-start border-4 border-secondary">
            <div class="d-flex justify-content-between">
                <span class="small fw-bold text-secondary text-uppercase">Unpaid Leave</span>
                <span class="badge bg-secondary">Deductible</span>
            </div>
            <div class="h3 fw-bold text-dark my-2">{{ $quota['unpaid']['used'] }} <span class="fs-6 text-muted font-normal">days taken</span></div>
            <div class="small text-muted">Salary deducted per day</div>
        </div>
    </div>
</div>

<!-- Leave Requests Table -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-list-alt text-primary me-2"></i>{{ $user->isAdmin() ? 'Workforce Leave Requests' : 'My Leave Applications' }}</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    @if($user->isAdmin())
                        <th>Employee</th>
                    @endif
                    <th>Leave Type</th>
                    <th>Dates</th>
                    <th>Days</th>
                    <th>Reason</th>
                    <th>Status</th>
                    @if($user->isAdmin())
                        <th class="text-end">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                    <tr>
                        @if($user->isAdmin())
                            <td>
                                <div class="fw-bold text-dark">{{ $leave->user?->name }}</div>
                                <div class="small text-muted">{{ $leave->user?->employee_code }} • {{ $leave->user?->designation }}</div>
                            </td>
                        @endif
                        <td>
                            <span class="badge bg-{{ $leave->leave_type == 'Annual' ? 'primary' : ($leave->leave_type == 'Sick' ? 'success' : 'info text-dark') }}">
                                {{ $leave->leave_type }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $leave->start_date }} <span class="text-muted">to</span> {{ $leave->end_date }}</div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $leave->days_count }} days</span></td>
                        <td><span class="text-muted small">{{ $leave->reason }}</span></td>
                        <td>
                            <span class="badge bg-{{ $leave->status == 'Approved' ? 'success' : ($leave->status == 'Pending' ? 'warning text-dark' : 'danger') }}">
                                {{ $leave->status }}
                            </span>
                        </td>
                        @if($user->isAdmin())
                            <td class="text-end">
                                @if($leave->status == 'Pending')
                                    <form action="{{ route('leaves.status', $leave->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Approved">
                                        <button type="submit" class="btn btn-sm btn-success fw-bold me-1">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('leaves.status', $leave->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Rejected">
                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-bold">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Reviewed</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $user->isAdmin() ? 7 : 6 }}" class="text-center py-4 text-muted">No leave requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($leaves->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $leaves->links() }}
        </div>
    @endif
</div>

<!-- Apply Leave Modal -->
<div class="modal fade" id="applyLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="{{ route('leaves.apply') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-plane-departure me-2"></i> Apply for Leave</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Leave Policy Type *</label>
                        <select name="leave_type" class="form-select" required>
                            <option value="Annual">Annual Leave (Full Pay)</option>
                            <option value="Sick">Sick / Medical Leave (Full Pay)</option>
                            <option value="Casual">Casual / Urgent Leave (Full Pay)</option>
                            <option value="Unpaid">Unpaid Leave (Loss of Pay)</option>
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Start Date *</label>
                            <input type="date" name="start_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">End Date *</label>
                            <input type="date" name="end_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Reason / Work Handover Details *</label>
                        <textarea name="reason" rows="3" class="form-control" placeholder="Provide reason and who will cover your tasks during absence..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Employee Self-Service (ESS) Portal')

@section('content')
<!-- Employee Profile Header Banner -->
<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $user->avatar ?: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=256&q=80' }}" class="rounded-circle border border-3 border-primary shadow-sm" width="70" height="70" style="object-fit: cover;" />
                <div>
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="fw-bold mb-0 text-dark">{{ $user->name }}</h3>
                        <span class="badge bg-success">Active Staff</span>
                    </div>
                    <div class="text-primary fw-semibold">{{ $user->designation }} &bull; {{ $user->department?->name }}</div>
                    <div class="small text-muted mt-1">
                        <span class="me-3"><i class="fas fa-id-badge me-1"></i> {{ $user->employee_code }}</span>
                        <span class="me-3"><i class="fas fa-mobile-alt me-1"></i> {{ $user->phone }}</span>
                        <span><i class="fas fa-calendar-check me-1"></i> Joined: {{ $user->join_date?->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <span class="badge bg-{{ $user->getTypeBadge()['color'] }} fs-6 p-2">{{ $user->getTypeBadge()['label'] }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- 1. Biometric Punch Clock Terminal -->
    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fas fa-fingerprint text-primary me-2"></i>My Daily Attendance Clock</span>
                <span class="badge bg-light text-dark border">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-dark mb-1" id="digitalClock">{{ now()->format('h:i:s A') }}</div>
                <p class="small text-muted mb-4">Expected Shift: 09:00 AM - 06:00 PM</p>

                <div class="p-3 bg-light rounded border mb-4">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <div class="small text-muted">Punch In</div>
                            <div class="fw-bold text-success fs-5">{{ $todayAttendance?->check_in ?: '--:--' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="small text-muted">Punch Out</div>
                            <div class="fw-bold text-danger fs-5">{{ $todayAttendance?->check_out ?: '--:--' }}</div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('attendance.punch') }}" method="POST">
                    @csrf
                    @if(!$todayAttendance)
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">
                            <i class="fas fa-sign-in-alt me-2"></i> Punch In Today
                        </button>
                    @elseif($todayAttendance->check_in && !$todayAttendance->check_out)
                        <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold shadow-sm">
                            <i class="fas fa-sign-out-alt me-2"></i> Punch Out Today
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary btn-lg w-100 fw-bold" disabled>
                            <i class="fas fa-check-double me-2"></i> Day Completed ({{ $todayAttendance->work_hours }} hrs)
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- 2. My Leave Quota Cards -->
    <div class="col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fas fa-calendar-check text-primary me-2"></i>My Leave Balance (2026 Quota)</span>
                <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-outline-primary fw-bold">
                    <i class="fas fa-plus me-1"></i> Apply Leave
                </a>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 border rounded bg-light">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-dark">Annual Leaves</span>
                                <span class="badge bg-primary">Paid</span>
                            </div>
                            <div class="h3 fw-bold text-primary my-2">{{ $leaveQuota['annual']['balance'] }} <span class="fs-6 text-muted font-normal">days left</span></div>
                            <div class="small text-muted">Total: {{ $leaveQuota['annual']['total'] }} | Used: {{ $leaveQuota['annual']['used'] }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded bg-light">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-dark">Medical / Sick Leaves</span>
                                <span class="badge bg-success">Paid</span>
                            </div>
                            <div class="h3 fw-bold text-success my-2">{{ $leaveQuota['sick']['balance'] }} <span class="fs-6 text-muted font-normal">days left</span></div>
                            <div class="small text-muted">Total: {{ $leaveQuota['sick']['total'] }} | Used: {{ $leaveQuota['sick']['used'] }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded bg-light">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-dark">Casual Leaves</span>
                                <span class="badge bg-info text-dark">Emergency</span>
                            </div>
                            <div class="h3 fw-bold text-info my-2">{{ $leaveQuota['casual']['balance'] }} <span class="fs-6 text-muted font-normal">days left</span></div>
                            <div class="small text-muted">Total: {{ $leaveQuota['casual']['total'] }} | Used: {{ $leaveQuota['casual']['used'] }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded bg-light">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-dark">Unpaid Leaves</span>
                                <span class="badge bg-secondary">Deductible</span>
                            </div>
                            <div class="h3 fw-bold text-secondary my-2">{{ $leaveQuota['unpaid']['used'] }} <span class="fs-6 text-muted font-normal">days taken</span></div>
                            <div class="small text-muted">Salary deducted per day taken</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Attendance History -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fas fa-history text-primary me-2"></i>My Recent Punch Logs</span>
                <a href="{{ route('attendance.index') }}" class="small text-decoration-none fw-bold">Full Log &rarr;</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>In</th>
                                <th>Out</th>
                                <th>Hours</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAttendances as $att)
                                <tr>
                                    <td>{{ $att->date }}</td>
                                    <td class="text-success fw-bold">{{ $att->check_in ?: '-' }}</td>
                                    <td class="text-danger fw-bold">{{ $att->check_out ?: '-' }}</td>
                                    <td>{{ $att->work_hours ?: '0' }} hrs</td>
                                    <td><span class="badge bg-{{ $att->status == 'Present' ? 'success' : 'warning text-dark' }}">{{ $att->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-3 text-muted">No attendance logs yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Salary Slip Preview -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fas fa-receipt text-primary me-2"></i>Latest Confidential Pay Slip</span>
                <a href="{{ route('payroll.index') }}" class="small text-decoration-none fw-bold">All Slips &rarr;</a>
            </div>
            <div class="card-body p-4">
                @if($lastPayroll)
                    <div class="p-3 bg-light rounded border mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Payroll Period:</span>
                            <span class="fw-bold">{{ Carbon\Carbon::parse($lastPayroll->month_year . '-01')->format('F Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Gross Earnings:</span>
                            <span class="fw-bold text-dark">PKR {{ number_format($lastPayroll->gross_salary) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Deductions (Tax &amp; EOBI):</span>
                            <span class="fw-bold text-danger">- PKR {{ number_format($lastPayroll->tax_deduction + $lastPayroll->eobi_deduction) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold text-dark fs-5">Net Take-Home Pay:</span>
                            <span class="fw-bold text-success fs-5">PKR {{ number_format($lastPayroll->net_salary) }}</span>
                        </div>
                    </div>
                    <a href="{{ route('payroll.slip', $lastPayroll->id) }}" class="btn btn-outline-primary w-100 fw-bold">
                        <i class="fas fa-print me-1"></i> View &amp; Print Official Salary Slip
                    </a>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-file-invoice-dollar fa-3x mb-2 text-black-50"></i>
                        <p class="mb-0">No salary slip generated yet for current quarter.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    setInterval(() => {
        const el = document.getElementById('digitalClock');
        if (el) el.textContent = new Date().toLocaleTimeString();
    }, 1000);
</script>
@endsection

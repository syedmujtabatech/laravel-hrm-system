@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Executive Admin Command Center</h3>
        <p class="text-muted small mb-0">ZenithHR Enterprise • Workforce, Attendance &amp; Payroll Management</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('employees.index') }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="fas fa-user-plus me-1"></i> Onboard Employee
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm border-start border-4 border-primary p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="small fw-bold text-primary text-uppercase mb-1">Active Workforce</div>
                    <div class="h3 mb-0 fw-bold text-dark">{{ $activeStaff }} <span class="fs-6 text-muted font-normal">/ {{ $totalStaff }}</span></div>
                </div>
                <div class="rounded-circle bg-primary-subtle text-primary p-3">
                    <i class="fas fa-users fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm border-start border-4 border-success p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="small fw-bold text-success text-uppercase mb-1">Present Today</div>
                    <div class="h3 mb-0 fw-bold text-dark">{{ $presentToday }}</div>
                </div>
                <div class="rounded-circle bg-success-subtle text-success p-3">
                    <i class="fas fa-user-check fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm border-start border-4 border-warning p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="small fw-bold text-warning text-uppercase mb-1">Pending Leaves</div>
                    <div class="h3 mb-0 fw-bold text-dark">{{ $pendingLeaves }}</div>
                </div>
                <div class="rounded-circle bg-warning-subtle text-warning p-3">
                    <i class="fas fa-calendar-times fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm border-start border-4 border-info p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="small fw-bold text-info text-uppercase mb-1">System Health</div>
                    <div class="h5 mb-0 fw-bold text-success"><i class="fas fa-shield-alt me-1"></i> SOLID 100%</div>
                </div>
                <div class="rounded-circle bg-info-subtle text-info p-3">
                    <i class="fas fa-server fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Quick Punch Terminal -->
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fas fa-fingerprint text-primary me-2"></i>Biometric Attendance Clock</span>
                <span class="badge bg-light text-dark border">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="card-body text-center p-4">
                <div class="display-6 fw-bold text-dark mb-1" id="digitalClock">{{ now()->format('h:i:s A') }}</div>
                <p class="small text-muted mb-4">Official Work Hours: 09:00 AM - 06:00 PM</p>

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
                            <i class="fas fa-sign-in-alt me-2"></i> Punch In Now
                        </button>
                    @elseif($todayAttendance->check_in && !$todayAttendance->check_out)
                        <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold shadow-sm">
                            <i class="fas fa-sign-out-alt me-2"></i> Punch Out Now
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary btn-lg w-100 fw-bold" disabled>
                            <i class="fas fa-check-double me-2"></i> Shift Completed ({{ $todayAttendance->work_hours }} hrs)
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Recent Staff & Active Roster -->
    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fas fa-users text-primary me-2"></i>Workforce Directory Snapshot</span>
                <a href="{{ route('employees.index') }}" class="small text-decoration-none fw-bold">View All Staff &rarr;</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Staff Member</th>
                                <th>Department</th>
                                <th>Type</th>
                                <th>Monthly Gross</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentStaff as $emp)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $emp->avatar ?: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=256&q=80' }}" class="rounded-circle border" width="34" height="34" style="object-fit: cover;" />
                                            <div>
                                                <div class="fw-bold text-dark">{{ $emp->name }}</div>
                                                <div class="small text-muted">{{ $emp->employee_code }} • {{ $emp->designation }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $emp->department?->name ?: 'General' }}</td>
                                    <td><span class="badge bg-{{ $emp->getTypeBadge()['color'] }}">{{ $emp->getTypeBadge()['label'] }}</span></td>
                                    <td class="fw-bold">PKR {{ number_format($emp->base_salary + $emp->allowances) }}</td>
                                    <td>
                                        @if($emp->isOffboarded())
                                            <span class="badge bg-danger"><i class="fas fa-user-slash me-1"></i> Inactive</span>
                                        @elseif($emp->isPendingActivation())
                                            <span class="badge bg-warning text-dark"><i class="fas fa-sms me-1"></i> Pending SMS</span>
                                        @else
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Active</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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

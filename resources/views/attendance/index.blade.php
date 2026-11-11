@extends('layouts.app')

@section('title', 'Attendance & Time Tracking')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Biometric Attendance &amp; Punch Register</h3>
        <p class="text-muted small mb-0">Daily automated check-in/out logging and working hours audit</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Punch Terminal -->
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fas fa-fingerprint text-primary me-2"></i>Biometric Terminal</span>
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
                            <i class="fas fa-sign-in-alt me-2"></i> Punch In
                        </button>
                    @elseif($todayAttendance->check_in && !$todayAttendance->check_out)
                        <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold shadow-sm">
                            <i class="fas fa-sign-out-alt me-2"></i> Punch Out
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

    <!-- Attendance Audit Table -->
    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fas fa-clipboard-list text-primary me-2"></i>{{ $user->isAdmin() ? 'Workforce Daily Audit Log' : 'My Attendance History' }}</span>
                @if($user->isAdmin())
                    <form action="{{ route('attendance.index') }}" method="GET" class="d-flex gap-2">
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date', now()->format('Y-m-d')) }}">
                        <button type="submit" class="btn btn-sm btn-dark">Filter</button>
                    </form>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                @if($user->isAdmin())
                                    <th>Staff Member</th>
                                @endif
                                <th>Date</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Work Hours</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $att)
                                <tr>
                                    @if($user->isAdmin())
                                        <td>
                                            <div class="fw-bold text-dark">{{ $att->user?->name }}</div>
                                            <div class="small text-muted">{{ $att->user?->employee_code }}</div>
                                        </td>
                                    @endif
                                    <td>{{ $att->date }}</td>
                                    <td class="text-success fw-bold">{{ $att->check_in ?: '-' }}</td>
                                    <td class="text-danger fw-bold">{{ $att->check_out ?: '-' }}</td>
                                    <td>{{ $att->work_hours ? $att->work_hours . ' hrs' : '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $att->status == 'Present' ? 'success' : ($att->status == 'Late' ? 'warning text-dark' : 'danger') }}">
                                            {{ $att->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $user->isAdmin() ? 6 : 5 }}" class="text-center py-4 text-muted">No attendance logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($attendances->hasPages())
                <div class="card-footer bg-white py-3">
                    {{ $attendances->links() }}
                </div>
            @endif
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

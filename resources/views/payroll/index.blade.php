@extends('layouts.app')

@section('title', 'Payroll & Salary Register')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Corporate Payroll Register</h3>
        <p class="text-muted small mb-0">Statutory tax deductions, EOBI contributions, allowances and salary disbursements</p>
    </div>
    @if($user->isAdmin())
        <div>
            <form action="{{ route('payroll.disburse') }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="month_year" value="{{ now()->format('Y-m') }}">
                <button type="submit" class="btn btn-success fw-bold shadow-sm" onclick="return confirm('Execute batch payroll for all active employees for current month?')">
                    <i class="fas fa-coins me-1"></i> Disburse {{ now()->format('F Y') }} Payroll
                </button>
            </form>
        </div>
    @endif
</div>

<!-- Payroll Table -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-file-invoice-dollar text-primary me-2"></i>{{ $user->isAdmin() ? 'Monthly Payroll Disbursements' : 'My Salary Slips' }}</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    @if($user->isAdmin())
                        <th>Employee</th>
                    @endif
                    <th>Period</th>
                    <th>Base + Allowances</th>
                    <th>Gross Pay</th>
                    <th>Tax Deduction</th>
                    <th>EOBI</th>
                    <th>Net Take-Home</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrolls as $p)
                    <tr>
                        @if($user->isAdmin())
                            <td>
                                <div class="fw-bold text-dark">{{ $p->user?->name }}</div>
                                <div class="small text-muted">{{ $p->user?->employee_code }} • {{ $p->user?->designation }}</div>
                            </td>
                        @endif
                        <td>
                            <span class="badge bg-light text-dark border fw-bold">{{ Carbon\Carbon::parse($p->month_year . '-01')->format('M Y') }}</span>
                        </td>
                        <td>
                            <div>PKR {{ number_format($p->base_salary) }}</div>
                            <div class="small text-muted">+ PKR {{ number_format($p->allowances) }}</div>
                        </td>
                        <td class="fw-bold text-dark">PKR {{ number_format($p->gross_salary) }}</td>
                        <td class="text-danger">- PKR {{ number_format($p->tax_deduction) }}</td>
                        <td class="text-secondary">- PKR {{ number_format($p->eobi_deduction) }}</td>
                        <td class="fw-bold text-success fs-6">PKR {{ number_format($p->net_salary) }}</td>
                        <td>
                            <span class="badge bg-{{ $p->payment_status == 'Paid' ? 'success' : 'warning text-dark' }}">
                                <i class="fas fa-check-circle me-1"></i> {{ $p->payment_status }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('payroll.slip', $p->id) }}" class="btn btn-sm btn-outline-primary fw-bold">
                                <i class="fas fa-receipt me-1"></i> View Slip
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $user->isAdmin() ? 9 : 8 }}" class="text-center py-4 text-muted">No payroll records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payrolls->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $payrolls->links() }}
        </div>
    @endif
</div>
@endsection

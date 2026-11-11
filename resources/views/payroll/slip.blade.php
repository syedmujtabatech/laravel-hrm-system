@extends('layouts.app')

@section('title', 'Official Corporate Salary Slip')

@section('styles')
<style>
    @media print {
        .topbar, .sidebar, .btn-print, .btn-back {
            display: none !important;
        }
        .main-content {
            margin: 0 !important;
            padding: 0 !important;
        }
        .salary-slip-card {
            border: none !important;
            box-shadow: none !important;
        }
    }
    .salary-slip-card {
        max-width: 820px;
        margin: 20px auto;
        background: #fff;
    }
</style>
@endsection

@section('content')
<div class="salary-slip-card card shadow-lg border p-4 p-md-5">
    <!-- Action buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary btn-sm btn-back">
            <i class="fas fa-arrow-left me-1"></i> Back to Payroll Register
        </a>
        <button onclick="window.print()" class="btn btn-primary btn-sm fw-bold btn-print">
            <i class="fas fa-print me-1"></i> Print / Download PDF
        </button>
    </div>

    <!-- Header & Company Branding -->
    <div class="row align-items-center mb-4">
        <div class="col-sm-7">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="p-2 bg-primary text-white rounded fw-bold fs-4">ZH</span>
                <div>
                    <h4 class="fw-bold mb-0 text-dark">ZenithHR Global Enterprise</h4>
                    <span class="small text-muted">Corporate Workforce &amp; Payroll Solutions</span>
                </div>
            </div>
            <div class="small text-muted">
                Technology Park, Sector 4, Islamabad, Pakistan<br>
                NTN: 8829103-4 • STRN: 17-00-9922-001-19
            </div>
        </div>
        <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
            <h5 class="fw-bold text-primary mb-1">CONFIDENTIAL PAYSLIP</h5>
            <div class="badge bg-dark fs-6">{{ Carbon\Carbon::parse($payroll->month_year . '-01')->format('F Y') }}</div>
            <div class="small text-muted mt-1">Disbursed: {{ $payroll->disbursed_at?->format('d M Y') ?: 'Draft' }}</div>
        </div>
    </div>

    <!-- Employee Information Box -->
    <div class="card bg-light border p-3 mb-4">
        <div class="row g-2 small">
            <div class="col-sm-6"><strong>Employee Name:</strong> <span class="text-dark">{{ $payroll->user?->name }}</span></div>
            <div class="col-sm-6"><strong>Employee ID:</strong> <span class="text-dark">{{ $payroll->user?->employee_code }}</span></div>
            <div class="col-sm-6"><strong>Designation:</strong> <span class="text-dark">{{ $payroll->user?->designation }}</span></div>
            <div class="col-sm-6"><strong>Department:</strong> <span class="text-dark">{{ $payroll->user?->department?->name }}</span></div>
            <div class="col-sm-6"><strong>Registered Mobile:</strong> <span class="text-dark">{{ $payroll->user?->phone }}</span></div>
            <div class="col-sm-6"><strong>Bank / Account:</strong> <span class="text-dark">Habib Bank Ltd (HBL) - *****9012</span></div>
        </div>
    </div>

    <!-- Earnings & Deductions Tables -->
    <div class="row g-4 mb-4">
        <!-- Earnings -->
        <div class="col-sm-6">
            <div class="card border h-100">
                <div class="card-header bg-light py-2 fw-bold text-success">
                    <i class="fas fa-plus-circle me-1"></i> Earnings (Additions)
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <td class="ps-3 py-2">Basic Salary</td>
                                <td class="text-end pe-3 py-2 fw-semibold">PKR {{ number_format($payroll->base_salary) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-3 py-2">House Rent Allowance (40%)</td>
                                <td class="text-end pe-3 py-2">PKR {{ number_format($payroll->allowances * 0.6) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-3 py-2">Medical &amp; Utility Allowances</td>
                                <td class="text-end pe-3 py-2">PKR {{ number_format($payroll->allowances * 0.4) }}</td>
                            </tr>
                            @if($payroll->bonus > 0)
                                <tr>
                                    <td class="ps-3 py-2 text-primary">Performance Bonus</td>
                                    <td class="text-end pe-3 py-2 fw-bold text-primary">PKR {{ number_format($payroll->bonus) }}</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th class="ps-3 py-2">Gross Earnings</th>
                                <th class="text-end pe-3 py-2 text-dark">PKR {{ number_format($payroll->gross_salary + $payroll->bonus) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Deductions -->
        <div class="col-sm-6">
            <div class="card border h-100">
                <div class="card-header bg-light py-2 fw-bold text-danger">
                    <i class="fas fa-minus-circle me-1"></i> Statutory Deductions
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <td class="ps-3 py-2">Income Tax (FBR Sec 149)</td>
                                <td class="text-end pe-3 py-2 text-danger fw-semibold">PKR {{ number_format($payroll->tax_deduction) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-3 py-2">EOBI Contribution</td>
                                <td class="text-end pe-3 py-2 text-secondary">PKR {{ number_format($payroll->eobi_deduction) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-3 py-2">Unpaid Leave Deductions</td>
                                <td class="text-end pe-3 py-2 text-muted">PKR {{ number_format($payroll->unpaid_leave_deduction) }}</td>
                            </tr>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th class="ps-3 py-2">Total Deductions</th>
                                <th class="text-end pe-3 py-2 text-danger">PKR {{ number_format($payroll->tax_deduction + $payroll->eobi_deduction + $payroll->unpaid_leave_deduction) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Net Pay Callout Banner -->
    <div class="card bg-primary-subtle border-primary p-3 mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="small fw-bold text-primary text-uppercase">Net Take-Home Pay (Disbursed to Bank):</span>
                <div class="h2 fw-bold text-primary mb-0">PKR {{ number_format($payroll->net_salary) }}</div>
            </div>
            <div class="text-end">
                <span class="badge bg-success p-2 fs-6"><i class="fas fa-check-circle me-1"></i> Transferred to Account</span>
            </div>
        </div>
    </div>

    <!-- Signatures Footer -->
    <div class="row pt-5 mt-4 text-center">
        <div class="col-4">
            <div class="border-top pt-2 small text-muted">Employee Signature</div>
        </div>
        <div class="col-4">
            <div class="border-top pt-2 small text-muted">HR Director Approval</div>
        </div>
        <div class="col-4">
            <div class="border-top pt-2 small text-muted">Finance Controller &amp; Stamp</div>
        </div>
    </div>
</div>
@endsection

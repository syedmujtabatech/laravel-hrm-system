@extends('layouts.app')

@section('title', 'Staff Directory & Workforce Management')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Corporate Staff Directory</h3>
        <p class="text-muted small mb-0">Total {{ $employees->total() }} employees registered in company directory</p>
    </div>
    <div>
        <button class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#onboardModal">
            <i class="fas fa-user-plus me-1"></i> Onboard New Employee
        </button>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card shadow-sm border-0 mb-4 bg-white">
    <div class="card-body p-3">
        <form action="{{ route('employees.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by name, employee code, phone..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">All Employment Types</option>
                    <option value="FullTime" {{ request('type') == 'FullTime' ? 'selected' : '' }}>Full-Time (Core)</option>
                    <option value="Contract" {{ request('type') == 'Contract' ? 'selected' : '' }}>Contractor</option>
                    <option value="PartTime" {{ request('type') == 'PartTime' ? 'selected' : '' }}>Part-Time</option>
                    <option value="Intern" {{ request('type') == 'Intern' ? 'selected' : '' }}>Intern</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Pending_Activation" {{ request('status') == 'Pending_Activation' ? 'selected' : '' }}>Pending SMS Setup</option>
                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive / Resigned</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-bold">Filter</button>
                @if(request()->hasAny(['search', 'type', 'status']))
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Staff Table -->
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Staff Member</th>
                    <th>Designation &amp; Dept</th>
                    <th>Type</th>
                    <th>Base + Allowances</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $emp)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $emp->avatar ?: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=256&q=80' }}" class="rounded-circle border" width="42" height="42" style="object-fit: cover;" />
                                <div>
                                    <div class="fw-bold text-primary">{{ $emp->name }}</div>
                                    <div class="small text-muted">{{ $emp->employee_code }} • {{ $emp->email }}</div>
                                    <div class="small text-muted"><i class="fas fa-mobile-alt me-1"></i>{{ $emp->phone }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $emp->designation }}</div>
                            <div class="small text-muted">{{ $emp->department?->name ?: 'General' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $emp->getTypeBadge()['color'] }}">{{ $emp->getTypeBadge()['label'] }}</span>
                        </td>
                        <td>
                            <div class="fw-bold">PKR {{ number_format($emp->base_salary) }}</div>
                            <div class="small text-muted">+ PKR {{ number_format($emp->allowances) }} allow.</div>
                        </td>
                        <td>
                            @if($emp->isOffboarded())
                                <span class="badge bg-danger"><i class="fas fa-user-slash me-1"></i> Inactive</span>
                                <div class="small text-danger fw-semibold" style="font-size: 0.7rem;">Exit: {{ $emp->exit_date?->format('Y-m-d') ?: 'Recent' }}</div>
                            @elseif($emp->isPendingActivation())
                                <span class="badge bg-warning text-dark"><i class="fas fa-sms me-1"></i> Pending SMS Setup</span>
                            @else
                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Active</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <!-- Dossier Modal Trigger -->
                            <button type="button" class="btn btn-sm btn-outline-info me-1" data-bs-toggle="modal" data-bs-target="#dossierModal{{ $emp->id }}" title="View 360 Dossier">
                                <i class="fas fa-id-card"></i> Dossier
                            </button>

                            <!-- Offboard Modal Trigger -->
                            @if(!$emp->isOffboarded())
                                <button type="button" class="btn btn-sm btn-outline-danger me-1" data-bs-toggle="modal" data-bs-target="#offboardModal{{ $emp->id }}" title="Offboard / Deactivate Employee">
                                    <i class="fas fa-user-slash me-1"></i> Offboard
                                </button>
                            @else
                                <button type="button" class="btn btn-sm btn-secondary disabled me-1" title="Already Deactivated">
                                    <i class="fas fa-ban me-1"></i> Inactive
                                </button>
                            @endif

                            @if($emp->isPendingActivation())
                                <form action="{{ route('employees.resend-sms', $emp->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Resend SMS Invitation">
                                        <i class="fas fa-sms"></i> SMS
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>

                    <!-- 360 Dossier Modal -->
                    <div class="modal fade" id="dossierModal{{ $emp->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-dark text-white">
                                    <h5 class="modal-title fw-bold"><i class="fas fa-id-card me-2"></i> Employee Dossier</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded mb-3">
                                        <img src="{{ $emp->avatar ?: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=256&q=80' }}" class="rounded-circle border" width="60" height="60" style="object-fit: cover;" />
                                        <div>
                                            <h5 class="fw-bold mb-0 text-dark">{{ $emp->name }}</h5>
                                            <div class="text-primary fw-semibold">{{ $emp->designation }}</div>
                                            <div class="small text-muted">{{ $emp->department?->name }} • {{ $emp->employee_code }}</div>
                                        </div>
                                    </div>
                                    <div class="row g-2 small">
                                        <div class="col-6 p-2 border rounded"><span class="text-muted">Mobile:</span> <div class="fw-bold">{{ $emp->phone }}</div></div>
                                        <div class="col-6 p-2 border rounded"><span class="text-muted">Email:</span> <div class="fw-bold">{{ $emp->email ?: 'N/A' }}</div></div>
                                        <div class="col-6 p-2 border rounded"><span class="text-muted">Joined:</span> <div class="fw-bold">{{ $emp->join_date?->format('d M Y') ?: 'Recent' }}</div></div>
                                        <div class="col-6 p-2 border rounded"><span class="text-muted">Contract Subtype:</span> <div class="fw-bold text-primary">{{ $emp->employment_type }}</div></div>
                                        <div class="col-6 p-2 border rounded"><span class="text-muted">Base Salary:</span> <div class="fw-bold text-success">PKR {{ number_format($emp->base_salary) }}</div></div>
                                        <div class="col-6 p-2 border rounded"><span class="text-muted">Allowances:</span> <div class="fw-bold text-success">PKR {{ number_format($emp->allowances) }}</div></div>
                                        @if($emp->isOffboarded())
                                            <div class="col-12 p-2 border border-danger bg-danger-subtle rounded text-danger">
                                                <strong>Deactivation Details:</strong>
                                                <div>Exit Date: {{ $emp->exit_date?->format('d M Y') }}</div>
                                                <div>Reason: {{ $emp->exit_reason }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Offboard Modal -->
                    @if(!$emp->isOffboarded())
                        <div class="modal fade" id="offboardModal{{ $emp->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <form action="{{ route('employees.offboard', $emp->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title fw-bold"><i class="fas fa-user-slash me-2"></i> Offboard Employee</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <p class="mb-3">Are you sure you want to mark <strong>{{ $emp->name }} ({{ $emp->employee_code }})</strong> as <strong>Inactive</strong>? Their login access will be immediately blocked.</p>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Effective Exit Date:</label>
                                                <input type="date" name="exit_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Separation Reason / Notes:</label>
                                                <select name="exit_reason" class="form-select mb-2" required>
                                                    <option value="Resignation - Voluntary separation">Resignation - Voluntary separation</option>
                                                    <option value="Contract Completion - Project ended">Contract Completion - Project ended</option>
                                                    <option value="Relocation / Better Opportunity">Relocation / Better Opportunity</option>
                                                    <option value="Termination - Policy violation">Termination - Policy violation</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger fw-bold">Confirm Deactivation</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No staff members found matching search query.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($employees->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $employees->links() }}
        </div>
    @endif
</div>

<!-- Onboard Employee Modal -->
<div class="modal fade" id="onboardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-user-plus me-2"></i> Onboard New Staff Member</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 small mb-4">
                        <i class="fas fa-info-circle me-1"></i> Once onboarded, the system dispatches an <strong>SMS activation link directly to the employee's mobile phone</strong> via the SMS Gateway so they can activate their account and set their password!
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">First Name *</label>
                            <input type="text" name="first_name" class="form-control" placeholder="e.g. Hamza" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" placeholder="e.g. Farooq" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Registered Mobile (For SMS Invite) *</label>
                            <input type="text" name="phone" class="form-control" placeholder="+92 300 1122334" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="hamza@zenithhr.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Department *</label>
                            <select name="department_id" class="form-select" required>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Designation *</label>
                            <input type="text" name="designation" class="form-control" placeholder="e.g. Senior Software Engineer" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Employment Subtype *</label>
                            <select name="employment_type" class="form-select" required>
                                <option value="FullTime">Full-Time (Core)</option>
                                <option value="Contract">Contractor</option>
                                <option value="PartTime">Part-Time</option>
                                <option value="Intern">Intern (Stipend)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Base Monthly Salary (PKR) *</label>
                            <input type="number" name="base_salary" class="form-control" placeholder="180000" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Monthly Allowances (PKR)</label>
                            <input type="number" name="allowances" class="form-control" placeholder="20000" min="0" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="fas fa-paper-plane me-1"></i> Save &amp; Dispatch SMS Invite
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

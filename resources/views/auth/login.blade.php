@extends('layouts.app')

@section('title', 'Sign In')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0284c7 100%);
        min-height: 100vh;
    }
    .login-container {
        max-width: 520px;
        margin: 50px auto;
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="card shadow-lg border-0 overflow-hidden">
        <div class="card-header bg-primary text-white p-4 text-center">
            <div class="d-inline-flex p-3 bg-white text-primary rounded-circle mb-2 shadow-sm">
                <i class="fas fa-users-cog fa-2x"></i>
            </div>
            <h3 class="fw-bold mb-1">ZenithHR Enterprise</h3>
            <p class="small mb-0 text-white-50">Workforce &amp; Employee Self-Service (ESS) Portal</p>
        </div>
        <div class="card-body p-4 p-md-5 bg-white">
            @if($errors->has('login'))
                <div class="alert alert-danger fw-semibold mb-4 shadow-sm" role="alert">
                    <i class="fas fa-ban me-2"></i> {{ $errors->first('login') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <h5 class="fw-bold mb-1 text-dark">Sign In to Your Workspace</h5>
                <p class="small text-muted mb-4">Enter registered Mobile Number (or Email) &amp; Password</p>

                <div class="form-floating mb-3">
                    <input type="text" name="identifier" class="form-control @error('identifier') is-invalid @enderror" id="floatingIdentifier" placeholder="+92 300 1234567" value="{{ old('identifier', '+92 300 1234567') }}" required>
                    <label for="floatingIdentifier"><i class="fas fa-mobile-alt me-1 text-primary"></i> Registered Mobile / Email</label>
                    @error('identifier')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-4">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="floatingPassword" placeholder="Password" value="password123" required>
                    <label for="floatingPassword"><i class="fas fa-lock me-1 text-primary"></i> Password</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold shadow">
                        <i class="fas fa-sign-in-alt me-2"></i> Sign In to Portal
                    </button>
                </div>
            </form>

            <!-- 1-Click Role & Security Testing Bar -->
            <div class="p-3 bg-light rounded border">
                <div class="small fw-bold text-uppercase text-muted mb-2">
                    <i class="fas fa-bolt text-warning me-1"></i> 1-Click Role &amp; Security Testing Bar:
                </div>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <a href="{{ route('quick.login', 'admin') }}" class="btn btn-sm btn-outline-danger fw-bold">
                        👑 HR Admin (Sara Khan)
                    </a>
                    <a href="{{ route('quick.login', 'zayd') }}" class="btn btn-sm btn-outline-primary fw-bold">
                        💼 Zayd Ahmed (Employee ESS)
                    </a>
                    <a href="{{ route('quick.login', 'fatima') }}" class="btn btn-sm btn-outline-info fw-bold">
                        🎨 Fatima Zahra (Contractor ESS)
                    </a>
                    <a href="{{ route('quick.login', 'inactive') }}" class="btn btn-sm btn-outline-secondary fw-bold text-danger border-danger">
                        🚫 Usman Ghani (Offboarded / Inactive)
                    </a>
                </div>
                <div class="small text-muted" style="font-size: 0.75rem;">
                    <strong>Tip:</strong> Click <em>Usman Ghani</em> to see how the system immediately blocks inactive employees who have resigned!
                </div>
            </div>
        </div>
        <div class="card-footer text-center py-3 bg-light border-top">
            <div class="small text-muted">
                Need to set password via SMS? <a href="{{ route('activate.show') }}" class="fw-bold text-decoration-none text-primary">Account Activation Page</a>
            </div>
        </div>
    </div>
</div>
@endsection

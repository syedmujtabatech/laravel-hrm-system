@extends('layouts.app')

@section('title', 'Account Activation & Password Setup')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0284c7 100%);
        min-height: 100vh;
    }
    .activate-container {
        max-width: 540px;
        margin: 50px auto;
    }
</style>
@endsection

@section('content')
<div class="activate-container">
    <div class="card shadow-lg border-0 overflow-hidden">
        <div class="card-header bg-primary text-white p-4 text-center">
            <div class="d-inline-flex p-3 bg-white text-primary rounded-circle mb-2 shadow-sm">
                <i class="fas fa-key fa-2x"></i>
            </div>
            <h3 class="fw-bold mb-1">Employee Account Activation</h3>
            <p class="small mb-0 text-white-50">Set up your password via Mobile SMS verification</p>
        </div>
        <div class="card-body p-4 p-md-5 bg-white">
            @if($errors->any())
                <div class="alert alert-danger fw-semibold mb-4 shadow-sm" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($user)
                <!-- Employee Info Box -->
                <div class="p-3 bg-light rounded mb-4 border">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold text-dark fs-5">{{ $user->name }}</span>
                        <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Pending Activation</span>
                    </div>
                    <div class="small text-muted">{{ $user->employee_code }} • {{ $user->designation }}</div>
                    <div class="small text-primary mt-2">
                        <i class="fas fa-mobile-alt me-1"></i> Verified Mobile: <strong>{{ $user->phone }}</strong>
                    </div>
                </div>

                <form action="{{ route('activate.save') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Create password" required minlength="6">
                        <label for="floatingPassword"><i class="fas fa-lock me-1 text-primary"></i> Create New Password</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" name="password_confirmation" class="form-control" id="floatingConfirmPassword" placeholder="Confirm password" required minlength="6">
                        <label for="floatingConfirmPassword"><i class="fas fa-check-double me-1 text-primary"></i> Confirm Password</label>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow">
                            <i class="fas fa-check-circle me-2"></i> Save Password &amp; Activate Account
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-4">
                    <div class="text-warning mb-3">
                        <i class="fas fa-exclamation-triangle fa-3x"></i>
                    </div>
                    <h5 class="fw-bold text-dark">No Pending Activation Found</h5>
                    <p class="text-muted small mb-4">The SMS token is invalid or the account has already been activated.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary px-4">Back to Login</a>
                </div>
            @endif
        </div>
        <div class="card-footer text-center py-3 bg-light border-top">
            <a href="{{ route('login') }}" class="small text-decoration-none text-muted">Back to Sign In</a>
        </div>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>@yield('title', 'ZenithHR') - Enterprise Workforce &amp; Payroll Management</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 60px;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }
        .topbar {
            height: var(--topbar-height);
            background: #0f172a;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }
        .sidebar {
            width: var(--sidebar-width);
            background: #1e293b;
            position: fixed;
            top: var(--topbar-height);
            bottom: 0;
            left: 0;
            overflow-y: auto;
            z-index: 1020;
            transition: all 0.3s;
        }
        .sidebar .nav-link {
            color: #94a3b8;
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-radius: 0.375rem;
            margin: 0.15rem 0.75rem;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background: #2563eb;
        }
        .sidebar .sidebar-heading {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.075rem;
            color: #64748b;
            padding: 1rem 1.5rem 0.35rem;
            font-weight: 700;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 1.75rem;
            min-height: calc(100vh - var(--topbar-height));
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Top Navigation Bar -->
    <header class="topbar d-flex align-items-center justify-content-between px-3 px-md-4 shadow-sm">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none text-white">
                <span class="p-1 px-2 bg-primary text-white rounded fw-bold fs-5">ZH</span>
                <span class="fw-bold fs-5">ZenithHR</span>
            </a>
            @if(Auth::check())
                <span class="badge {{ Auth::user()->isAdmin() ? 'bg-danger' : 'bg-primary' }} text-uppercase ms-2" style="font-size: 0.65rem;">
                    {{ Auth::user()->isAdmin() ? 'ADMIN CONSOLE' : 'EMPLOYEE PORTAL (ESS)' }}
                </span>
            @endif
        </div>

        @if(Auth::check())
            <div class="d-flex align-items-center gap-3">
                <!-- 1-Click Fast Role Switcher for Testing -->
                <div class="dropdown d-none d-md-block">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bolt text-warning me-1"></i> Switch Role
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><h6 class="dropdown-header">Switch Logged-in Persona</h6></li>
                        <li><a class="dropdown-item" href="{{ route('quick.login', 'admin') }}"><i class="fas fa-crown text-warning me-2"></i> HR Admin (Sara Khan)</a></li>
                        <li><a class="dropdown-item" href="{{ route('quick.login', 'zayd') }}"><i class="fas fa-briefcase text-primary me-2"></i> Full-Time Eng (Zayd Ahmed)</a></li>
                        <li><a class="dropdown-item" href="{{ route('quick.login', 'fatima') }}"><i class="fas fa-paint-brush text-info me-2"></i> Contract Designer (Fatima)</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('quick.login', 'inactive') }}"><i class="fas fa-user-slash text-danger me-2"></i> Usman Ghani (Deactivated Test)</a></li>
                    </ul>
                </div>

                <!-- User Profile Pill -->
                <div class="dropdown">
                    <button class="btn btn-link text-white text-decoration-none p-0 d-flex align-items-center gap-2 dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->avatar ?: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=256&q=80' }}" class="rounded-circle border border-2 border-primary" width="36" height="36" style="object-fit: cover;" />
                        <div class="d-none d-sm-block text-start" style="line-height: 1.2;">
                            <div class="small fw-bold text-white">{{ Auth::user()->name }}</div>
                            <div class="text-white-50" style="font-size: 0.7rem;">{{ Auth::user()->isAdmin() ? 'HR Administrator' : Auth::user()->designation }}</div>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><div class="dropdown-header">Signed in as <strong>{{ Auth::user()->phone }}</strong></div></li>
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2 text-muted"></i> Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Sign Out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </header>

    <!-- Left Sidebar -->
    @if(Auth::check())
        <aside class="sidebar">
            <div class="py-3">
                <div class="sidebar-heading">Navigation</div>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-chart-line fa-fw"></i> Dashboard
                    </a>

                    @if(Auth::user()->isAdmin())
                        <div class="sidebar-heading">HR &amp; Workforce</div>
                        <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                            <i class="fas fa-users fa-fw"></i> Staff Directory
                        </a>
                    @endif

                    <div class="sidebar-heading">Self-Service &amp; Ops</div>
                    <a class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                        <i class="fas fa-user-clock fa-fw"></i> Attendance &amp; Punch
                    </a>
                    <a class="nav-link {{ request()->routeIs('leaves.*') ? 'active' : '' }}" href="{{ route('leaves.index') }}">
                        <i class="fas fa-calendar-alt fa-fw"></i> Leave Management
                    </a>
                    <a class="nav-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}" href="{{ route('payroll.index') }}">
                        <i class="fas fa-file-invoice-dollar fa-fw"></i> Payroll &amp; Slips
                    </a>
                </nav>
            </div>
        </aside>
    @endif

    <!-- Main Content Area -->
    <main class="{{ Auth::check() ? 'main-content' : 'container py-5' }}">
        <!-- Real SMS Gateway Automated Dispatch Alert -->
        @if(session('sms_dispatched'))
            @php $dispatch = session('sms_dispatched'); @endphp
            <div class="alert alert-info alert-dismissible fade show shadow-sm border-start border-4 border-primary mb-4" role="alert">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <div class="fw-bold text-dark fs-6">
                            <i class="fas fa-satellite-dish text-primary me-2"></i> [Automated Background Dispatch] SMS Sent to Mobile: <span class="badge bg-primary fs-6">{{ $dispatch['to'] }}</span>
                        </div>
                        <div class="small text-muted mt-1">
                            Recipient: <strong>{{ $dispatch['name'] }}</strong> • Gateway Status: <span class="badge bg-success">{{ $dispatch['gateway_status'] }}</span> • Protocol: <strong>Telecom Network / SMS Gateway</strong>
                        </div>
                        <div class="small text-dark mt-2 bg-white p-2 rounded border">
                            <i class="fas fa-quote-left text-muted me-1"></i> {{ $dispatch['message'] }}
                        </div>
                    </div>
                    <div>
                        <a href="{{ $dispatch['url'] }}" target="_blank" class="btn btn-outline-primary btn-sm fw-bold">
                            <i class="fas fa-external-link-alt me-1"></i> Open Activation Link
                        </a>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- General Flash Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>

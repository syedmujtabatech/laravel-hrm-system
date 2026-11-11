@extends('layouts.app')

@section('title', 'SOLID Principles Architecture Guide')

@section('content')
<div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-1">
        <span class="badge bg-warning text-dark p-2 fs-6"><i class="fas fa-graduation-cap me-1"></i> MASTERCLASS</span>
        <h3 class="fw-bold text-dark mb-0">SOLID Principles in Real-World Laravel HRM</h3>
    </div>
    <p class="text-muted small">Real company architecture: Controllers, Services, Repositories aur Strategies kaise design kiye gaye hain.</p>
</div>

<!-- Tabs for S, O, L, I, D -->
<ul class="nav nav-pills mb-4 gap-2" id="solidTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active fw-bold" id="srp-tab" data-bs-toggle="pill" data-bs-target="#tab-srp">
            <span class="badge bg-white text-primary me-1">S</span> Single Responsibility
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="ocp-tab" data-bs-toggle="pill" data-bs-target="#tab-ocp">
            <span class="badge bg-white text-primary me-1">O</span> Open / Closed
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="lsp-tab" data-bs-toggle="pill" data-bs-target="#tab-lsp">
            <span class="badge bg-white text-primary me-1">L</span> Liskov Substitution
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="isp-tab" data-bs-toggle="pill" data-bs-target="#tab-isp">
            <span class="badge bg-white text-primary me-1">I</span> Interface Segregation
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link fw-bold" id="dip-tab" data-bs-toggle="pill" data-bs-target="#tab-dip">
            <span class="badge bg-white text-primary me-1">D</span> Dependency Inversion
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- 1. SRP -->
    <div class="tab-pane fade show active" id="tab-srp">
        <div class="card shadow-sm border-0 p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="rounded-circle bg-primary text-white p-3 fw-bold fs-4">S</span>
                <div>
                    <h4 class="fw-bold mb-0">Single Responsibility Principle (SRP)</h4>
                    <span class="text-muted small">"A class should have one, and only one, reason to change."</span>
                </div>
            </div>
            <div class="alert alert-light border mb-4">
                <h6 class="fw-bold text-primary mb-1">Roman Urdu Explanation:</h6>
                <p class="mb-0 small text-dark">
                    Real company mein **Controller** ka kaam sirf HTTP request lena aur response dena hota hai. Database queries, tax calculation, SMS dispatching controller ke andar nahi likhte! Is project mein har cheez ke liye alag <strong>Service</strong> bani hui hai:
                </p>
                <ul class="small mb-0 mt-2">
                    <li><code>EmployeeService</code>: Sirf employee onboarding, status changes aur separation handle karta hai.</li>
                    <li><code>AttendanceService</code>: Sirf biometric clock-in/out aur work hours calculate karta hai.</li>
                    <li><code>LeaveService</code>: Sirf leave quota balance aur deductions evaluate karta hai.</li>
                    <li><code>PayrollService</code>: Sirf monthly gross pay, statutory tax aur salary slips generate karta hai.</li>
                    <li><code>SmsNotificationService</code>: Sirf mobile activation links aur SMS dispatch karta hai.</li>
                </ul>
            </div>
            <pre class="bg-dark text-white p-3 rounded small"><code>// EmployeeController.php - Clean and Thin!
public function store(Request $request)
{
    $validated = $request->validate([...]);
    
    // Controller logic nahi likhta, SRP Service ko delegate karta hai!
    $employee = $this->employeeService->createEmployee($validated);

    return redirect()->route('employees.index')->with('success', 'Onboarded!');
}</code></pre>
        </div>
    </div>

    <!-- 2. OCP -->
    <div class="tab-pane fade" id="tab-ocp">
        <div class="card shadow-sm border-0 p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="rounded-circle bg-success text-white p-3 fw-bold fs-4">O</span>
                <div>
                    <h4 class="fw-bold mb-0">Open / Closed Principle (OCP)</h4>
                    <span class="text-muted small">"Software entities should be open for extension, but closed for modification."</span>
                </div>
            </div>
            <div class="alert alert-light border mb-4">
                <h6 class="fw-bold text-success mb-1">Roman Urdu Explanation:</h6>
                <p class="mb-0 small text-dark">
                    Agar company kal ko naya tax slab (e.g., Freelancer Exemption) ya nayi leave policy (e.g., Hajj Leave) introduce karti hai, toh hume puraana code edit nahi karna parta. Hum naya <strong>Strategy class</strong> add kar dete hain bina existing core calculations ko chede!
                </p>
            </div>
            <pre class="bg-dark text-white p-3 rounded small"><code>// Tax Strategy Pattern (OCP)
interface TaxStrategyInterface {
    public function calculateTax(float $gross): float;
}

class StandardTaxStrategy implements TaxStrategyInterface {
    public function calculateTax(float $gross): float { return $gross * 0.08; }
}

class ExecutiveTaxStrategy implements TaxStrategyInterface {
    public function calculateTax(float $gross): float { return $gross * 0.15; }
}</code></pre>
        </div>
    </div>

    <!-- 3. LSP -->
    <div class="tab-pane fade" id="tab-lsp">
        <div class="card shadow-sm border-0 p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="rounded-circle bg-warning text-white p-3 fw-bold fs-4">L</span>
                <div>
                    <h4 class="fw-bold mb-0">Liskov Substitution Principle (LSP)</h4>
                    <span class="text-muted small">"Subtypes must be substitutable for their base types."</span>
                </div>
            </div>
            <div class="alert alert-light border mb-4">
                <h6 class="fw-bold text-warning mb-1">Roman Urdu Explanation:</h6>
                <p class="mb-0 small text-dark">
                    Company mein Full-Time, Contractor, Part-Time aur Intern sab **Employee** hain. Jab system payroll ya attendance run karta hai, toh woh har subtype ko uniformly handle karta hai bina kisi runtime crash ke!
                </p>
            </div>
            <pre class="bg-dark text-white p-3 rounded small"><code>// LSP Polymorphic Subtypes
abstract class BaseEmployee {
    abstract public function calculateBenefits(): float;
}

class FullTimeEmployee extends BaseEmployee {
    public function calculateBenefits(): float { return 35000.00; } // Health + Provident Fund
}

class InternEmployee extends BaseEmployee {
    public function calculateBenefits(): float { return 5000.00; }  // Learning Stipend
}</code></pre>
        </div>
    </div>

    <!-- 4. ISP -->
    <div class="tab-pane fade" id="tab-isp">
        <div class="card shadow-sm border-0 p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="rounded-circle bg-info text-white p-3 fw-bold fs-4">I</span>
                <div>
                    <h4 class="fw-bold mb-0">Interface Segregation Principle (ISP)</h4>
                    <span class="text-muted small">"Clients should not be forced to depend upon interfaces that they do not use."</span>
                </div>
            </div>
            <div class="alert alert-light border mb-4">
                <h6 class="fw-bold text-info mb-1">Roman Urdu Explanation:</h6>
                <p class="mb-0 small text-dark">
                    Bari bloated interface banane ke bajaye choti, focused interfaces banayein. Example: Contractor ko Health Insurance ya Provident Fund interface force nahi ki jaati!
                </p>
            </div>
            <pre class="bg-dark text-white p-3 rounded small"><code>interface PunchableInterface {
    public function punchIn();
    public function punchOut();
}

interface BenefitsEligibleInterface {
    public function claimMedicalInsurance();
    public function getProvidentFundBalance();
}

// Full-time dono implement karega, Contractor sirf punchable implement karega!</code></pre>
        </div>
    </div>

    <!-- 5. DIP -->
    <div class="tab-pane fade" id="tab-dip">
        <div class="card shadow-sm border-0 p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <span class="rounded-circle bg-danger text-white p-3 fw-bold fs-4">D</span>
                <div>
                    <h4 class="fw-bold mb-0">Dependency Inversion Principle (DIP)</h4>
                    <span class="text-muted small">"Depend upon abstractions, not concretions."</span>
                </div>
            </div>
            <div class="alert alert-light border mb-4">
                <h6 class="fw-bold text-danger mb-1">Roman Urdu Explanation:</h6>
                <p class="mb-0 small text-dark">
                    Laravel ka <strong>Service Container</strong> DIP ko natively support karta hai. Controllers direct <code>new EmployeeService()</code> instantiate nahi karte; Laravel auto-inject karta hai Constructor Injection ke zariye!
                </p>
            </div>
            <pre class="bg-dark text-white p-3 rounded small"><code>// DIP Constructor Injection in Laravel
class EmployeeController extends Controller
{
    // Dependency Injected via Service Container!
    public function __construct(
        protected EmployeeService $employeeService
    ) {}
}</code></pre>
        </div>
    </div>
</div>
@endsection

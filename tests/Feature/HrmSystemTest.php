<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HrmSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_quick_login_admin_works_and_opens_admin_dashboard(): void
    {
        $response = $this->get('/quick-login/admin');
        $response->assertRedirect('/dashboard');

        $admin = User::where('role', 'Admin')->first();
        $this->assertAuthenticatedAs($admin);

        $dashboardResponse = $this->actingAs($admin)->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('ADMIN CONSOLE');
        $dashboardResponse->assertSee('Operational (100%)');
        $dashboardResponse->assertDontSee('SOLID Guide');
    }

    public function test_quick_login_employee_works_and_opens_ess_portal(): void
    {
        $response = $this->get('/quick-login/zayd');
        $response->assertRedirect('/dashboard');

        $zayd = User::where('phone', '+92 300 1234567')->first();
        $this->assertAuthenticatedAs($zayd);

        $dashboardResponse = $this->actingAs($zayd)->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('EMPLOYEE PORTAL (ESS)');
        $dashboardResponse->assertDontSee('SOLID Guide');
    }

    public function test_offboarded_inactive_employee_cannot_access_system(): void
    {
        $response = $this->get('/quick-login/inactive');
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['login']);
        $this->assertGuest();
    }

    public function test_admin_can_access_staff_directory(): void
    {
        $admin = User::where('role', 'Admin')->first();
        $response = $this->actingAs($admin)->get('/employees');
        $response->assertStatus(200);
        $response->assertSee('Corporate Staff Directory');
    }

    public function test_regular_employee_cannot_access_admin_staff_directory(): void
    {
        $zayd = User::where('phone', '+92 300 1234567')->first();
        $response = $this->actingAs($zayd)->get('/employees');
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error');
    }

    public function test_attendance_punch_in_and_punch_out(): void
    {
        $zayd = User::where('phone', '+92 300 1234567')->first();
        $response = $this->actingAs($zayd)->post('/attendance/punch');
        $response->assertSessionHas('success');
    }

    public function test_employee_can_view_own_salary_slip(): void
    {
        $zayd = User::where('phone', '+92 300 1234567')->first();
        $payroll = Payroll::where('user_id', $zayd->id)->first();

        $this->assertNotNull($payroll);
        $response = $this->actingAs($zayd)->get("/payroll/slip/{$payroll->id}");
        $response->assertStatus(200);
        $response->assertSee('Official Corporate Salary Slip');
    }

    public function test_employee_cannot_view_another_employees_salary_slip(): void
    {
        $fatima = User::where('phone', '+92 321 9876543')->first();
        $zayd = User::where('phone', '+92 300 1234567')->first();
        $zaydPayroll = Payroll::where('user_id', $zayd->id)->first();

        $this->assertNotNull($zaydPayroll);
        $response = $this->actingAs($fatima)->get("/payroll/slip/{$zaydPayroll->id}");
        $response->assertStatus(403);
    }

    public function test_solid_guide_route_redirects_cleanly_to_dashboard(): void
    {
        $zayd = User::where('phone', '+92 300 1234567')->first();
        $response = $this->actingAs($zayd)->get('/solid-guide');
        $response->assertRedirect('/dashboard');
    }
}

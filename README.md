# ZenithHR - Enterprise Human Resource & Payroll Management System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11%20%2F%2012-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5" />
  <img src="https://img.shields.io/badge/Database-MySQL-00758F?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License" />
</p>

ZenithHR is a modern, enterprise-grade Human Resource Management (HRM) and Payroll solution built on Laravel. It provides comprehensive capabilities for end-to-end workforce lifecycle administration, attendance tracking, leave request processing, automated payroll generation, and SMS activation.

---

## Key Features

### 1. Employee Lifecycle Management
- **Automated Onboarding**: Rapid staff registration with role assignment (`Admin`, `Employee`).
- **SMS Account Activation**: One-time token verification link dispatched via SMS/WhatsApp gateway for new employees.
- **Offboarding & Archival**: Safe offboarding workflow with immediate portal access revocation while preserving historical attendance and payroll audits.

### 2. Real-Time Attendance & Geolocation Ready
- **Punch Clock System**: Single-click punch-in and punch-out with duration and overtime calculations.
- **Monthly Attendance Logs**: Comprehensive timesheets filtered by department, status (`Present`, `Late`, `HalfDay`).

### 3. Leave Management Workflow
- **Multi-Type Leaves**: Annual, Casual, Sick, and Unpaid leave allocations with real-time balance tracking.
- **Approval Engine**: Admins can review, approve, or reject requests with immediate status updates.

### 4. Payroll & Salary Slips
- **Automated Salary Calculation**: Base salary, allowances, statutory tax withholdings, and EOBI contributions.
- **Disbursement Engine**: Single-click monthly salary disbursement with batch status updates.
- **Printable Pay Slips**: Professional corporate salary slips ready for PDF export or printing.

### 5. Enterprise Architecture & Clean Code
- Built adhering to modular service layers, repository patterns, and clean code practices.
- 100% automated feature test coverage ensuring high reliability.

---

## Quick Setup Guide

### Prerequisites
- PHP 8.2 or higher
- Composer 2.x
- MySQL / MariaDB (XAMPP or standalone)

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/syedmujtabatech/laravel-hrm-system.git
   cd laravel-hrm-system
   ```

2. **Install Composer dependencies:**
   ```bash
   composer install
   ```

3. **Configure Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration:**
   Create a database named `zenith_hrm` in MySQL, then run:
   ```bash
   php artisan migrate --seed
   ```

5. **Start Application:**
   ```bash
   php artisan serve --port=8000
   ```
   Or simply execute `run.bat` on Windows.

---

## Default Credentials (Demo)

| Role | Mobile / Email | Password | Persona |
|---|---|---|---|
| **HR Administrator** | `+92 300 0000000` / `admin@zenithhr.com` | `admin123` | Sara Khan (CHRO) |
| **Full-Time Employee** | `+92 300 1234567` / `zayd@zenithhr.com` | `password123` | Zayd Ahmed (Principal Engineer) |
| **Contractor** | `+92 321 9876543` / `fatima@zenithhr.com` | `password123` | Fatima Zahra (Lead Designer) |
| **Offboarded / Inactive** | `+92 322 8899001` / `usman@zenithhr.com` | `password123` | Usman Ghani (Blocked Access Test) |

*1-Click fast login shortcuts are also available on the login page for effortless persona switching.*

---

## Running Automated Tests

To execute the automated test suite:
```bash
php artisan test
```

---

## License
This project is open-source under the [MIT License](LICENSE).

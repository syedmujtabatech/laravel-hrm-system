# ZenithHR - Enterprise Human Resource & Payroll Management System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11%20%2F%2012-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5" />
  <img src="https://img.shields.io/badge/Database-MySQL-00758F?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License" />
</p>

ZenithHR is a modern, enterprise-grade Human Resource Management (HRM) and Payroll solution built on top of Laravel. It provides comprehensive capabilities for end-to-end workforce lifecycle administration, attendance tracking, leave request processing, automated payroll generation, and SMS activation.

---

## 🌟 Key Features

### 1. 👥 Employee Lifecycle Management
- **Automated Onboarding**: Rapid staff registration with role assignment (`Admin`, `Manager`, `Employee`).
- **SMS Account Activation**: 6-digit OTP verification code sent via SMS/WhatsApp gateway for new employees.
- **Offboarding & Archival**: Safe offboarding workflow preserving historical attendance and payroll audits.

### 2. ⏱️ Real-Time Attendance & Geolocation Ready
- **Punch Clock System**: Single-click punch-in and punch-out with duration and overtime calculations.
- **Monthly Attendance Logs**: Comprehensive timesheets filtered by department, status (`Present`, `Late`, `Absent`, `Half-day`).

### 3. 📅 Leave Management Workflow
- **Multi-Type Leaves**: Annual, Casual, Sick, and Unpaid leave allocations.
- **Approval Engine**: Managers and Admins can review, approve, or reject requests with audit comments.

### 4. 💰 Payroll & Salary Slips
- **Automated Salary Calculation**: Base salary, allowances, deductions, and tax withholdings.
- **Disbursement Engine**: Single-click monthly salary disbursement with batch status updates.
- **Printable Pay Slips**: Professional corporate salary slips ready for PDF export or thermal printing.

### 5. 🏛️ SOLID Architecture & Clean Code
- Built adhering to SOLID design principles with dedicated service layers and interfaces.
- Includes interactive **SOLID Architecture Guide** inside the application dashboard.

---

## 🚀 Quick Setup Guide

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

## 🔐 Default Credentials (Demo)

| Role | Email | Password |
|---|---|---|
| **HR Administrator** | `admin@zenithhr.com` | `Admin@123` |
| **Employee** | `employee@zenithhr.com` | `Employee@123` |

*Quick login shortcuts are also available on the login page for effortless testing.*

---

## 📄 License
This project is open-source under the [MIT License](LICENSE).

# SmartShelf

**Smart Multi-Branch University Library Management System**

SmartShelf is a robust, enterprise-grade, modern library management system built specifically for multi-branch university libraries. It combines a highly responsive, glassmorphism-inspired React frontend with a powerful Laravel backend to handle complex operations like Role-Based Access Control (RBAC), inter-branch circulation, automated fine calculations, eBook access, and detailed reporting.

---

## 🚀 Features

- **Multi-Branch Support:** Seamlessly manage library resources, staff, and circulation across multiple university campuses or branch libraries.
- **Advanced RBAC:** Highly granular permissions system with roles such as Super Admin, Branch Admin, Librarian, Faculty Member, and Student.
- **Circulation & Loan Management:** Automated checkout, return, and renewal processing with integrated late-fine calculations.
- **Digital Library (eBooks):** Secure eBook repository allowing faculty and students to download or read digital materials based on access levels.
- **Comprehensive Cataloging:** Track books, authors, publishers, categories, and individual book copies with real-time availability tracking.
- **Reservation System:** Allow students and faculty to place holds on checked-out materials.
- **Reporting & Analytics:** Generate detailed reports on circulation trends, fine collections, and inventory status.
- **Audit Logging:** System-wide activity tracking for accountability and security.

---

## 💻 Tech Stack

### Frontend
- **Framework:** React 19 with TanStack Start (Vite)
- **Routing:** TanStack Router
- **Styling:** Tailwind CSS 4 with a custom glassmorphism design system
- **Components:** Radix UI primitives & custom components
- **State/Data:** TanStack Query (React Query)
- **Forms:** React Hook Form & Zod for validation

### Backend
- **Framework:** Laravel 13 (PHP 8.3+)
- **Database:** MySQL 8.0+
- **Authentication:** Laravel Sanctum (Token-based API Auth) & Laravel Breeze (Web Auth)
- **Testing:** Pest PHP
- **Asset Compilation:** Vite (for backend Blade views if running in Monolithic mode)

---

## 📂 Project Structure

The project is structured as a decoupled application with an API-driven architecture, though it also supports monolithic deployment.

```text
SmartShelf/
├── backend/            # Laravel 13 API & Administration Dashboard
│   ├── app/            # Controllers, Models, Services, Policies
│   ├── database/       # Migrations, Factories, Seeders
│   ├── routes/         # API & Web routes
│   └── tests/          # Pest test suites
├── src/                # TanStack Start React Frontend
│   ├── components/     # UI & Shared Components
│   ├── lib/            # Utilities & Auth Context
│   └── routes/         # React application pages
└── public/             # Static Frontend Assets
```

---

## ⚙️ Installation & Setup

### Prerequisites
- Node.js (v20+)
- PHP 8.3+
- Composer
- MySQL 8.0+

### Backend Setup (Laravel)
1. Navigate to the backend directory:
   ```bash
   cd backend
   ```
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Set up the environment file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Configure your `.env` with your database credentials.
5. Run migrations and seeders (this populates test data and roles):
   ```bash
   php artisan migrate --seed
   ```
6. Start the backend development server:
   ```bash
   php artisan serve
   ```

### Frontend Setup (React)
1. From the project root, install Node dependencies:
   ```bash
   npm install
   ```
2. Start the Vite development server:
   ```bash
   npm run dev
   ```

*(Note: The frontend proxy is configured to automatically route `/api` requests to `http://localhost:8000`.)*

---

## 🧪 Testing
The backend is extensively tested using Pest. To run the automated test suite:
```bash
cd backend
php artisan test
```

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

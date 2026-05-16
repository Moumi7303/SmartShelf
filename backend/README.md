<p align="center">
  <strong>📚 SmartShelf</strong><br>
  <em>Smart Multi-Branch University Library Management System — Backend</em>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.3+">
  <img src="https://img.shields.io/badge/License-MIT-blue?style=flat-square" alt="License">
</p>

---

## About SmartShelf

**SmartShelf** is an enterprise-grade, multi-branch university library management system built with Laravel. It provides a robust backend API for:

- **Catalog Management** — Full CRUD for books, periodicals, and digital media across multiple library branches.
- **Circulation & Loans** — Issue, renew, and return workflows with overdue tracking and automated notifications.
- **Member Management** — Student, faculty, and staff registration with role-based access control.
- **Multi-Branch Support** — Manage inventory, transfers, and analytics across all campus library locations.
- **Analytics & Reporting** — PDF report generation with SmartShelf branding, usage dashboards, and audit logs.
- **Notifications** — Email and in-app notification pipelines for due dates, reservations, and system alerts.

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 13.x |
| Language | PHP 8.3+ |
| Database | MySQL / MariaDB |
| Queue | Database driver (configurable) |
| Cache | Database driver (configurable) |
| Mail | SMTP / SES / Postmark / Resend |

## Getting Started

```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed default data
php artisan db:seed

# Start development server
php artisan serve
```

## Development

```bash
# Run all services concurrently (server + queue + vite)
composer dev

# Run tests
composer test

# Format code
./vendor/bin/pint
```

## Environment

Key environment variables:

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_NAME` | `SmartShelf` | Application display name |
| `DB_DATABASE` | `backend` | Database name |
| `MAIL_FROM_NAME` | `SmartShelf` | Email sender name |
| `QUEUE_CONNECTION` | `database` | Queue driver |

## License

SmartShelf is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

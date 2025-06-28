# Legal Laravel CRM

A modern Customer Relationship Management system specifically for law firms and consultants, developed with Laravel and modern web technologies.

## 📋 Project Overview

The Legal CRM is a comprehensive solution for managing clients, counseling sessions, and documents. It offers an intuitive user interface and role-based access control for different user types.

### 🎯 Target Audience
- Law Firms
- Consultants
- Coaching Practices
- Therapy Practices

## 🚀 Features

### ✅ Implemented (v1.0)

#### Authentication & User Management
- [x] Role-based authentication (Admin, Counselor, User)
- [x] Secure login with Laravel Breeze
- [x] User profiles and management
- [x] Password reset functionality

#### Client Management
- [x] Full CRUD operations for clients
- [x] Advanced search functions
- [x] Filtering options by status, date, etc.
- [x] Client detail views
- [x] Contact information and notes

#### Session Management
- [x] Appointment scheduling and management
- [x] Session status tracking
- [x] Linking with clients
- [x] Time tracking for sessions

#### Dashboard & Overviews
- [x] Interactive dashboard with statistics
- [x] Overview of upcoming appointments
- [x] Quick access to important functions
- [x] Responsive design for all devices

#### Calendar
- [x] Monthly view of all appointments
- [x] Calendar integration
- [x] Appointment overview

#### Technical Basis
- [x] Laravel 12 Framework
- [x] SQLite/MySQL/PostgreSQL support
- [x] Tailwind CSS for modern design
- [x] Alpine.js for interactive components
- [x] Vite for asset bundling
- [x] Responsive design
- [x] Database seeding with test data

## 🚀 Getting Started

This guide provides instructions for setting up the Legal Laravel CRM for both local development using Laravel Sail and for production deployment on Fly.io.

### Local Development with Laravel Sail

#### Prerequisites
- Docker Desktop

#### Steps

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/legal-laravel-crm.git
    cd legal-laravel-crm
    ```

2.  **Create a `.env` file:**
    ```bash
    cp .env.example .env
    ```

3.  **Install Composer dependencies:**
    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php82-composer:latest \
        composer install --ignore-platform-reqs
    ```

4.  **Start the Sail containers:**
    ```bash
    ./vendor/bin/sail up -d
    ```

    💡 **Tip:** To run the application on a custom port, you can set the `APP_PORT` variable in your `.env` file. For example:
    ```dotenv
    APP_PORT=8080
    APP_URL=http://localhost:8080
    ```
    After changing the port, you may need to restart the Sail containers: `./vendor/bin/sail down && ./vendor/bin/sail up -d`.

5.  **Generate the application key:**
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

6.  **Run database migrations and seed the database:**
    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```

7.  **Install NPM dependencies and build assets:**
    ```bash
    ./vendor/bin/sail npm install
    ./vendor/bin/sail npm run dev
    ```

Your application is now available at the URL specified by `APP_URL` in your `.env` file (by default: http://localhost).

#### Default Users

-   **Admin:** `admin@example.com` / `password`
-   **Counselor:** `counselor@example.com` / `password`

### Production Deployment with Fly.io

#### Prerequisites

-   Fly.io account
-   `flyctl` CLI installed and authenticated
-   A PostgreSQL database on Fly.io

#### Steps

1.  **Launch the app on Fly.io:**
    ```bash
    flyctl launch
    ```
    This will create a `fly.toml` file and a `Dockerfile`.

2.  **Set the application key as a secret:**
    ```bash
    flyctl secrets set APP_KEY=$(php artisan key:generate --show)
    ```

3.  **Set the `DATABASE_URL` secret:**
    Get your database connection string from the Fly.io dashboard and set it as a secret:
    ```bash
    flyctl secrets set DATABASE_URL="your-postgres-connection-string"
    ```

4.  **Deploy the application:**
    ```bash
    flyctl deploy
    ```

5.  **Run database migrations:**
    Connect to the production instance and run the migrations:
    ```bash
    flyctl ssh console -C "php artisan migrate --force"
    ```

## 🏗️ Technical Architecture

### Backend
- **Framework**: Laravel 12
- **PHP Version**: 8.2+
- **Database**: SQLite/MySQL/PostgreSQL
- **Authentication**: Laravel Breeze
- **Queue System**: Database/Redis
- **Caching**: File/Redis/Memcached

### Frontend
- **CSS Framework**: Tailwind CSS 4.x
- **JavaScript**: Alpine.js 3.x
- **Build Tool**: Vite 6.x
- **Icons**: Heroicons
- **Forms**: @tailwindcss/forms

### DevOps & Deployment
- **Containerization**: Docker
- **CI/CD**: GitHub Actions
- **Monitoring**: Laravel Telescope
-- **Testing**: PHPUnit, Pest

## 🤝 Contributing

### Development Guidelines
1.  **Code Standards**: PSR-12 Coding Standards
2.  **Testing**: All new features require tests
3.  **Documentation**: Inline documentation required
4.  **Security**: Security review for all PRs

### Git Workflow
1.  Feature Branches from `develop`
2.  Pull Requests with Code Review
3.  Automated Testing before Merge
4.  Semantic Versioning

## 📄 License

This project is developed for internal use and is subject to company policies.
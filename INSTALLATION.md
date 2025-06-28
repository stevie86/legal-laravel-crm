# Installation Guide

This guide provides instructions for setting up the Legal Laravel CRM for both local development using Laravel Sail and for production deployment on Fly.io.

## Local Development with Laravel Sail

### Prerequisites
- Docker Desktop

### Steps

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

5.  **Publish the configuration for Spatie Permissions:**
    This package is used for role and permission management.
    ```bash
    ./vendor/bin/sail artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
    ```

6.  **Generate the application key:**
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

7.  **Run database migrations and seed the database:**
    This will create all necessary tables, including those for roles and permissions.
    ```bash
    ./vendor/bin/sail artisan migrate --seed
    ```

8.  **Install NPM dependencies and build assets:**
    ```bash
    ./vendor/bin/sail npm install
    ./vendor/bin/sail npm run dev
    ```

Your application is now available at the URL specified by `APP_URL` in your `.env` file (by default: http://localhost).

### Default Users

-   **Admin:** `admin@example.com` / `password`
-   **Counselor:** `counselor@example.com` / `password`

## Production Deployment with Fly.io

### Prerequisites

-   [Fly.io account](https://fly.io/)
-   `flyctl` CLI installed and authenticated
-   A PostgreSQL database on Fly.io

### Steps

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

## Troubleshooting

### Creating a Super-Admin User

If you are unable to log in with the default seeded credentials, or if you prefer to create your own administrator account, you can use the following Artisan command.

1.  **For local development with Sail:**
    ```bash
    ./vendor/bin/sail artisan crm:create-super-admin
    ```

2.  **For production on Fly.io:**
    ```bash
    flyctl ssh console -C "php artisan crm:create-super-admin"
    ```

The command will prompt you to enter a name, email, and password for the new super-admin account.
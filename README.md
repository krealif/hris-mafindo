
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Prerequisites

Ensure you have the following installed on your system:

-   PHP (>=8.3)
    
-   Composer
    
-   MySQL or MariaDB

## Installation Steps

### 1. Clone the Repository
```bash
git clone https://github.com/krealif/hris-mafindo
cd hris-mafindo
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure Environment Variables
Copy the `.env.example` file and rename it to `.env`:
```bash
cp .env.example .env
```
Then update the following sections in `.env`:

#### Database Configuration
Ensure the database is created in MySQL and update the `.env` file with the correct credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

#### Mail Configuration
Update mail settings to enable email functionality:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@yourdomain.com
MAIL_FROM_NAME="Your Application Name"
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Run Database Migrations & Seeders
```bash
php artisan migrate --seed
```

### 6. Storage & Link Configuration
```bash
php artisan storage:link
```

### 7. Optimize and Cache Icons
```bash
php artisan optimize
php artisan icons:cache
```

### 8. Start the Development Server
```bash
php artisan serve
```
By default, the project will be accessible at `http://127.0.0.1:8000`.

### 9. Running Queue Worker (To Send an Email)
If the application uses queues, run the following command:
```bash
php artisan queue:work
```

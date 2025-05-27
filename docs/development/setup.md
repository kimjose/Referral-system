# Development Setup Guide

This guide will help you set up the development environment for the Referral System.

## Prerequisites

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Node.js and NPM
- Git

## Installation Steps

1. Clone the repository:
   ```bash
   git clone https://gitlab.com/kim_kim/angaza_referral2.git
   cd angaza_referral2
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Create environment file:
   ```bash
   cp .env.example .env
   ```

4. Configure your database in `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=angaza_referral
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Run database migrations:
   ```bash
   php artisan migrate
   ```

7. Seed the database:
   ```bash
   php artisan db:seed
   ```

8. Install Node.js dependencies:
   ```bash
   npm install
   ```

9. Compile assets:
   ```bash
   npm run dev
   ```

10. Start the development server:
    ```bash
    php artisan serve
    ```

## Development Workflow

1. Create a new branch for your feature:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. Make your changes and commit them:
   ```bash
   git add .
   git commit -m "Description of your changes"
   ```

3. Push your changes:
   ```bash
   git push origin feature/your-feature-name
   ```

4. Create a merge request on GitLab

## Testing

Run the test suite:
```bash
php artisan test
```

## Common Issues

### Permission Issues
If you encounter permission issues:
```bash
chmod -R 775 storage bootstrap/cache
```

### Composer Issues
If you have issues with Composer:
```bash
composer clear-cache
composer update
```

### NPM Issues
If you have issues with NPM:
```bash
rm -rf node_modules
npm cache clean --force
npm install
``` 
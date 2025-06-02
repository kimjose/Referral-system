# Development Setup Guide

This guide will help you set up your development environment for the Angaza Referral System.

## Prerequisites

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Composer
- Node.js 18 or higher
- npm or yarn
- Git

## Installation Steps

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/angaza-referral.git
   cd angaza-referral
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure database:
   - Create a MySQL database
   - Update `.env` with database credentials
   - Run migrations:
     ```bash
     php artisan migrate
     ```

6. Start development server:
   ```bash
   php artisan serve
   ```

## Development Tools

### Code Style
- Follow PSR-12 coding standards
- Use PHP_CodeSniffer for linting
- Run tests before committing

### Testing
- PHPUnit for unit tests
- Laravel Dusk for browser tests
- Jest for JavaScript tests

### Documentation
- PHPDoc for PHP code
- JSDoc for JavaScript code
- Markdown for general documentation

## Common Issues

### Database Connection
- Verify MySQL is running
- Check database credentials
- Ensure database exists

### Composer Issues
- Clear composer cache
- Update composer
- Check PHP version

### Node.js Issues
- Clear npm cache
- Delete node_modules
- Reinstall dependencies

## Next Steps

- Read the [Contributing Guidelines](CONTRIBUTING.md)
- Review the [Architecture Overview](../architecture/system-overview.md)
- Check the [API Documentation](../api/overview.md) 
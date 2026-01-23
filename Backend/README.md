# EMS Backend

A simple backend application for Exam Management System built with PHP and Slim Framework.

## Prerequisites

- PHP 8.2 or higher
- Composer
- Docker (optional, for containerized setup)

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd ems-backend
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Copy the environment file and configure your settings:
   ```bash
   cp .env.example .env
   ```

4. Set up your database configuration in the `.env` file.

## Database Setup

### Create Migrations

Generate a new migration:
```bash
php bin/console migrations:generate
```

Or create from differences:
```bash
php bin/console migrations:diff
```

### Run Migrations

```bash
php bin/console migrations:migrate
```

For Docker:
```bash
docker-compose run api php bin/console migrations:migrate
```

### Seed Database

```bash
php bin/console db:seed
```

## Running the Application

Start the development server:
```bash
composer dump-autoload
php -S localhost:8000 -t public
```

The API will be available at `http://localhost:8000`.

## API Endpoints

### Admin Routes (`/admin`)

- `POST /admin/exams` - Create a new exam
- `PUT /admin/exams/{id}` - Update an existing exam
- `GET /admin/exams/{id}/attempts` - Get attempt history for an exam

### Student Routes (`/student`)

- `GET /student/exams` - Get student's exam dashboard
- `GET /student/exams/{id}/attempts` - Get student's attempts for an exam
- `POST /student/exams/{id}/start` - Start an exam attempt
- `POST /student/attempts/{id}/submit` - Submit an exam attempt

## Testing

Run the test suite:
```bash
composer test
```

Or run specific test types:
```bash
# Unit tests
composer run test:unit

# Integration tests
composer run test:int
```

## Project Structure

- `src/` - Application source code
- `config/` - Configuration files
- `migrations/` - Database migrations
- `public/` - Public web directory
- `tests/` - Test files

## Technologies Used

- Slim Framework
- Doctrine ORM
- Symfony Console
- PHP-DI
- vlucas/phpdotenv
- PHPUnit (for testing)
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

## Docker Setup (Optional)

If you prefer to run the application using Docker:

1. Build the Docker images:
   ```bash
   docker-compose build
   ```

2. Start the services:
   ```bash
   docker-compose up
   ```

   The API will be available at `http://localhost:8000`.

3. To run commands inside the container:
   ```bash
   docker-compose run api <command>
   ```

   For example, to run migrations:
   ```bash
   docker-compose run api php bin/console migrations:migrate
   ```

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

### Local Development

Start the development server:
```bash
composer dump-autoload
php -S localhost:8000 -t public
```

### Using Docker

If using Docker, the application will be available at `http://localhost:8000` after running `docker-compose up`.

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

### Test Coverage

The test suite includes:

- **Unit Tests**: Test individual components in isolation
- **Integration Tests**: Test service layer interactions with the database
- **API Integration Tests**: Test HTTP endpoints end-to-end, including:
  - Admin exam management (create, update, attempt history)
  - Student exam dashboard and attempt management
  - Full request/response cycle validation

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
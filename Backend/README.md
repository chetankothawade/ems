# EMS Backend

A simple backend application for Exam Management System built with PHP and Slim Framework.

## Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL or compatible database
- Docker and Docker Compose (optional, for containerized setup)

**Note**: For Docker setup, ensure you have a `docker-compose.yml` file configured for the project (not included in this repository).

## Installation

### Quick Setup (Recommended)

**On macOS/Linux:**
```bash
chmod +x setup.sh
./setup.sh
```

**On Windows:**
```cmd
setup.bat
```

This automated setup will:
1. Create `.env` from `.env.example` (if needed)
2. Install PHP dependencies
3. Run database migrations automatically
4. Seed database with demo data
5. Verify installation

### Manual Setup

If you prefer manual steps or the automated setup doesn't work:

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd ems-backend
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Copy and configure the environment file:
   ```bash
   cp .env.example .env
   ```
   Update database credentials in `.env`

4. Run migrations:
   ```bash
   php bin/console migrations:migrate
   ```

5. Seed the database:
   ```bash
   php bin/console db:seed
   ```

## Database Setup

### Local Setup

#### Run Migrations

```bash
php bin/console migrations:migrate
```

#### Seed Database

```bash
php bin/console db:seed
```

### Docker Setup (Automated)

The Docker setup automatically runs migrations and seeding on container startup:

1. Build the Docker images:
   ```bash
   docker compose build
   ```

2. Start the services (migrations and seeding run automatically):
   ```bash
   docker compose up
   ```

The API will be available at `http://localhost:8000` and the database will be fully initialized.

**Note**: To manually run migrations or seeding in an existing container:
```bash
# Run migrations
docker compose exec api php bin/console migrations:migrate

# Seed database
docker compose exec api php bin/console db:seed
```

## Running the Application

### Local Development

After running the setup script:

```bash
php -S localhost:8000 -t public
```

The API will be available at `http://localhost:8000`.

### Docker Setup

The easiest way to run the application with automated database setup:

```bash
docker compose up
```

The API will be available at `http://localhost:8000` with migrations and seeding automatically applied.

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

### Local Setup

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

### Docker Setup

Run tests inside the container:
```bash
# Full test suite
docker compose run api composer test

# Run Unit tests
docker compose run api composer run test:unit

# Run Integration tests
docker compose run api composer run test:int
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
- `Tests/` - Test files

## Technologies Used

- Slim Framework
- Doctrine ORM
- Symfony Console
- PHP-DI
- vlucas/phpdotenv
- PHPUnit (for testing)

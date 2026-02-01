#Exam Management System (EMS)

Built with:

- Backend: PHP (Slim + Doctrine ORM)
- Database: MySQL 8
- Frontend: React + Vite
- Docker + Docker Compose

Everything runs inside Docker — **no local PHP/Node/MySQL setup required**.

---

#Prerequisites

Install only:

- Docker Desktop (latest)
- Git

Nothing else is needed.

---

#Start the Application (ONE command)

```bash
docker compose up --build

#Run Migrations (First time only)
docker compose run api php bin/console migrations:migrate
#Run Seeder
docker compose run api php bin/console db:seed

# Run the API tests
# Full test
docker compose run api composer test
# Unit Tests
docker compose run api composer run test:unit
#Integration tests
docker compose run api composer run test:int

# Run the UI tests 
docker compose run frontend npm test


# Rebuild CLEAN containers (If Required)
docker compose down -v
docker compose build --no-cache
docker compose up


#Open URL FOR Preview
http://localhost:3000/#/admin
http://localhost:3000/#/student

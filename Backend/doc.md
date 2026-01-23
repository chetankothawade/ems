1. Create Your First Migration
php bin/console migrations:diff


Or create manually:

php bin/console migrations:generate


Migration files go here:

migrations/
└── Version202601210001.php


2. Run Migrations
php bin/console migrations:migrate


You should see:

Migrating up to DoctrineMigrations\Version202601210001

3. Docker Integration (IMPORTANT)

In your docker-compose, migrations must run inside the container:

docker-compose run api php bin/console migrations:migrate

-----------------------------------------------------------
SEEDER
php bin/console db:seed


---------------------------------------------------------
run
composer dump-autoload
php -S localhost:8000 -t public
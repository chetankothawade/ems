@echo off
REM EMS Backend Setup Script for Windows

echo.
echo 🚀 EMS Backend Setup Script
echo ============================
echo.

REM Check if .env exists
if not exist .env (
    echo ⚠️  .env file not found. Copying from .env.example...
    if exist .env.example (
        copy .env.example .env
        echo ✅ Created .env file. Please update database credentials.
    ) else (
        echo ❌ Error: .env.example not found. Please create .env manually.
        exit /b 1
    )
)

echo 📦 Installing PHP dependencies...
call composer install --prefer-dist

echo.
echo 🗄️  Running database migrations...
php bin/console migrations:migrate --no-interaction

echo.
echo 🌱 Seeding database...
php bin/console db:seed

echo.
echo ✅ Setup complete!
echo.
echo 📋 Next steps:
echo    1. Update .env with your database credentials if not done
echo    2. Run tests: composer test
echo    3. Start dev server: php -S localhost:8000 -t public
echo.
pause

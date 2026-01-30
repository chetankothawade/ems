#!/bin/bash

set -e

echo "🚀 EMS Backend Setup Script"
echo "============================"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "⚠️  .env file not found. Copying from .env.example..."
    if [ -f .env.example ]; then
        cp .env.example .env
        echo "✅ Created .env file. Please update database credentials."
    else
        echo "❌ Error: .env.example not found. Please create .env manually."
        exit 1
    fi
fi

echo "📦 Installing PHP dependencies..."
composer install --prefer-dist

echo ""
echo "🗄️  Running database migrations..."
php bin/console migrations:migrate --no-interaction

echo ""
echo "🌱 Seeding database..."
php bin/console db:seed

echo ""
echo "✅ Setup complete!"
echo ""
echo "📋 Next steps:"
echo "   1. Update .env with your database credentials if not done"
echo "   2. Run tests: composer test"
echo "   3. Start dev server: php -S localhost:8000 -t public"
echo ""

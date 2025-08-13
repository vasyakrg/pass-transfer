#!/bin/bash

echo "🔍 Checking PassTransfer environment..."

# Check if containers are running
echo "🐳 Checking Docker containers..."
if docker-compose ps | grep -q "Up"; then
    echo "✅ Containers are running"
else
    echo "❌ Containers are not running"
    echo "Run: docker-compose up -d"
    exit 1
fi

# Check web application
echo "🌐 Checking web application..."
if curl -s http://localhost:8080 > /dev/null; then
    echo "✅ Web application is accessible at http://localhost:8080"
else
    echo "❌ Web application is not accessible"
fi

# Check MySQL connection
echo "🗄️ Checking MySQL connection..."
if docker-compose exec -T db mysql -u passuser -ppass123 -e "SELECT 1" > /dev/null 2>&1; then
    echo "✅ MySQL is accessible"
else
    echo "❌ MySQL is not accessible"
fi

# Check database migration
echo "🔗 Testing database migration..."
if docker-compose exec -T app php /var/www/html/docker/migrate.php; then
    echo "✅ Database migration successful"
else
    echo "❌ Database migration failed"
fi

echo "✅ Environment check complete!"
echo ""
echo "📝 Access the application at: http://localhost:8080"
echo "🗄️ MySQL is available at: localhost:3306"
echo "👤 MySQL credentials: passuser/pass123"

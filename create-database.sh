#!/bin/bash

echo "Creating WordPress database..."
echo ""

sudo mysql << 'MYSQL_COMMANDS'
-- Create database
CREATE DATABASE IF NOT EXISTS wordpress_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER IF NOT EXISTS 'wordpress_user'@'localhost' IDENTIFIED BY 'wordpress_password_123';

-- Grant privileges
GRANT ALL PRIVILEGES ON wordpress_db.* TO 'wordpress_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

-- Show success
SELECT '✓ Database created successfully!' AS Status;
SELECT '✓ User created successfully!' AS Status;
SELECT '✓ Privileges granted!' AS Status;

-- Show databases
SHOW DATABASES LIKE 'wordpress_db';
MYSQL_COMMANDS

echo ""
echo "Testing connection..."
mysql -u wordpress_user -pwordpress_password_123 -e "USE wordpress_db; SELECT 'Connection works!' AS Test;" 2>/dev/null

if [ $? -eq 0 ]; then
    echo ""
    echo "=========================================="
    echo "✓ SUCCESS! Database is ready!"
    echo "=========================================="
    echo ""
    echo "Database Name: wordpress_db"
    echo "Username: wordpress_user"
    echo "Password: wordpress_password_123"
    echo ""
    echo "Now starting WordPress on port 7000..."
    echo ""
    cd /home/saiful/wordpress && php -S localhost:7000
else
    echo ""
    echo "Connection test failed. Please check the output above."
fi


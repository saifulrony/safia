#!/bin/bash

echo "=========================================="
echo "WordPress Database Fix & Setup"
echo "=========================================="
echo ""

# Check if MySQL is running
if ! systemctl is-active --quiet mysql; then
    echo "Starting MySQL..."
    sudo systemctl start mysql
fi

echo "Step 1: Checking current database status..."
sudo mysql -e "SELECT User, Host FROM mysql.user WHERE User='wordpress_user';" 2>&1

echo ""
echo "Step 2: Dropping old user and database (if exists)..."
sudo mysql << 'EOF'
DROP USER IF EXISTS 'wordpress_user'@'localhost';
DROP DATABASE IF EXISTS wordpress_db;
EOF

echo ""
echo "Step 3: Creating fresh database..."
sudo mysql << 'EOF'
CREATE DATABASE wordpress_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SELECT 'Database created' AS Status;
EOF

echo ""
echo "Step 4: Creating user with password..."
sudo mysql << 'EOF'
CREATE USER 'wordpress_user'@'localhost' IDENTIFIED BY 'wordpress_password_123';
SELECT 'User created' AS Status;
EOF

echo ""
echo "Step 5: Granting privileges..."
sudo mysql << 'EOF'
GRANT ALL PRIVILEGES ON wordpress_db.* TO 'wordpress_user'@'localhost';
FLUSH PRIVILEGES;
SELECT 'Privileges granted' AS Status;
EOF

echo ""
echo "Step 6: Verifying setup..."
sudo mysql -e "SELECT User, Host FROM mysql.user WHERE User='wordpress_user';"
sudo mysql -e "SHOW DATABASES LIKE 'wordpress_db';"

echo ""
echo "Step 7: Testing connection..."
mysql -u wordpress_user -pwordpress_password_123 -e "USE wordpress_db; SELECT 'Connection successful!' AS Status;" 2>/dev/null

if [ $? -eq 0 ]; then
    echo ""
    echo "=========================================="
    echo "✓✓✓ SUCCESS! Database is ready! ✓✓✓"
    echo "=========================================="
    echo ""
    echo "Database credentials:"
    echo "  Database: wordpress_db"
    echo "  Username: wordpress_user"
    echo "  Password: wordpress_password_123"
    echo "  Host: localhost"
    echo ""
    echo "Now refresh your browser at:"
    echo "  http://localhost:7000"
    echo ""
else
    echo ""
    echo "❌ Connection test failed"
    echo "Please check the error messages above"
fi


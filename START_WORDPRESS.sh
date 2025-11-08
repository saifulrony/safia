#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo ""
echo -e "${BLUE}=========================================="
echo "WordPress + MySQL Setup & Start"
echo "==========================================${NC}"
echo ""

# Step 1: Check and start MySQL
echo -e "${YELLOW}[1/4] Checking MySQL status...${NC}"
if systemctl is-active --quiet mysql; then
    echo -e "${GREEN}✓ MySQL is running${NC}"
else
    echo -e "${RED}✗ MySQL is not running${NC}"
    echo "Starting MySQL..."
    sudo systemctl start mysql
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ MySQL started successfully${NC}"
    else
        echo -e "${RED}✗ Failed to start MySQL${NC}"
        echo "Please run manually: sudo systemctl start mysql"
        exit 1
    fi
fi

echo ""

# Step 2: Create database
echo -e "${YELLOW}[2/4] Creating WordPress database...${NC}"
sudo mysql << 'EOF' 2>&1
CREATE DATABASE IF NOT EXISTS wordpress_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'wordpress_user'@'localhost' IDENTIFIED BY 'wordpress_password_123';
GRANT ALL PRIVILEGES ON wordpress_db.* TO 'wordpress_user'@'localhost';
FLUSH PRIVILEGES;
EOF

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Database created successfully${NC}"
else
    echo -e "${YELLOW}⚠ Database might already exist (this is OK)${NC}"
fi

echo ""

# Step 3: Verify database connection
echo -e "${YELLOW}[3/4] Testing database connection...${NC}"
mysql -u wordpress_user -pwordpress_password_123 -e "USE wordpress_db; SELECT 'Connection successful!' AS Status;" 2>/dev/null

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Database connection verified${NC}"
else
    echo -e "${RED}✗ Cannot connect to database${NC}"
    echo "Trying to fix permissions..."
    sudo mysql << 'EOF'
DROP USER IF EXISTS 'wordpress_user'@'localhost';
CREATE USER 'wordpress_user'@'localhost' IDENTIFIED BY 'wordpress_password_123';
GRANT ALL PRIVILEGES ON wordpress_db.* TO 'wordpress_user'@'localhost';
FLUSH PRIVILEGES;
EOF
    echo -e "${GREEN}✓ Permissions reset${NC}"
fi

echo ""

# Step 4: Install phpMyAdmin (optional)
echo -e "${YELLOW}[4/4] Setting up phpMyAdmin...${NC}"
if [ -d "/usr/share/phpmyadmin" ]; then
    echo -e "${GREEN}✓ phpMyAdmin is installed${NC}"
    if [ ! -L "/home/saiful/wordpress/phpmyadmin" ]; then
        ln -s /usr/share/phpmyadmin /home/saiful/wordpress/phpmyadmin 2>/dev/null
        echo -e "${GREEN}✓ phpMyAdmin symlink created${NC}"
    else
        echo -e "${GREEN}✓ phpMyAdmin symlink exists${NC}"
    fi
else
    echo -e "${YELLOW}⚠ phpMyAdmin not installed${NC}"
    echo "To install phpMyAdmin, run:"
    echo "  sudo apt update && sudo apt install phpmyadmin -y"
    echo "  ln -s /usr/share/phpmyadmin /home/saiful/wordpress/phpmyadmin"
fi

echo ""
echo -e "${GREEN}=========================================="
echo "Setup Complete!"
echo "==========================================${NC}"
echo ""
echo -e "${BLUE}Database Credentials:${NC}"
echo "  Database: wordpress_db"
echo "  Username: wordpress_user"
echo "  Password: wordpress_password_123"
echo ""
echo -e "${BLUE}Starting WordPress server on port 7000...${NC}"
echo ""
echo -e "${GREEN}Access your WordPress site at:${NC}"
echo "  → http://localhost:7000"
echo ""
if [ -d "/usr/share/phpmyadmin" ]; then
    echo -e "${GREEN}Access phpMyAdmin at:${NC}"
    echo "  → http://localhost:7000/phpmyadmin"
    echo ""
fi
echo -e "${YELLOW}Press Ctrl+C to stop the server${NC}"
echo "=========================================="
echo ""

# Kill any existing PHP server on port 7000
pkill -f "php -S localhost:7000" 2>/dev/null

# Start PHP server
cd /home/saiful/wordpress
php -S localhost:7000


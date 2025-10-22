#!/bin/bash

echo "=========================================="
echo "phpMyAdmin Installation for WordPress"
echo "=========================================="
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# Check if running with sudo
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Please run with sudo: sudo bash install-phpmyadmin.sh${NC}"
    exit 1
fi

echo -e "${YELLOW}Installing phpMyAdmin...${NC}"
echo ""

# Install phpMyAdmin
DEBIAN_FRONTEND=noninteractive apt install phpmyadmin -y

# Configure phpMyAdmin for Apache
if [ -f /etc/phpmyadmin/apache.conf ]; then
    echo -e "${YELLOW}Configuring phpMyAdmin for Apache...${NC}"
    
    # Enable phpMyAdmin configuration
    if [ ! -L /etc/apache2/conf-available/phpmyadmin.conf ]; then
        ln -s /etc/phpmyadmin/apache.conf /etc/apache2/conf-available/phpmyadmin.conf
    fi
    
    a2enconf phpmyadmin
fi

# Restart Apache
systemctl restart apache2

# Check if phpMyAdmin is accessible
if systemctl is-active --quiet apache2; then
    echo ""
    echo -e "${GREEN}=========================================="
    echo "✓ phpMyAdmin Installed Successfully!"
    echo "==========================================${NC}"
    echo ""
    echo -e "${GREEN}Access phpMyAdmin at:${NC}"
    echo "  → http://localhost/phpmyadmin"
    echo ""
    echo -e "${YELLOW}Login Credentials:${NC}"
    echo "  Username: wordpress_user"
    echo "  Password: wordpress_password_123"
    echo ""
    echo -e "${YELLOW}Or use MySQL root:${NC}"
    echo "  Username: root"
    echo "  Password: (your MySQL root password, or leave blank)"
    echo ""
    echo -e "${GREEN}Database:${NC} wordpress_db"
    echo ""
else
    echo -e "${RED}Apache is not running. Please install Apache2 first.${NC}"
    echo "Run: sudo bash /home/saiful/wordpress/setup-apache2.sh"
fi


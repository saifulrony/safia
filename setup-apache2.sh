#!/bin/bash

echo "=========================================="
echo "Apache2 + PHP + MySQL Setup for WordPress"
echo "=========================================="
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if running with sudo
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Please run with sudo: sudo bash setup-apache2.sh${NC}"
    exit 1
fi

# Step 1: Update package list
echo -e "${YELLOW}[1/8] Updating package list...${NC}"
apt update

# Step 2: Install Apache2
echo ""
echo -e "${YELLOW}[2/8] Installing Apache2...${NC}"
apt install apache2 -y

# Step 3: Install PHP and extensions
echo ""
echo -e "${YELLOW}[3/8] Installing PHP and extensions...${NC}"
apt install php libapache2-mod-php php-mysql php-curl php-gd php-mbstring php-xml php-xmlrpc php-soap php-intl php-zip -y

# Step 4: Enable Apache modules
echo ""
echo -e "${YELLOW}[4/8] Enabling Apache modules...${NC}"
a2enmod rewrite
a2enmod headers
a2enmod expires
a2enmod ssl

# Step 5: Configure WordPress virtual host
echo ""
echo -e "${YELLOW}[5/8] Creating Apache virtual host for WordPress...${NC}"

cat > /etc/apache2/sites-available/wordpress.conf <<'VHOST'
<VirtualHost *:80>
    ServerAdmin admin@localhost
    DocumentRoot /home/saiful/wordpress
    ServerName localhost

    <Directory /home/saiful/wordpress>
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/wordpress-error.log
    CustomLog ${APACHE_LOG_DIR}/wordpress-access.log combined
</VirtualHost>
VHOST

# Step 6: Set proper permissions
echo ""
echo -e "${YELLOW}[6/8] Setting file permissions...${NC}"
chown -R www-data:www-data /home/saiful/wordpress
chmod -R 755 /home/saiful/wordpress
chmod -R 775 /home/saiful/wordpress/wp-content

# Step 7: Enable site and disable default
echo ""
echo -e "${YELLOW}[7/8] Enabling WordPress site...${NC}"
a2dissite 000-default.conf
a2ensite wordpress.conf

# Step 8: Restart Apache
echo ""
echo -e "${YELLOW}[8/8] Restarting Apache2...${NC}"
systemctl restart apache2
systemctl enable apache2

# Check Apache status
if systemctl is-active --quiet apache2; then
    echo ""
    echo -e "${GREEN}=========================================="
    echo "✓✓✓ Apache2 Setup Complete! ✓✓✓"
    echo "==========================================${NC}"
    echo ""
    echo -e "${GREEN}Apache2 Status:${NC} Running"
    echo -e "${GREEN}WordPress URL:${NC} http://localhost"
    echo -e "${GREEN}Admin Panel:${NC} http://localhost/wp-admin"
    echo ""
    echo -e "${YELLOW}Database Credentials:${NC}"
    echo "  Database: wordpress_db"
    echo "  Username: wordpress_user"
    echo "  Password: wordpress_password_123"
    echo ""
    echo -e "${GREEN}Apache Configuration:${NC}"
    echo "  Config: /etc/apache2/sites-available/wordpress.conf"
    echo "  Error Log: /var/log/apache2/wordpress-error.log"
    echo "  Access Log: /var/log/apache2/wordpress-access.log"
    echo ""
    echo -e "${YELLOW}Useful Commands:${NC}"
    echo "  Restart Apache: sudo systemctl restart apache2"
    echo "  Check Status: sudo systemctl status apache2"
    echo "  View Error Log: sudo tail -f /var/log/apache2/wordpress-error.log"
    echo ""
else
    echo ""
    echo -e "${RED}❌ Apache2 failed to start. Check error logs:${NC}"
    echo "sudo journalctl -u apache2 -n 50"
    exit 1
fi

# Stop PHP development server if running
echo -e "${YELLOW}Stopping PHP development server (port 7000)...${NC}"
pkill -f "php -S localhost:7000" 2>/dev/null
echo -e "${GREEN}✓ PHP server stopped${NC}"
echo ""
echo -e "${GREEN}You can now access WordPress at: http://localhost${NC}"


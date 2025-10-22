#!/bin/bash

echo "=========================================="
echo "Fixing Apache2 for WordPress"
echo "=========================================="
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Create WordPress virtual host configuration
echo -e "${YELLOW}Creating WordPress virtual host...${NC}"

sudo tee /etc/apache2/sites-available/wordpress.conf > /dev/null <<'EOF'
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
EOF

echo -e "${GREEN}✓ Configuration created${NC}"

# Set permissions
echo -e "${YELLOW}Setting permissions...${NC}"
sudo chown -R www-data:www-data /home/saiful/wordpress
sudo chmod -R 755 /home/saiful/wordpress
sudo chmod -R 775 /home/saiful/wordpress/wp-content

echo -e "${GREEN}✓ Permissions set${NC}"

# Enable WordPress site, disable default
echo -e "${YELLOW}Enabling WordPress site...${NC}"
sudo a2dissite 000-default.conf
sudo a2ensite wordpress.conf

# Enable required modules
sudo a2enmod rewrite
sudo a2enmod headers

echo -e "${GREEN}✓ Site enabled${NC}"

# Restart Apache
echo -e "${YELLOW}Restarting Apache...${NC}"
sudo systemctl restart apache2

# Check status
if systemctl is-active --quiet apache2; then
    echo ""
    echo -e "${GREEN}=========================================="
    echo "✓✓✓ Apache2 Configured Successfully! ✓✓✓"
    echo "==========================================${NC}"
    echo ""
    echo -e "${GREEN}WordPress is now available at:${NC}"
    echo "  → http://localhost"
    echo ""
    echo -e "${GREEN}Admin Panel:${NC}"
    echo "  → http://localhost/wp-admin"
    echo ""
    echo -e "${GREEN}Create Demo Store:${NC}"
    echo "  → http://localhost/create-demo-products.php"
    echo ""
else
    echo -e "${RED}❌ Apache failed to start${NC}"
    echo "Check logs: sudo journalctl -u apache2 -n 50"
fi

# Stop PHP development server if running
pkill -f "php -S localhost:7000" 2>/dev/null && echo -e "${YELLOW}Stopped PHP dev server on port 7000${NC}"

echo ""


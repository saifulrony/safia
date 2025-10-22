#!/bin/bash

echo "=========================================="
echo "WordPress + MySQL + phpMyAdmin Setup"
echo "=========================================="
echo ""

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if running as root
if [ "$EUID" -eq 0 ]; then 
    echo -e "${RED}Please do not run as root. Run with: bash setup-wordpress-mysql.sh${NC}"
    exit 1
fi

echo -e "${YELLOW}This script will:${NC}"
echo "1. Install MySQL Server (if not installed)"
echo "2. Install phpMyAdmin"
echo "3. Create WordPress database and user"
echo "4. Configure wp-config.php"
echo "5. Start WordPress on port 7000"
echo ""
read -p "Continue? (y/n) " -n 1 -r
echo ""
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    exit 1
fi

# Step 1: Install MySQL Server
echo ""
echo -e "${YELLOW}Step 1: Checking MySQL installation...${NC}"
if ! command -v mysql &> /dev/null; then
    echo "MySQL not found. Installing..."
    sudo apt update
    sudo apt install mysql-server -y
else
    echo -e "${GREEN}MySQL is already installed${NC}"
fi

# Start MySQL service
echo "Starting MySQL service..."
sudo systemctl start mysql
sudo systemctl enable mysql
echo -e "${GREEN}MySQL service started${NC}"

# Step 2: Install phpMyAdmin
echo ""
echo -e "${YELLOW}Step 2: Installing phpMyAdmin...${NC}"
if [ ! -d "/usr/share/phpmyadmin" ]; then
    echo "Installing phpMyAdmin..."
    sudo apt install phpmyadmin -y
    
    # Create symlink to make phpMyAdmin accessible
    if [ ! -L "/home/saiful/wordpress/phpmyadmin" ]; then
        ln -s /usr/share/phpmyadmin /home/saiful/wordpress/phpmyadmin
    fi
    echo -e "${GREEN}phpMyAdmin installed${NC}"
else
    echo -e "${GREEN}phpMyAdmin is already installed${NC}"
    if [ ! -L "/home/saiful/wordpress/phpmyadmin" ]; then
        ln -s /usr/share/phpmyadmin /home/saiful/wordpress/phpmyadmin
    fi
fi

# Step 3: Create WordPress database and user
echo ""
echo -e "${YELLOW}Step 3: Creating WordPress database and user...${NC}"
echo "Please enter MySQL root password (or press Enter if no password set):"
read -s MYSQL_ROOT_PASS

# Generate random password for WordPress user
WP_DB_PASSWORD=$(openssl rand -base64 12)

# Create database and user
if [ -z "$MYSQL_ROOT_PASS" ]; then
    sudo mysql <<MYSQL_SCRIPT
CREATE DATABASE IF NOT EXISTS wordpress_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'wordpress_user'@'localhost' IDENTIFIED BY '${WP_DB_PASSWORD}';
GRANT ALL PRIVILEGES ON wordpress_db.* TO 'wordpress_user'@'localhost';
FLUSH PRIVILEGES;
SELECT 'Database and user created successfully!' as Status;
MYSQL_SCRIPT
else
    mysql -u root -p"${MYSQL_ROOT_PASS}" <<MYSQL_SCRIPT
CREATE DATABASE IF NOT EXISTS wordpress_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'wordpress_user'@'localhost' IDENTIFIED BY '${WP_DB_PASSWORD}';
GRANT ALL PRIVILEGES ON wordpress_db.* TO 'wordpress_user'@'localhost';
FLUSH PRIVILEGES;
SELECT 'Database and user created successfully!' as Status;
MYSQL_SCRIPT
fi

if [ $? -eq 0 ]; then
    echo -e "${GREEN}Database created successfully!${NC}"
    echo ""
    echo "Database credentials:"
    echo "  Database Name: wordpress_db"
    echo "  Username: wordpress_user"
    echo "  Password: ${WP_DB_PASSWORD}"
    echo ""
else
    echo -e "${RED}Failed to create database. Please check MySQL root password.${NC}"
    exit 1
fi

# Step 4: Generate WordPress security keys
echo ""
echo -e "${YELLOW}Step 4: Generating WordPress security keys...${NC}"
SALT=$(curl -s https://api.wordpress.org/secret-key/1.1/salt/)

# Step 5: Create wp-config.php
echo ""
echo -e "${YELLOW}Step 5: Creating wp-config.php...${NC}"

cat > /home/saiful/wordpress/wp-config.php <<'WPCONFIG'
<?php
/**
 * The base configuration for WordPress
 *
 * @package WordPress
 */

// ** Database settings ** //
define( 'DB_NAME', 'wordpress_db' );
define( 'DB_USER', 'wordpress_user' );
define( 'DB_PASSWORD', 'DB_PASSWORD_PLACEHOLDER' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 */
SALTS_PLACEHOLDER

/**#@-*/

/**
 * WordPress database table prefix.
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

/* Add any custom values between this line and the "stop editing" line. */

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
WPCONFIG

# Replace password placeholder
sed -i "s/DB_PASSWORD_PLACEHOLDER/${WP_DB_PASSWORD}/g" /home/saiful/wordpress/wp-config.php

# Replace salts placeholder
ESCAPED_SALT=$(echo "$SALT" | sed 's/[&/\]/\\&/g')
sed -i "s/SALTS_PLACEHOLDER/${ESCAPED_SALT}/g" /home/saiful/wordpress/wp-config.php

echo -e "${GREEN}wp-config.php created successfully!${NC}"

# Save credentials to file
cat > /home/saiful/wordpress/CREDENTIALS.txt <<CREDS
========================================
WordPress Database Credentials
========================================

Database Name: wordpress_db
Username: wordpress_user
Password: ${WP_DB_PASSWORD}
Host: localhost

========================================
Access URLs
========================================

WordPress: http://localhost:7000
phpMyAdmin: http://localhost:7000/phpmyadmin

========================================
phpMyAdmin Login
========================================

Username: wordpress_user
Password: ${WP_DB_PASSWORD}

Or use MySQL root credentials if you have them.

========================================
CREDS

chmod 600 /home/saiful/wordpress/CREDENTIALS.txt

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Setup completed successfully!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "Credentials saved to: /home/saiful/wordpress/CREDENTIALS.txt"
echo ""
echo "To start WordPress, run:"
echo -e "${YELLOW}  cd /home/saiful/wordpress${NC}"
echo -e "${YELLOW}  php -S localhost:7000${NC}"
echo ""
echo "Then visit:"
echo "  WordPress: http://localhost:7000"
echo "  phpMyAdmin: http://localhost:7000/phpmyadmin"
echo ""
echo -e "${YELLOW}Note: Keep CREDENTIALS.txt safe and don't commit it to version control!${NC}"
echo ""


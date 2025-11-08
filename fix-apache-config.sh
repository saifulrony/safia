#!/bin/bash
# Fix Apache configuration to point to WordPress directory
# Run this script with: sudo bash fix-apache-config.sh

echo "Fixing Apache configuration..."

# Backup current config
cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf.backup.$(date +%Y%m%d_%H%M%S)

# Update DocumentRoot
sed -i 's|DocumentRoot /var/www/html|DocumentRoot /home/saiful/wordpress|g' /etc/apache2/sites-available/000-default.conf

# Add Directory block if it doesn't exist
if ! grep -q "<Directory /home/saiful/wordpress>" /etc/apache2/sites-available/000-default.conf; then
    sed -i '/DocumentRoot \/home\/saiful\/wordpress/a\
\
	<Directory /home/saiful/wordpress>\
		Options Indexes FollowSymLinks\
		AllowOverride All\
		Require all granted\
	</Directory>' /etc/apache2/sites-available/000-default.conf
fi

# Set proper permissions
chmod 755 /home/saiful
chmod -R 755 /home/saiful/wordpress
find /home/saiful/wordpress -type f -exec chmod 644 {} \;

# Test Apache configuration
echo "Testing Apache configuration..."
apache2ctl configtest

if [ $? -eq 0 ]; then
    echo "Configuration is valid. Restarting Apache..."
    systemctl restart apache2
    echo "Apache restarted successfully!"
    echo ""
    echo "Your WordPress site should now be accessible at:"
    echo "http://$(hostname -I | awk '{print $1}')"
    echo "http://192.168.177.129"
else
    echo "ERROR: Apache configuration test failed!"
    echo "Restoring backup..."
    cp /etc/apache2/sites-available/000-default.conf.backup.* /etc/apache2/sites-available/000-default.conf
    exit 1
fi


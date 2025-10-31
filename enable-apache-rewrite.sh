#!/bin/bash
# Enable Apache mod_rewrite for pretty URLs

echo "🔧 Enabling Apache mod_rewrite..."

# Enable mod_rewrite module
sudo a2enmod rewrite

# Restart Apache
sudo systemctl restart apache2

echo "✅ Done! Apache mod_rewrite is now enabled."
echo ""
echo "Test your URL now:"
echo "http://192.168.10.203:7000/asdf21/"
echo ""
echo "It should work without index.php!"


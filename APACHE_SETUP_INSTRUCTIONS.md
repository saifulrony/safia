# 🚀 Apache2 Setup for WordPress

## ⚡ Quick Setup

Run this **ONE** command:

```bash
sudo bash /home/saiful/wordpress/setup-apache2.sh
```

That's it! The script will:
1. ✅ Install Apache2
2. ✅ Install PHP and all required extensions
3. ✅ Enable necessary Apache modules (rewrite, headers, etc.)
4. ✅ Create WordPress virtual host configuration
5. ✅ Set proper file permissions
6. ✅ Configure and start Apache2
7. ✅ Stop the PHP development server (port 7000)

---

## 🌐 After Setup

**Access your WordPress site:**
- **Website**: http://localhost
- **Admin Panel**: http://localhost/wp-admin

**Database Info** (already configured in wp-config.php):
- Database: `wordpress_db`
- Username: `wordpress_user`
- Password: `wordpress_password_123`

---

## 🔧 What Gets Installed

### Apache2 Modules Enabled:
- ✅ `mod_rewrite` - Pretty URLs (permalinks)
- ✅ `mod_headers` - HTTP headers control
- ✅ `mod_expires` - Browser caching
- ✅ `mod_ssl` - HTTPS support (for future use)

### PHP Extensions Installed:
- ✅ `php-mysql` - Database connectivity
- ✅ `php-curl` - External HTTP requests
- ✅ `php-gd` - Image processing
- ✅ `php-mbstring` - String handling
- ✅ `php-xml` - XML parsing
- ✅ `php-xmlrpc` - XML-RPC support
- ✅ `php-soap` - SOAP protocol
- ✅ `php-intl` - Internationalization
- ✅ `php-zip` - ZIP file handling

---

## 📁 Apache Configuration

### Virtual Host Config Location:
```
/etc/apache2/sites-available/wordpress.conf
```

### Log Files:
- **Error Log**: `/var/log/apache2/wordpress-error.log`
- **Access Log**: `/var/log/apache2/wordpress-access.log`

### WordPress Directory:
```
/home/saiful/wordpress
```

---

## 🛠️ Useful Commands

### Manage Apache2:
```bash
# Restart Apache
sudo systemctl restart apache2

# Start Apache
sudo systemctl start apache2

# Stop Apache
sudo systemctl stop apache2

# Check Apache status
sudo systemctl status apache2

# Enable Apache on boot
sudo systemctl enable apache2
```

### View Logs:
```bash
# Watch error log in real-time
sudo tail -f /var/log/apache2/wordpress-error.log

# Watch access log in real-time
sudo tail -f /var/log/apache2/wordpress-access.log

# View last 50 lines of error log
sudo tail -50 /var/log/apache2/wordpress-error.log
```

### Test Apache Configuration:
```bash
# Test config syntax
sudo apache2ctl configtest

# Show loaded modules
sudo apache2ctl -M

# Show virtual hosts
sudo apache2ctl -S
```

---

## 🔐 File Permissions

The script automatically sets proper permissions:
- **Owner**: `www-data:www-data` (Apache user)
- **Directories**: `755` (rwxr-xr-x)
- **wp-content**: `775` (rwxrwxr-x) for uploads

---

## 🆚 Port Difference

### Before (PHP Server):
- URL: http://localhost:7000
- Type: Development server
- Process: `php -S localhost:7000`

### After (Apache2):
- URL: http://localhost
- Type: Production server
- Service: `apache2.service`

The setup script automatically stops the PHP server on port 7000.

---

## 🔄 Switch Back to PHP Server (if needed)

If you ever want to use PHP's built-in server again:

```bash
# Stop Apache
sudo systemctl stop apache2

# Start PHP server
cd /home/saiful/wordpress && php -S localhost:7000
```

---

## 🐛 Troubleshooting

### Apache won't start:
```bash
# Check what's using port 80
sudo netstat -tlnp | grep :80

# Check Apache error logs
sudo journalctl -u apache2 -n 50
```

### Permission denied errors:
```bash
# Reset permissions
sudo chown -R www-data:www-data /home/saiful/wordpress
sudo chmod -R 755 /home/saiful/wordpress
sudo chmod -R 775 /home/saiful/wordpress/wp-content
```

### "Cannot write to wp-content" error:
```bash
# Make wp-content writable
sudo chmod -R 775 /home/saiful/wordpress/wp-content
```

### Clear Apache cache:
```bash
sudo rm -rf /var/cache/apache2/*
sudo systemctl restart apache2
```

---

## ✨ Benefits of Apache2

1. ✅ **Better WordPress Support**: Native `.htaccess` support
2. ✅ **Pretty URLs**: Automatic permalink rewriting
3. ✅ **Plugin Compatibility**: All plugins work out of the box
4. ✅ **Production Ready**: Suitable for live sites
5. ✅ **Security**: Better security modules available
6. ✅ **Performance**: Can handle more concurrent users
7. ✅ **Standards**: Industry standard for WordPress hosting

---

## 📚 Next Steps

After Apache2 is running:

1. **Access WordPress**: http://localhost
2. **Complete Installation**: Follow WordPress setup wizard
3. **Configure Theme Options**: Visit **Appearance → Theme Options** in admin
4. **Customize Site**: Use the new Theme Options panel to customize everything!

---

**Ready? Run the setup command:**

```bash
sudo bash /home/saiful/wordpress/setup-apache2.sh
```

🚀 **Your WordPress site will be live in under 2 minutes!**


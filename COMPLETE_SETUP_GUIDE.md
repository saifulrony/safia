# Complete WordPress + MySQL Setup Guide

## 🚀 **Method 1: Full Installation (Recommended)**

### **Step 1: Install Required Packages**

Open your terminal and run these commands:

```bash
# Update package list
sudo apt update

# Install PHP and extensions
sudo apt install php8.3-cli php8.3-mysql php8.3-curl php8.3-gd php8.3-mbstring php8.3-xml php8.3-zip php8.3-fpm -y

# Install MySQL Server
sudo apt install mysql-server -y

# Install Apache (optional, for production)
sudo apt install apache2 -y

# Install phpMyAdmin (optional, for database management)
sudo apt install phpmyadmin -y
```

### **Step 2: Start Services**

```bash
# Start MySQL
sudo systemctl start mysql
sudo systemctl enable mysql

# Start Apache (if installed)
sudo systemctl start apache2
sudo systemctl enable apache2

# Check MySQL status
sudo systemctl status mysql
```

### **Step 3: Secure MySQL Installation**

```bash
# Run MySQL secure installation
sudo mysql_secure_installation
```

**Follow the prompts:**
- Set root password: `YES` (choose a strong password)
- Remove anonymous users: `YES`
- Disallow root login remotely: `YES`
- Remove test database: `YES`
- Reload privilege tables: `YES`

### **Step 4: Create WordPress Database**

```bash
# Login to MySQL
sudo mysql -u root -p

# Create database and user (run these in MySQL)
CREATE DATABASE wp_ecocommerce;
CREATE USER 'wp_user'@'localhost' IDENTIFIED BY 'your_password_here';
GRANT ALL PRIVILEGES ON wp_ecocommerce.* TO 'wp_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### **Step 5: Configure WordPress**

Edit the `wp-config.php` file:

```php
// Database settings
define( 'DB_NAME', 'wp_ecocommerce' );
define( 'DB_USER', 'wp_user' );
define( 'DB_PASSWORD', 'your_password_here' );
define( 'DB_HOST', 'localhost' );

// Security keys (generate at https://api.wordpress.org/secret-key/1.1/salt/)
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );
```

### **Step 6: Start WordPress**

```bash
cd /home/saiful/wordpress
php -S localhost:9000
```

Visit: `http://localhost:9000`

---

## 🛠️ **Method 2: Using XAMPP (Easier)**

### **Download and Install XAMPP**

1. **Download XAMPP:**
   - Go to: https://www.apachefriends.org/download.html
   - Download XAMPP for Linux
   - Make executable: `chmod +x xampp-linux-x64-8.2.12-0-installer.run`
   - Install: `sudo ./xampp-linux-x64-8.2.12-0-installer.run`

2. **Start XAMPP:**
   ```bash
   sudo /opt/lampp/lampp start
   ```

3. **Copy WordPress to XAMPP:**
   ```bash
   sudo cp -r /home/saiful/wordpress /opt/lampp/htdocs/
   ```

4. **Access WordPress:**
   - Go to: `http://localhost/wordpress`
   - phpMyAdmin: `http://localhost/phpmyadmin`

---

## 🐳 **Method 3: Using Docker (Advanced)**

### **Create Docker Compose File**

Create `docker-compose.yml`:

```yaml
version: '3.8'
services:
  wordpress:
    image: wordpress:latest
    ports:
      - "8080:80"
    environment:
      WORDPRESS_DB_HOST: db
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
    volumes:
      - ./wordpress:/var/www/html
    depends_on:
      - db

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
      MYSQL_ROOT_PASSWORD: rootpassword
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

### **Run with Docker**

```bash
# Install Docker
sudo apt install docker.io docker-compose -y

# Start services
docker-compose up -d

# Access WordPress
# Go to: http://localhost:8080
```

---

## 🎯 **Method 4: Local Development with SQLite**

### **Install SQLite Support**

```bash
sudo apt install php8.3-sqlite3 -y
```

### **Configure WordPress for SQLite**

Edit `wp-config.php`:

```php
// Use SQLite instead of MySQL
define('DB_DIR', '/home/saiful/wordpress/wp-content/database/');
define('DB_FILE', 'database.sqlite');
define('DB_NAME', 'database.sqlite');
define('DB_HOST', 'localhost');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// SQLite plugin (download from WordPress.org)
define('WP_USE_THEMES', true);
```

---

## 🔧 **Troubleshooting**

### **Common Issues:**

1. **MySQL Connection Failed:**
   ```bash
   # Check MySQL status
   sudo systemctl status mysql
   
   # Restart MySQL
   sudo systemctl restart mysql
   ```

2. **Permission Issues:**
   ```bash
   # Fix WordPress permissions
   sudo chown -R www-data:www-data /home/saiful/wordpress
   sudo chmod -R 755 /home/saiful/wordpress
   ```

3. **PHP Errors:**
   ```bash
   # Check PHP version
   php -v
   
   # Enable error reporting
   # Add to wp-config.php:
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   ```

### **Port Conflicts:**

If ports are busy, try different ports:

```bash
# Try different ports
php -S localhost:9000
php -S localhost:9001
php -S localhost:9002
```

---

## 📋 **Quick Setup Checklist**

- [ ] Install PHP 8.3+
- [ ] Install MySQL Server
- [ ] Create WordPress database
- [ ] Configure wp-config.php
- [ ] Set proper permissions
- [ ] Start PHP server
- [ ] Complete WordPress installation
- [ ] Activate EcoCommerce Pro theme

---

## 🎉 **After Setup**

1. **Visit WordPress:** `http://localhost:9000`
2. **Complete Installation Wizard**
3. **Login to Admin:** `http://localhost:9000/wp-admin`
4. **Activate Theme:** Appearance > Themes > EcoCommerce Pro
5. **Customize:** Appearance > Customize

---

## 📞 **Need Help?**

If you encounter issues:

1. Check error logs: `/var/log/apache2/error.log`
2. Check MySQL logs: `sudo tail -f /var/log/mysql/error.log`
3. Verify services: `sudo systemctl status mysql apache2`
4. Test PHP: `php -v`

**Your EcoCommerce Pro theme will be ready to use!** 🚀

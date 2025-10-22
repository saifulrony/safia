# 🗄️ phpMyAdmin Setup Guide

## ❌ Why http://localhost:7000/phpmyadmin Doesn't Work

The PHP development server (port 7000) **cannot run phpMyAdmin** because:
- ❌ It only serves files from the WordPress directory
- ❌ phpMyAdmin needs Apache or a full web server
- ❌ It doesn't support the phpMyAdmin configuration

---

## ✅ Solution: Use Apache2 + phpMyAdmin

### **Option 1: Full Setup (Recommended)**

Install both Apache2 and phpMyAdmin:

```bash
# Step 1: Install Apache2 (if not installed)
sudo bash /home/saiful/wordpress/setup-apache2.sh

# Step 2: Install phpMyAdmin
sudo bash /home/saiful/wordpress/install-phpmyadmin.sh
```

Then access:
- **WordPress**: http://localhost
- **phpMyAdmin**: http://localhost/phpmyadmin

---

### **Option 2: Quick phpMyAdmin Only**

If Apache2 is already running:

```bash
sudo bash /home/saiful/wordpress/install-phpmyadmin.sh
```

---

## 🌐 Access URLs

### **With Apache2** (Recommended):
- WordPress: **http://localhost**
- phpMyAdmin: **http://localhost/phpmyadmin**

### **With PHP Server** (Development only):
- WordPress: **http://localhost:7000**
- phpMyAdmin: ❌ **Not available**

---

## 🔐 phpMyAdmin Login

### **Option 1: WordPress Database User**
- **Username**: `wordpress_user`
- **Password**: `wordpress_password_123`
- **Access**: Only `wordpress_db` database

### **Option 2: MySQL Root User**
- **Username**: `root`
- **Password**: (your MySQL root password or leave blank)
- **Access**: All databases

---

## 🚀 Quick Start

### **Complete Setup (Apache2 + phpMyAdmin):**

```bash
# Run both scripts
sudo bash /home/saiful/wordpress/setup-apache2.sh
sudo bash /home/saiful/wordpress/install-phpmyadmin.sh
```

### **Then visit:**
- WordPress: http://localhost
- phpMyAdmin: http://localhost/phpmyadmin

---

## 🔧 Manual Installation (Alternative)

If you prefer to install manually:

```bash
# Install phpMyAdmin
sudo apt update
sudo apt install phpmyadmin -y

# During installation, select:
# - Web server: apache2
# - Configure database: Yes
# - Set phpMyAdmin password

# Enable phpMyAdmin in Apache
sudo ln -s /etc/phpmyadmin/apache.conf /etc/apache2/conf-available/phpmyadmin.conf
sudo a2enconf phpmyadmin
sudo systemctl restart apache2
```

---

## 📊 What You Get

### **phpMyAdmin Features:**
✅ Visual database management
✅ Browse tables and data
✅ Run SQL queries
✅ Import/Export databases
✅ User management
✅ Table structure editing
✅ Data search and filtering
✅ Database backup and restore

---

## 🆚 PHP Server vs Apache2

### **PHP Built-in Server (Port 7000)**
- ✅ Quick for development
- ✅ No installation needed
- ❌ Cannot run phpMyAdmin
- ❌ No .htaccess support
- ❌ Single-threaded (slow)
- ❌ Not for production

### **Apache2 (Port 80)**
- ✅ Production-ready
- ✅ Supports phpMyAdmin
- ✅ Full .htaccess support
- ✅ Multi-threaded (fast)
- ✅ Better plugin compatibility
- ✅ Industry standard

**Recommendation: Use Apache2** 🎯

---

## 🐛 Troubleshooting

### **phpMyAdmin not found (404 error)**

```bash
# Check if phpMyAdmin is installed
dpkg -l | grep phpmyadmin

# If not installed, run:
sudo bash /home/saiful/wordpress/install-phpmyadmin.sh
```

### **Access denied error**

```bash
# Test database connection
mysql -u wordpress_user -pwordpress_password_123

# If fails, recreate user:
sudo mysql -e "DROP USER IF EXISTS 'wordpress_user'@'localhost'; CREATE USER 'wordpress_user'@'localhost' IDENTIFIED BY 'wordpress_password_123'; GRANT ALL PRIVILEGES ON wordpress_db.* TO 'wordpress_user'@'localhost'; FLUSH PRIVILEGES;"
```

### **Apache not running**

```bash
# Check Apache status
sudo systemctl status apache2

# Start Apache
sudo systemctl start apache2

# If not installed
sudo bash /home/saiful/wordpress/setup-apache2.sh
```

### **Cannot write to directory error**

```bash
# Fix phpMyAdmin temp directory permissions
sudo mkdir -p /var/lib/phpmyadmin/tmp
sudo chown -R www-data:www-data /var/lib/phpmyadmin
sudo chmod -R 755 /var/lib/phpmyadmin
```

---

## 💡 Alternative: MySQL Command Line

If you don't want to install phpMyAdmin, you can use MySQL command line:

```bash
# Access MySQL
mysql -u wordpress_user -pwordpress_password_123 wordpress_db

# Common commands:
SHOW TABLES;                    # List all tables
SELECT * FROM wp_users;         # View users
SELECT * FROM wp_options;       # View options
DESCRIBE wp_posts;              # Show table structure
EXIT;                           # Exit MySQL
```

---

## 📝 Summary

### **To Get phpMyAdmin Working:**

1. **Install Apache2**:
   ```bash
   sudo bash /home/saiful/wordpress/setup-apache2.sh
   ```

2. **Install phpMyAdmin**:
   ```bash
   sudo bash /home/saiful/wordpress/install-phpmyadmin.sh
   ```

3. **Access**:
   - http://localhost/phpmyadmin

4. **Login**:
   - Username: `wordpress_user`
   - Password: `wordpress_password_123`

---

## ✅ After Setup

### **Your URLs:**
| Service | URL |
|---------|-----|
| WordPress Site | http://localhost |
| WordPress Admin | http://localhost/wp-admin |
| phpMyAdmin | http://localhost/phpmyadmin |
| Theme Options | http://localhost/wp-admin (sidebar → Theme Options) |

---

**Ready? Run these commands:**

```bash
sudo bash /home/saiful/wordpress/setup-apache2.sh
sudo bash /home/saiful/wordpress/install-phpmyadmin.sh
```

Then visit: **http://localhost/phpmyadmin** 🚀


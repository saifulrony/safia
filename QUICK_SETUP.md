# Quick WordPress Setup with MySQL and phpMyAdmin

## 🚀 Run These Commands in Your Terminal

### Step 1: Start MySQL Service
```bash
sudo systemctl start mysql
sudo systemctl enable mysql
```

### Step 2: Create WordPress Database
```bash
sudo mysql
```

Then in MySQL prompt, run:
```sql
CREATE DATABASE wordpress_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'wordpress_user'@'localhost' IDENTIFIED BY 'wordpress_password_123';
GRANT ALL PRIVILEGES ON wordpress_db.* TO 'wordpress_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 3: Install phpMyAdmin (Optional)
```bash
sudo apt update
sudo apt install phpmyadmin -y

# Create symlink for easy access
ln -s /usr/share/phpmyadmin /home/saiful/wordpress/phpmyadmin
```

### Step 4: I'll Create wp-config.php
The wp-config.php file will be created automatically with the database credentials.

### Step 5: Start WordPress Server
```bash
cd /home/saiful/wordpress
php -S localhost:7000
```

### Step 6: Access Your Sites
- **WordPress**: http://localhost:7000
- **phpMyAdmin**: http://localhost:7000/phpmyadmin

---

## 📝 Database Credentials

**Database Name**: wordpress_db  
**Username**: wordpress_user  
**Password**: wordpress_password_123  
**Host**: localhost

---

## 🔐 phpMyAdmin Login

Use the same credentials:
- **Username**: wordpress_user
- **Password**: wordpress_password_123

Or use your MySQL root account if you prefer.

---

## ⚠️ Important Notes

1. **Change the password**: The password `wordpress_password_123` is just for development. Change it for production!
2. **Port 7000**: WordPress will run on http://localhost:7000
3. **MySQL must be running**: Make sure MySQL service is active
4. **Theme compatibility**: MySQL ensures 100% compatibility with all WordPress themes and plugins (unlike SQLite)

---

## 🐛 Troubleshooting

### If MySQL won't start:
```bash
sudo systemctl status mysql
sudo journalctl -u mysql -n 50
```

### If you get connection errors:
```bash
# Test MySQL connection
mysql -u wordpress_user -p
# Enter password: wordpress_password_123
```

### If port 7000 is busy:
```bash
# Use a different port
php -S localhost:7001
# or
php -S localhost:8080
```


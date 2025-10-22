# 🔧 Fix Apache - WordPress Not Found Error

## ❌ The Problem

Apache is running but looking in the wrong directory:
- **Apache is looking at**: `/var/www/html` (empty)
- **WordPress is located at**: `/home/saiful/wordpress`

---

## ✅ The Solution (1 Command)

### **Run this command in your terminal:**

```bash
sudo bash /home/saiful/wordpress/fix-apache-wordpress.sh
```

This will:
1. ✅ Create proper Apache configuration
2. ✅ Point Apache to /home/saiful/wordpress
3. ✅ Set correct permissions
4. ✅ Enable WordPress site
5. ✅ Restart Apache

---

## 📋 Alternative: Manual Fix (Copy-Paste These Commands)

If the script doesn't work, run these commands one by one:

### **Step 1: Create Apache Configuration**
```bash
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
```

### **Step 2: Set Permissions**
```bash
sudo chown -R www-data:www-data /home/saiful/wordpress
sudo chmod -R 755 /home/saiful/wordpress
sudo chmod -R 775 /home/saiful/wordpress/wp-content
```

### **Step 3: Enable WordPress Site**
```bash
sudo a2dissite 000-default.conf
sudo a2ensite wordpress.conf
sudo a2enmod rewrite
sudo a2enmod headers
```

### **Step 4: Restart Apache**
```bash
sudo systemctl restart apache2
```

### **Step 5: Verify**
```bash
systemctl status apache2
```

---

## 🌐 After Fix

**Access WordPress:**
- Main Site: http://localhost
- Admin Panel: http://localhost/wp-admin
- Create Demo: http://localhost/create-demo-products.php

---

## 🔍 Check Current Status

### **Check where Apache is pointing:**
```bash
cat /etc/apache2/sites-enabled/*.conf | grep DocumentRoot
```

### **Check if WordPress files exist:**
```bash
ls -la /home/saiful/wordpress/ | head -10
```

### **Check Apache status:**
```bash
systemctl status apache2
```

---

## 💡 Quick Check

After running the fix, test with:
```bash
curl http://localhost
```

You should see HTML output (WordPress homepage), not "Not Found"!

---

## 🚀 Fastest Solution

**Just run this ONE command:**

```bash
sudo bash /home/saiful/wordpress/fix-apache-wordpress.sh
```

**Enter your password when prompted, then refresh your browser!**

---

## ✅ Expected Result

After running the command, you should see:

```
✓✓✓ Apache2 Configured Successfully! ✓✓✓

WordPress is now available at:
  → http://localhost

Admin Panel:
  → http://localhost/wp-admin

Create Demo Store:
  → http://localhost/create-demo-products.php
```

---

**Run the command now, then refresh http://localhost in your browser!** 🚀


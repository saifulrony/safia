# EcoCommerce Pro - WordPress Installation Guide

## 🚀 How to Install the Theme in WordPress

### **Step 1: Install PHP and MySQL (Required)**

First, you need to install PHP and MySQL on your system. Run these commands in your terminal:

```bash
# Update package list
sudo apt update

# Install PHP and required extensions
sudo apt install php8.3-cli php8.3-mysql php8.3-curl php8.3-gd php8.3-mbstring php8.3-xml php8.3-zip -y

# Install MySQL server
sudo apt install mysql-server -y

# Start MySQL service
sudo systemctl start mysql
sudo systemctl enable mysql
```

### **Step 2: Create Database**

```bash
# Create database for WordPress
sudo mysql -e "CREATE DATABASE wp_ecocommerce;"
sudo mysql -e "CREATE USER 'wp_user'@'localhost' IDENTIFIED BY 'wp_password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON wp_ecocommerce.* TO 'wp_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"
```

### **Step 3: Configure WordPress**

Edit the `wp-config.php` file with your database details:

```php
define( 'DB_NAME', 'wp_ecocommerce' );
define( 'DB_USER', 'wp_user' );
define( 'DB_PASSWORD', 'wp_password' );
define( 'DB_HOST', 'localhost' );
```

### **Step 4: Start WordPress Server**

```bash
cd /home/saiful/wordpress
php -S localhost:8000
```

### **Step 5: Complete WordPress Installation**

1. Open browser and go to: `http://localhost:8000`
2. Follow the WordPress installation wizard
3. Create admin account
4. Complete installation

### **Step 6: Activate EcoCommerce Pro Theme**

1. Go to **Appearance > Themes** in WordPress admin
2. Find "EcoCommerce Pro" theme
3. Click **Activate**

---

## 🎯 **Alternative: Manual Theme Installation**

If you already have WordPress running elsewhere, you can install the theme manually:

### **Method 1: Upload Theme ZIP**

1. Create a ZIP file of the theme:
```bash
cd /home/saiful/wp-theme
zip -r ecocommerce-pro.zip .
```

2. Go to WordPress Admin > Appearance > Themes
3. Click "Add New" > "Upload Theme"
4. Upload the `ecocommerce-pro.zip` file
5. Activate the theme

### **Method 2: FTP Upload**

1. Upload the theme folder to `/wp-content/themes/`
2. Go to WordPress Admin > Appearance > Themes
3. Find "EcoCommerce Pro" and activate it

---

## 🔧 **Theme Configuration**

After activation, configure the theme:

1. **Go to Appearance > Customize**
2. **Set up the following:**
   - Site Identity (Logo, Site Title)
   - Colors (Primary, Secondary colors)
   - Layout (Header, Footer options)
   - Social Media links
   - Typography settings

3. **Set up Menus:**
   - Go to Appearance > Menus
   - Create new menu
   - Add pages and assign to "Primary Menu"

4. **Configure Widgets:**
   - Go to Appearance > Widgets
   - Add widgets to Sidebar and Footer areas

---

## 🛍️ **WooCommerce Setup (Optional)**

To use ecommerce features:

1. **Install WooCommerce plugin:**
   - Go to Plugins > Add New
   - Search for "WooCommerce"
   - Install and activate

2. **Run WooCommerce Setup Wizard**
3. **Configure shop settings**
4. **Add sample products**

---

## 📱 **Testing the Theme**

1. **Visit your site:** `http://localhost:8000`
2. **Test responsive design:** Resize browser window
3. **Check all pages:** Home, Shop, About, Contact
4. **Test WooCommerce features:** Products, Cart, Checkout

---

## 🎉 **You're Ready!**

Your EcoCommerce Pro theme is now installed and ready to use! The theme includes:

- ✅ Professional ecommerce design
- ✅ WooCommerce integration
- ✅ Responsive layout
- ✅ Customization options
- ✅ Performance optimization
- ✅ SEO features
- ✅ Accessibility compliance

---

## 🆘 **Need Help?**

If you encounter any issues:

1. Check the theme documentation in `README.md`
2. Verify PHP and MySQL are running
3. Check file permissions
4. Review WordPress error logs

**Your EcoCommerce Pro theme is ready for a professional online store!** 🚀

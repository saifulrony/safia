# 🔗 WordPress URL Reference Guide

## Quick Access Links for Your WordPress Site

### 🌐 Network Access (From Any Device)
Use these URLs from phones, tablets, or other computers on your network:

#### Main URLs:
```
Homepage:     http://192.168.10.203:7000/
Shop Page:    http://192.168.10.203:7000/shop/
Admin Login:  http://192.168.10.203:7000/wp-admin/
```

### 💻 Local Access (From This Computer)
Use these URLs from this computer:

#### Main URLs:
```
Homepage:     http://localhost:7000/
Shop Page:    http://localhost:7000/shop/
Admin Login:  http://localhost:7000/wp-admin/
```

---

## 🔧 WordPress Admin Panel URLs

### Dashboard & Main Admin
```
Admin Dashboard:
http://192.168.10.203:7000/wp-admin/
http://localhost:7000/wp-admin/
```

### Products Management
```
All Products:
http://192.168.10.203:7000/wp-admin/edit.php?post_type=product
http://localhost:7000/wp-admin/edit.php?post_type=product

Add New Product:
http://192.168.10.203:7000/wp-admin/post-new.php?post_type=product
http://localhost:7000/wp-admin/post-new.php?post_type=product

Product Categories:
http://192.168.10.203:7000/wp-admin/edit-tags.php?taxonomy=product_cat&post_type=product
http://localhost:7000/wp-admin/edit-tags.php?taxonomy=product_cat&post_type=product

Product Tags:
http://192.168.10.203:7000/wp-admin/edit-tags.php?taxonomy=product_tag&post_type=product
http://localhost:7000/wp-admin/edit-tags.php?taxonomy=product_tag&post_type=product
```

### WooCommerce Settings
```
WooCommerce Dashboard:
http://192.168.10.203:7000/wp-admin/admin.php?page=wc-admin
http://localhost:7000/wp-admin/admin.php?page=wc-admin

WooCommerce Settings:
http://192.168.10.203:7000/wp-admin/admin.php?page=wc-settings
http://localhost:7000/wp-admin/admin.php?page=wc-settings

Orders:
http://192.168.10.203:7000/wp-admin/edit.php?post_type=shop_order
http://localhost:7000/wp-admin/edit.php?post_type=shop_order

Customers:
http://192.168.10.203:7000/wp-admin/admin.php?page=wc-admin&path=/customers
http://localhost:7000/wp-admin/admin.php?page=wc-admin&path=/customers
```

### Pages & Posts
```
All Pages:
http://192.168.10.203:7000/wp-admin/edit.php?post_type=page
http://localhost:7000/wp-admin/edit.php?post_type=page

Add New Page:
http://192.168.10.203:7000/wp-admin/post-new.php?post_type=page
http://localhost:7000/wp-admin/post-new.php?post_type=page

All Posts:
http://192.168.10.203:7000/wp-admin/edit.php
http://localhost:7000/wp-admin/edit.php

Add New Post:
http://192.168.10.203:7000/wp-admin/post-new.php
http://localhost:7000/wp-admin/post-new.php
```

### Media Library
```
Media Library:
http://192.168.10.203:7000/wp-admin/upload.php
http://localhost:7000/wp-admin/upload.php

Add New Media:
http://192.168.10.203:7000/wp-admin/media-new.php
http://localhost:7000/wp-admin/media-new.php
```

### Appearance & Themes
```
Themes:
http://192.168.10.203:7000/wp-admin/themes.php
http://localhost:7000/wp-admin/themes.php

Customize Theme:
http://192.168.10.203:7000/wp-admin/customize.php
http://localhost:7000/wp-admin/customize.php

Menus:
http://192.168.10.203:7000/wp-admin/nav-menus.php
http://localhost:7000/wp-admin/nav-menus.php

Widgets:
http://192.168.10.203:7000/wp-admin/widgets.php
http://localhost:7000/wp-admin/widgets.php

Theme Options:
http://192.168.10.203:7000/wp-admin/themes.php?page=ecocommerce-pro-options
http://localhost:7000/wp-admin/themes.php?page=ecocommerce-pro-options
```

### Plugins
```
Installed Plugins:
http://192.168.10.203:7000/wp-admin/plugins.php
http://localhost:7000/wp-admin/plugins.php

Add New Plugin:
http://192.168.10.203:7000/wp-admin/plugin-install.php
http://localhost:7000/wp-admin/plugin-install.php

Elementor (Page Builder):
http://192.168.10.203:7000/wp-admin/admin.php?page=elementor
http://localhost:7000/wp-admin/admin.php?page=elementor
```

### Users
```
All Users:
http://192.168.10.203:7000/wp-admin/users.php
http://localhost:7000/wp-admin/users.php

Add New User:
http://192.168.10.203:7000/wp-admin/user-new.php
http://localhost:7000/wp-admin/user-new.php

Your Profile:
http://192.168.10.203:7000/wp-admin/profile.php
http://localhost:7000/wp-admin/profile.php
```

### Settings
```
General Settings:
http://192.168.10.203:7000/wp-admin/options-general.php
http://localhost:7000/wp-admin/options-general.php

Reading Settings:
http://192.168.10.203:7000/wp-admin/options-reading.php
http://localhost:7000/wp-admin/options-reading.php

Permalinks:
http://192.168.10.203:7000/wp-admin/options-permalink.php
http://localhost:7000/wp-admin/options-permalink.php
```

---

## 🛍️ Frontend (Customer-Facing) URLs

### Main Pages
```
Homepage:
http://192.168.10.203:7000/
http://localhost:7000/

Shop (All Products):
http://192.168.10.203:7000/shop/
http://localhost:7000/shop/

Cart:
http://192.168.10.203:7000/cart/
http://localhost:7000/cart/

Checkout:
http://192.168.10.203:7000/checkout/
http://localhost:7000/checkout/

My Account:
http://192.168.10.203:7000/my-account/
http://localhost:7000/my-account/
```

### Product Pages
```
Individual products will be at:
http://192.168.10.203:7000/product/product-name/
http://localhost:7000/product/product-name/

Example:
http://192.168.10.203:7000/product/premium-wireless-headphones/
http://localhost:7000/product/premium-wireless-headphones/
```

---

## 🗄️ Database Management

### phpMyAdmin
```
phpMyAdmin:
http://192.168.10.203:7000/phpmyadmin
http://localhost:7000/phpmyadmin

Login:
  Username: wordpress_user
  Password: wordpress_password_123
  Database: wordpress_db
```

---

## 🚨 Common Mistakes to Avoid

### ❌ WRONG URLs (Will Give 404 Error):
```
❌ http://192.168.10.203:7000/edit.php?post_type=product
❌ http://localhost:7000/edit.php?post_type=product
❌ http://192.168.10.203:7000/admin/
❌ http://localhost:7000/admin/
```

### ✅ CORRECT URLs:
```
✅ http://192.168.10.203:7000/wp-admin/edit.php?post_type=product
✅ http://localhost:7000/wp-admin/edit.php?post_type=product
✅ http://192.168.10.203:7000/wp-admin/
✅ http://localhost:7000/wp-admin/
```

**Key Point:** Always include `/wp-admin/` before admin page names!

---

## 💡 Quick Tips

### Accessing Admin:
1. Go to: `http://192.168.10.203:7000/wp-admin/`
2. Login with your WordPress admin credentials
3. You'll see the dashboard with all options

### Viewing Products:
- **Admin View** (edit products): `/wp-admin/edit.php?post_type=product`
- **Customer View** (see shop): `/shop/`

### Creating Content:
- **Products**: Admin → Products → Add New
- **Pages**: Admin → Pages → Add New
- **Posts**: Admin → Posts → Add New

### Using Elementor:
1. Go to Pages or create a new page
2. Click "Edit with Elementor" button
3. Drag and drop to build your page

---

## 📱 Bookmark These!

**Most Used URLs:**

1. **Admin Dashboard**: http://192.168.10.203:7000/wp-admin/
2. **Products**: http://192.168.10.203:7000/wp-admin/edit.php?post_type=product
3. **Shop Page**: http://192.168.10.203:7000/shop/
4. **Add Product**: http://192.168.10.203:7000/wp-admin/post-new.php?post_type=product
5. **WooCommerce**: http://192.168.10.203:7000/wp-admin/admin.php?page=wc-admin

---

**Remember:** 
- All admin URLs start with `/wp-admin/`
- Use `192.168.10.203:7000` for network access
- Use `localhost:7000` for local access only

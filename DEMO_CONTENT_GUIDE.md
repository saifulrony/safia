# 📦 **DEMO CONTENT - HOW TO EDIT & CUSTOMIZE**

## 🎉 **Your Demo is Installed & Working!**

The demo content is live at: `http://localhost:7000/`

---

## 📍 **What Demo Content You Have**

### **✅ Pages:**
- Home (front page with hero, products, categories)
- Contact Us

### **✅ Products (9 items):**
1. Adjustable Dumbbells Set
2. Indoor Plant Collection
3. Professional Knife Set
4. Premium Yoga Mat
5. Essential Oils Gift Set
6. Daily Moisturizing Cream
7. Family Camping Tent
8. Running Sneakers Pro
9. (More products)

### **✅ Homepage Sections:**
- Hero Banner (large image + CTA buttons)
- Featured Products
- Product Categories
- New Arrivals
- Promo Banners
- Testimonials
- Newsletter Signup

---

## 🎨 **HOW TO EDIT THE DEMO**

### **1. Edit Products**

**Go to:**
```
http://localhost:7000/wp-admin/edit.php?post_type=product
```

**What you can edit:**
- Product names
- Prices
- Descriptions
- Images
- Categories
- Stock status
- Product tags

**To edit a product:**
1. Go to Products → All Products
2. Click product name
3. Edit title, price, description
4. Upload product images
5. Click "Update"

---

### **2. Edit Homepage Content**

**Go to:**
```
http://localhost:7000/wp-admin/admin.php?page=ecocommerce-pro-options&tab=homepage
```

**What you can customize:**

#### **Hero Section:**
- Hero title (currently: "Welcome to Our Store")
- Hero subtitle
- Button text & link
- Background image
- Enable/disable hero

#### **Section Toggles:**
- Turn ON/OFF: Featured Products
- Turn ON/OFF: Categories
- Turn ON/OFF: New Arrivals
- Turn ON/OFF: Testimonials
- Turn ON/OFF: Newsletter

**To edit:**
1. Go to Theme Options → Homepage tab
2. Change text, images, settings
3. Click "Save Changes"
4. Refresh homepage to see changes

---

### **3. Edit Product Categories**

**Go to:**
```
http://localhost:7000/wp-admin/edit-tags.php?taxonomy=product_cat&post_type=product
```

**What you can do:**
- Add new categories
- Edit category names
- Add category images
- Change category descriptions
- Set category order

**To add category images:**
1. Go to Products → Categories
2. Click category name
3. Upload "Thumbnail" image
4. Save

---

### **4. Edit Pages**

**Go to:**
```
http://localhost:7000/wp-admin/edit.php?post_type=page
```

**Pages you can edit:**
- **Home** - Main landing page
- **Contact Us** - Contact form page

**To edit with Elementor:**
1. Install Elementor (Theme Options → Page Builders → Install Elementor)
2. Go to Pages → All Pages
3. Click "Edit with Elementor"
4. Drag & drop elements
5. Click "Update"

---

### **5. Customize Header**

**Go to:**
```
http://localhost:7000/wp-admin/admin.php?page=ecocommerce-pro-options&tab=header
```

**What you can change:**
- **Header Template** - Choose from 9 designs
- **Logo** - Upload your logo
- **Cart Icon** - Show/hide
- **Search Bar** - Show/hide
- **Sticky Header** - Enable/disable
- **Top Bar** - Add promotional text
- **Colors** - Header background, text colors

**Example changes:**
1. Click "Modern Header" template
2. Turn OFF cart icon
3. Add top bar: "Free Shipping on Orders $50+"
4. Enable sticky header
5. Save!

---

### **6. Customize Footer**

**Go to:**
```
http://localhost:7000/wp-admin/admin.php?page=ecocommerce-pro-options&tab=footer
```

**What you can change:**
- Number of columns (1-4)
- Copyright text
- Show/hide social icons
- Show/hide back-to-top button
- Footer widgets

**To add footer content:**
1. Go to Appearance → Widgets
2. Find "Footer 1", "Footer 2", etc.
3. Add widgets (text, menu, categories)
4. Save

---

### **7. Change Colors**

**Go to:**
```
http://localhost:7000/wp-admin/admin.php?page=ecocommerce-pro-options&tab=colors
```

**What you can change:**
- **Primary Color** (buttons, links) - Click color picker
- **Secondary Color** (accents)
- **Text Color** (body text)
- **Heading Color** (titles)
- **Link Colors** (hover states)

**How to change:**
1. Click color field (opens color picker)
2. Select new color
3. Click "Save Changes"
4. Refresh site - colors updated!

---

### **8. Edit Menu**

**Go to:**
```
http://localhost:7000/wp-admin/nav-menus.php
```

**What you can do:**
- Add menu items
- Remove items
- Reorder items
- Create sub-menus (dropdowns)
- Add custom links

**To create a menu:**
1. Go to Appearance → Menus
2. Create new menu
3. Add pages, products, custom links
4. Assign to "Primary Menu" location
5. Save

---

## 🛍️ **HOW TO ADD MORE PRODUCTS**

### **Quick Way:**
1. Go to: `http://localhost:7000/wp-admin/post-new.php?post_type=product`
2. Enter product name
3. Add description
4. Set price
5. Upload images
6. Add to categories
7. Click "Publish"

### **Bulk Import:**
1. Go to Products → Import
2. Download sample CSV
3. Fill with your products
4. Upload CSV
5. Map columns
6. Import!

---

## 🎨 **POPULAR CUSTOMIZATIONS**

### **Change Homepage Hero:**
```
Theme Options → Homepage → Hero Section
- Hero Title: "Your Custom Title"
- Hero Subtitle: "Your tagline here"
- Upload hero background image
- Save!
```

### **Hide Cart Icon:**
```
Theme Options → Header → Cart Icon → Turn OFF → Save
```

### **Change Button Color:**
```
Theme Options → Colors → Primary Color → Pick red → Save
Result: All buttons become red!
```

### **Add Promo Bar:**
```
Theme Options → Header → Top Bar
- Enable Top Bar: ON
- Text: "🎉 50% Off - Limited Time!"
- Background: Pick color
- Save!
```

### **Change Footer Columns:**
```
Theme Options → Footer → Columns: 3 → Save
Result: Footer now has 3 columns!
```

---

## 📱 **EDIT WITH PAGE BUILDER**

### **Install Elementor:**
1. Go to: `http://localhost:7000/wp-admin/admin.php?page=ecocommerce-pro-builders`
2. Click "Install Elementor" button
3. Wait for installation
4. Click "Activate"

### **Edit Any Page:**
1. Go to Pages → All Pages
2. Hover over page
3. Click "Edit with Elementor"
4. Drag & drop elements:
   - Text
   - Images
   - Buttons
   - Products
   - Testimonials
5. Click "Update"

---

## 🗂️ **WHERE FILES ARE LOCATED**

### **Homepage Template:**
```
/wp-content/themes/ecocommerce-pro/front-page.php
```
This is the demo homepage you see!

### **Theme Options:**
Saved in WordPress database:
- `wp_options` table
- Option names: `ecocommerce_pro_*_options`

### **Products:**
```
WP Admin → Products → All Products
```

### **Product Images:**
```
/wp-content/uploads/
```

---

## 🔧 **QUICK ACCESS LINKS**

### **Content Management:**
- **All Products:** `http://localhost:7000/wp-admin/edit.php?post_type=product`
- **Add Product:** `http://localhost:7000/wp-admin/post-new.php?post_type=product`
- **Categories:** `http://localhost:7000/wp-admin/edit-tags.php?taxonomy=product_cat&post_type=product`
- **Pages:** `http://localhost:7000/wp-admin/edit.php?post_type=page`

### **Customization:**
- **Theme Options:** `http://localhost:7000/wp-admin/admin.php?page=ecocommerce-pro-options`
- **Menus:** `http://localhost:7000/wp-admin/nav-menus.php`
- **Widgets:** `http://localhost:7000/wp-admin/widgets.php`
- **Customize:** `http://localhost:7000/wp-admin/customize.php`

### **WooCommerce:**
- **Settings:** `http://localhost:7000/wp-admin/admin.php?page=wc-settings`
- **Orders:** `http://localhost:7000/wp-admin/edit.php?post_type=shop_order`

---

## ✅ **NEXT STEPS**

### **1. Customize Your Store:**
- Upload your logo
- Change colors to match your brand
- Edit product names & prices
- Add more product images

### **2. Set Up Shop:**
- Configure payment methods (WooCommerce → Settings → Payments)
- Set up shipping (WooCommerce → Settings → Shipping)
- Add tax rates if needed

### **3. Create Content:**
- Add About Us page
- Create Privacy Policy
- Add Terms & Conditions
- Create blog posts

### **4. Test Everything:**
- Try adding product to cart
- Test checkout process
- Check mobile responsiveness
- Test contact forms

---

## 🆘 **NEED HELP?**

### **Check Theme Options:**
Everything is controlled from:
```
http://localhost:7000/wp-admin/admin.php?page=ecocommerce-pro-options
```

### **Reset to Demo:**
If you mess up, you can re-import demo:
```
Theme Options → Import Demo → Click "Import Now"
```

---

**Your demo is fully installed and ready to customize!** 🎉✨

**Start here:** `http://localhost:7000/wp-admin/admin.php?page=ecocommerce-pro-options`


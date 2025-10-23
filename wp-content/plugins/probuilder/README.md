# ProBuilder - Advanced Page Builder

A powerful, feature-rich drag & drop page builder plugin for WordPress, similar to Elementor, Divi, and Muffin Builder.

## 🚀 Features

### **20+ Premium Widgets**
- **Layout**: Container
- **Basic**: Heading, Text Editor, Button, Image, Divider, Spacer
- **Advanced**: Tabs, Accordion, Image Carousel, Gallery
- **Content**: Image Box, Icon Box, Icon List, Progress Bar, Testimonial, Counter, Pricing Table, Video, Google Map

### **Visual Drag & Drop Builder**
- Intuitive drag and drop interface
- Live visual editing
- Real-time preview
- Easy element positioning

### **Responsive Design**
- Mobile-friendly widgets
- Responsive controls
- Adaptive layouts

### **Rich Styling Options**
- Colors, fonts, spacing controls
- Background options
- Border and shadow controls
- Custom CSS support

## 📦 Installation

1. **Upload the plugin**
   ```bash
   # The plugin is already in:
   /wp-content/plugins/probuilder/
   ```

2. **Activate the plugin**
   - Go to WordPress Admin → Plugins
   - Find "ProBuilder - Advanced Page Builder"
   - Click "Activate"

3. **Start building!**
   - Go to Pages → All Pages
   - Hover over any page
   - Click "Edit with ProBuilder"

## 🎨 How to Use

### Creating a New Page

1. **From WordPress Dashboard**
   - Go to ProBuilder menu
   - Click "Create New Page" button
   - Or go to Pages → Add New and click "Edit with ProBuilder"

2. **From Pages List**
   - Hover over any page
   - Click "Edit with ProBuilder"

### Using the Builder

#### **Sidebar Panel**
- Browse widgets by category (Layout, Basic, Advanced, Content)
- Search for widgets using the search box
- Access pre-made templates

#### **Canvas Area**
- Drag widgets from sidebar to canvas
- Click elements to select them
- Use element controls (Edit, Duplicate, Delete)
- Reorder elements by dragging

#### **Settings Panel**
- Edit element settings in real-time
- Switch between Content, Style, and Advanced tabs
- Customize colors, fonts, spacing, and more

#### **Header Controls**
- **Save**: Save your page
- **Preview**: Preview in new tab
- **Exit**: Return to WordPress editor

### Widget Guide

#### **Container**
Create sections to organize your content. Set background colors, padding, and width.

#### **Heading**
Add titles and headings. Choose from H1-H6, customize size, color, and alignment.

#### **Text Editor**
Add formatted text content with customizable styles.

#### **Button**
Create call-to-action buttons with icons, custom colors, and hover effects.

#### **Image**
Insert images with alignment and width controls.

#### **Tabs**
Create tabbed content sections for organized information display.

#### **Accordion**
Build collapsible content sections for FAQs and expandable content.

#### **Image Carousel**
Display multiple images in an auto-playing slideshow with navigation.

#### **Gallery**
Create responsive image grids with hover effects.

#### **Icon Box**
Feature boxes with icons, titles, and descriptions.

#### **Image Box**
Cards combining images with text content.

#### **Icon List**
Styled lists with custom icons for each item.

#### **Progress Bar**
Show skills, statistics, or progress with animated bars.

#### **Testimonial**
Display customer reviews with ratings, images, and quotes.

#### **Counter**
Animated number counters for statistics and achievements.

#### **Pricing Table**
Create beautiful pricing plans with features and CTAs.

#### **Video**
Embed YouTube, Vimeo, or self-hosted videos.

#### **Google Map**
Display location maps (requires Google Maps API key for production).

## ⚙️ Settings

### Configure ProBuilder

Go to **ProBuilder → Settings** to:
- Enable ProBuilder for specific post types
- Configure default settings
- Manage templates

## 🎯 Keyboard Shortcuts

- `Ctrl/Cmd + S` - Save page
- `Delete` - Delete selected element
- `Esc` - Close settings panel

## 🏗️ Architecture

### File Structure
```
probuilder/
├── probuilder.php          # Main plugin file
├── includes/               # Core classes
│   ├── class-base-widget.php
│   ├── class-widgets-manager.php
│   ├── class-editor.php
│   ├── class-frontend.php
│   ├── class-ajax.php
│   └── class-templates.php
├── widgets/                # Widget definitions
│   ├── container.php
│   ├── heading.php
│   ├── tabs.php
│   └── ... (20+ widgets)
├── assets/
│   ├── css/               # Stylesheets
│   │   ├── editor.css
│   │   ├── frontend.css
│   │   └── admin.css
│   └── js/                # JavaScript
│       ├── editor.js
│       └── frontend.js
└── templates/
    └── editor.php         # Builder template
```

### Technology Stack
- **Backend**: PHP 7.4+
- **Frontend**: jQuery, jQuery UI (Draggable, Droppable, Sortable)
- **Icons**: Font Awesome 6.4.0
- **Architecture**: OOP, MVC-inspired structure

## 🔧 Development

### Creating Custom Widgets

1. Create a new file in `widgets/` directory:

```php
<?php
class ProBuilder_Widget_Custom extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'custom-widget';
        $this->title = __('Custom Widget', 'probuilder');
        $this->icon = 'fa fa-star';
        $this->category = 'content';
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('My Custom Widget', 'probuilder'),
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $title = $this->get_settings('title', 'My Custom Widget');
        echo '<h3>' . esc_html($title) . '</h3>';
    }
}
```

2. Register in `probuilder.php`:

```php
require_once PROBUILDER_PATH . 'widgets/custom-widget.php';
```

3. Add to widgets manager in `includes/class-widgets-manager.php`.

## 📝 API Reference

### Widget Base Methods

- `get_name()` - Get widget name
- `get_title()` - Get widget title
- `get_icon()` - Get widget icon class
- `get_category()` - Get widget category
- `register_controls()` - Define widget controls
- `render()` - Render widget output
- `get_settings($key)` - Get control value

### Control Types

- `text` - Text input
- `textarea` - Textarea
- `select` - Dropdown
- `color` - Color picker
- `slider` - Range slider
- `url` - URL input
- `number` - Number input
- `media` - Image uploader
- `switcher` - Toggle switch
- `repeater` - Repeatable fields

## 🐛 Troubleshooting

### Plugin Not Showing
- Clear browser cache
- Check if plugin is activated
- Verify PHP version (7.4+)

### Elements Not Saving
- Check browser console for errors
- Verify AJAX URL is correct
- Check file permissions

### Drag & Drop Not Working
- Ensure jQuery UI is loaded
- Check for JavaScript conflicts
- Disable other page builders

## 🤝 Contributing

This plugin is designed to be extensible. You can:
- Add custom widgets
- Create widget templates
- Extend existing widgets
- Add new control types

## 📄 License

GPL v2 or later

## 🎉 Credits

Built with modern web technologies and WordPress best practices.

## 📞 Support

For issues and feature requests, please check the documentation or contact support.

---

**Version**: 1.0.0  
**Author**: ProBuilder Team  
**Requires**: WordPress 5.8+, PHP 7.4+


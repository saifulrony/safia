# ProBuilder Widgets Guide

Complete reference for all 20+ widgets in ProBuilder.

## 📑 Table of Contents

- [Layout Widgets](#layout-widgets)
- [Basic Widgets](#basic-widgets)
- [Advanced Widgets](#advanced-widgets)
- [Content Widgets](#content-widgets)

---

## Layout Widgets

### 🔲 Container

**Purpose**: Create sections and organize content

**Settings**:
- Layout: Boxed or Full Width
- Content Width: 500-1920px
- Min Height: 0-1000px
- Background Color
- Padding: Top, Right, Bottom, Left

**Use Cases**:
- Hero sections
- Content sections
- Footer areas
- Colored backgrounds
- Full-width banners

**Example**:
```
[Container: Blue background, 80px padding]
  ↳ Heading: "Welcome"
  ↳ Text: "Description"
  ↳ Button: "Get Started"
```

---

## Basic Widgets

### 📝 Heading

**Purpose**: Add titles and headings

**Settings**:
- Title text
- HTML Tag: H1, H2, H3, H4, H5, H6, div, span, p
- Alignment: Left, Center, Right, Justify
- Text Color
- Font Size: 10-100px
- Font Weight: Light, Normal, Semi Bold, Bold, Black

**Use Cases**:
- Page titles
- Section headings
- Sub-headings
- Feature titles

**Best Practices**:
- Use H1 for main page title
- H2 for section titles
- H3 for sub-sections
- Maintain hierarchy

---

### ✏️ Text Editor

**Purpose**: Add paragraphs and formatted text

**Settings**:
- Text content (textarea)
- Text Color
- Font Size: 10-50px
- Line Height: 1.0-3.0

**Use Cases**:
- Body content
- Descriptions
- Articles
- Paragraphs

**Best Practices**:
- Keep line height at 1.5-1.8 for readability
- Use 16px+ font size
- Break into paragraphs

---

### 🔘 Button

**Purpose**: Call-to-action buttons

**Settings**:
- Button Text
- Link URL
- Icon (Font Awesome)
- Icon Position: Left or Right
- Alignment: Left, Center, Right
- Background Color
- Text Color
- Padding
- Border Radius: 0-50px

**Use Cases**:
- CTA buttons
- Download links
- Navigation
- Form submits

**Best Practices**:
- Use clear action words
- Make buttons prominent
- Consistent styling
- Adequate padding

---

### 🖼️ Image

**Purpose**: Display images

**Settings**:
- Image upload/select
- Link URL (optional)
- Alignment: Left, Center, Right
- Width: 0-100%

**Use Cases**:
- Hero images
- Product photos
- Team photos
- Logos
- Decorative images

**Best Practices**:
- Optimize images before upload
- Use appropriate dimensions
- Add alt text for accessibility
- Consider mobile display

---

### ➖ Divider

**Purpose**: Visual separator between sections

**Settings**:
- Style: Solid, Dashed, Dotted
- Width: 0-100%
- Height: 1-10px
- Color
- Alignment: Left, Center, Right

**Use Cases**:
- Section separators
- Content breaks
- Visual rhythm
- List separators

---

### ⬆️ Spacer

**Purpose**: Add vertical spacing

**Settings**:
- Height: 0-500px

**Use Cases**:
- Section spacing
- Breathing room
- Vertical rhythm
- Layout control

**Best Practices**:
- Use consistently
- 20-60px for tight spacing
- 80-120px for section breaks

---

## Advanced Widgets

### 📂 Tabs

**Purpose**: Organize content in tabs

**Settings**:
- Tab Items (repeater):
  - Tab Title
  - Tab Content
- Tab Background Color
- Active Tab Background
- Tab Text Color

**Use Cases**:
- Product features
- Service descriptions
- FAQ sections
- Content organization

**Features**:
- Click to switch
- Multiple tabs
- Custom styling
- Responsive

---

### 📋 Accordion

**Purpose**: Collapsible content sections

**Settings**:
- Accordion Items (repeater):
  - Title
  - Content
- Title Background Color
- Title Text Color
- Content Background Color

**Use Cases**:
- FAQ pages
- Long content
- Expandable sections
- Mobile menus

**Features**:
- Click to expand/collapse
- Multiple items
- +/- indicators
- Smooth animation

---

### 🎠 Image Carousel

**Purpose**: Sliding image gallery

**Settings**:
- Images (multiple upload)
- Autoplay: Yes/No
- Autoplay Speed: milliseconds
- Aspect Ratio

**Use Cases**:
- Image slideshows
- Product galleries
- Portfolio showcases
- Testimonial images

**Features**:
- Auto-play
- Navigation buttons
- Smooth transitions
- Touch-friendly

---

### 🖼️ Gallery

**Purpose**: Image grid

**Settings**:
- Images (multiple upload)
- Columns: 2-6
- Gap: 0-50px

**Use Cases**:
- Photo galleries
- Portfolio grids
- Product collections
- Team photos

**Features**:
- Responsive grid
- Hover effects
- Multiple columns
- Adjustable spacing

---

## Content Widgets

### 🎴 Image Box

**Purpose**: Card with image and text

**Settings**:
- Image
- Title
- Description
- Link (optional)
- Text Alignment

**Use Cases**:
- Feature cards
- Service boxes
- Portfolio items
- Blog previews

**Features**:
- Hover effects
- Clickable
- Responsive
- Clean design

---

### 💎 Icon Box

**Purpose**: Feature box with icon

**Settings**:
- Icon (Font Awesome)
- Icon Color
- Icon Size: 20-100px
- Title
- Description
- Text Alignment

**Use Cases**:
- Features list
- Services
- Benefits
- Steps/Process

**Best Practices**:
- Use relevant icons
- Consistent sizing
- Clear titles
- Brief descriptions

---

### 📝 Icon List

**Purpose**: List with custom icons

**Settings**:
- List Items (repeater):
  - Text
  - Icon
- Icon Color
- Text Color

**Use Cases**:
- Feature lists
- Benefits
- Checkmarks
- Bullet points

**Features**:
- Custom icons per item
- Color control
- Clean layout
- Multiple items

---

### 📊 Progress Bar

**Purpose**: Show progress/skills

**Settings**:
- Title
- Percentage: 0-100%
- Show Percentage: Yes/No
- Bar Color
- Background Color
- Height: 10-50px

**Use Cases**:
- Skills display
- Statistics
- Project progress
- Completion status

**Features**:
- Animated
- Customizable colors
- Multiple bars
- Percentage display

---

### 💬 Testimonial

**Purpose**: Customer reviews

**Settings**:
- Content
- Image
- Name
- Title/Position
- Rating: 0-5 stars
- Alignment

**Use Cases**:
- Customer reviews
- Client feedback
- Team testimonials
- Social proof

**Features**:
- Star ratings
- Profile images
- Quote icon
- Professional design

---

### 🔢 Counter

**Purpose**: Animated number counters

**Settings**:
- Starting Number
- Ending Number
- Prefix (e.g., $)
- Suffix (e.g., +)
- Title
- Animation Duration
- Number Color
- Title Color
- Alignment

**Use Cases**:
- Statistics
- Achievements
- Milestones
- Fun facts

**Features**:
- Animated counting
- Viewport trigger
- Custom duration
- Prefix/suffix support

---

### 💰 Pricing Table

**Purpose**: Display pricing plans

**Settings**:
- Title
- Currency symbol
- Price
- Period (e.g., "per month")
- Features (repeater)
- Button Text
- Button Link
- Featured: Yes/No

**Use Cases**:
- Pricing pages
- Subscription plans
- Service packages
- Membership tiers

**Features**:
- Featured badge
- Feature lists
- CTA button
- Hover effects

---

### 🎥 Video

**Purpose**: Embed videos

**Settings**:
- Video Type: YouTube, Vimeo, Self-hosted
- Video URL
- Aspect Ratio: 16:9, 4:3, 21:9

**Use Cases**:
- Product demos
- Tutorials
- Promotional videos
- About videos

**Features**:
- Responsive
- Multiple platforms
- Aspect ratio control
- Embedded players

---

### 🗺️ Google Map

**Purpose**: Display location

**Settings**:
- Address
- Zoom: 1-20
- Height: 200-800px

**Use Cases**:
- Contact pages
- Office locations
- Event venues
- Store locator

**Note**: Requires Google Maps API key for production use.

---

## 🎨 Widget Categories Summary

| Category | Count | Use Case |
|----------|-------|----------|
| Layout | 1 | Page structure |
| Basic | 7 | Essential elements |
| Advanced | 4 | Interactive components |
| Content | 8 | Rich content modules |
| **Total** | **20** | **Complete toolkit** |

---

## 🔥 Popular Combinations

### Landing Page
```
Container (Hero)
  ↳ Heading
  ↳ Text
  ↳ Button

Container (Features)
  ↳ Heading
  ↳ Icon Box (x3)

Container (Testimonials)
  ↳ Heading
  ↳ Testimonial (x2)

Container (CTA)
  ↳ Heading
  ↳ Button
```

### About Page
```
Container
  ↳ Heading
  ↳ Image
  ↳ Text
  ↳ Progress Bar (x3)
  ↳ Counter (x4)
```

### Services Page
```
Container (Intro)
  ↳ Heading
  ↳ Text

Container (Services)
  ↳ Image Box (x3)

Container (Pricing)
  ↳ Pricing Table (x3)
```

---

## 💡 Widget Tips

1. **Container First**: Always start with a container
2. **Hierarchy**: Use proper heading levels (H1 → H2 → H3)
3. **Spacing**: Use spacers between major sections
4. **Colors**: Maintain color consistency
5. **Icons**: Use relevant, recognizable icons
6. **Images**: Optimize before uploading
7. **Text**: Keep it concise and readable
8. **Mobile**: Preview on mobile devices
9. **Performance**: Don't overuse heavy widgets
10. **Testing**: Test all interactive elements

---

## 🚀 Next Steps

1. Try each widget
2. Combine widgets creatively
3. Save favorite combinations as templates
4. Build complete pages
5. Experiment with styling

---

**Happy widget building! 🎨**


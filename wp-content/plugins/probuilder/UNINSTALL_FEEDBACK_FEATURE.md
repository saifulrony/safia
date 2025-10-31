# 🎯 Uninstall Feedback Feature - Complete Guide

## Overview

The Uninstall Feedback system collects valuable feedback from users when they deactivate ProBuilder. This helps you understand why users are leaving and improve the plugin.

---

## ✅ Features

### 1. **Beautiful Modal Popup**
- Appears when user clicks "Deactivate" on plugins page
- Professional gradient design
- Smooth animations
- Mobile-responsive

### 2. **Multiple Deactivation Reasons**
- ❌ It's not working / I found bugs
- 🔧 Missing features I need
- 😕 Too complex / Hard to use
- ⭐ Found a better plugin
- ⏸️ Temporary deactivation / Testing
- 📦 No longer need it
- 💬 Other reason

### 3. **Optional Detailed Feedback**
- Text area for additional comments
- Optional - users can skip if they want
- Helps understand specific issues

### 4. **Comprehensive Data Collection**
- Deactivation reason
- User's detailed feedback
- Site URL
- WordPress version
- PHP version
- ProBuilder version
- Number of pages created with ProBuilder
- User email
- Timestamp

### 5. **Developer Notifications**
- Email sent to admin when feedback is received
- Immediate notification of issues
- Can be customized or disabled

### 6. **Admin Dashboard**
- View all feedback in WordPress admin
- Statistics and charts
- Reasons breakdown
- Search and filter
- Export capability

---

## 🚀 How It Works

### User Flow:

1. **User clicks "Deactivate"** on ProBuilder in Plugins page
2. **Modal popup appears** instead of immediate deactivation
3. **User selects a reason** (or clicks "Skip")
4. **Optional**: User provides details
5. **User clicks "Submit & Deactivate"** or "Skip & Deactivate"
6. **Feedback is saved** to database
7. **Email sent** to developer (optional)
8. **Plugin deactivates** normally

### Developer Flow:

1. **Go to ProBuilder → Feedback** in WordPress admin
2. **View statistics**:
   - Total feedback received
   - Most common reason
   - Average pages created
3. **See breakdown chart** of all reasons
4. **Read detailed feedback** in table
5. **Take action** based on insights

---

## 📂 Files Created

### 1. **Main Class**
```
wp-content/plugins/probuilder/includes/class-uninstall-feedback.php
```
- Handles all feedback logic
- Creates database table
- Displays modal
- Saves feedback
- Sends emails

### 2. **Admin Template**
```
wp-content/plugins/probuilder/templates/admin-feedback.php
```
- Beautiful admin dashboard
- Statistics cards
- Reasons chart
- Feedback table

### 3. **Database Table**
```
wp_probuilder_feedback
```
- Stores all feedback data
- Created automatically on plugin activation

---

## 🎨 How to Access Feedback

### Method 1: WordPress Admin Menu
```
ProBuilder → Feedback
```

### Method 2: Direct URL
```
http://yoursite.com/wp-admin/admin.php?page=probuilder-feedback
```

---

## 📊 What Data is Collected

### Stored in Database:

| Field | Description | Example |
|-------|-------------|---------|
| `reason` | Why they're deactivating | "not-working" |
| `details` | Additional comments | "Conflicts with my theme" |
| `site_url` | User's website | "https://example.com" |
| `wp_version` | WordPress version | "6.4.2" |
| `php_version` | PHP version | "8.1.0" |
| `plugin_version` | ProBuilder version | "3.0.0" |
| `pages_created` | Pages built | 15 |
| `user_email` | User's email | "user@example.com" |
| `user_id` | WordPress user ID | 1 |
| `created_at` | When feedback submitted | "2025-01-15 10:30:00" |

---

## ⚙️ Configuration

### Change Email Recipient

Edit `class-uninstall-feedback.php`, line ~270:

```php
private function send_notification_email($reason, $details, $pages_created) {
    // Change this to your email
    $developer_email = 'your-email@example.com'; // <-- CHANGE THIS
    
    // Or use admin email:
    // $developer_email = get_option('admin_email');
```

### Disable Email Notifications

Comment out this line in `save_feedback()` method:

```php
// $this->send_notification_email($reason, $details, $pages_created);
```

### Customize Reasons

Edit the modal HTML in `get_modal_script()` method:

```javascript
<div class="probuilder-feedback-reason">
    <input type="radio" name="probuilder_reason" id="reason-X" value="your-reason">
    <label for="reason-X">Your Custom Reason</label>
</div>
```

### Customize Modal Design

Edit CSS in `get_modal_styles()` method to match your branding.

---

## 📈 Using the Data

### 1. **Identify Top Issues**
If many users say "It's not working":
- Check for compatibility issues
- Review error logs
- Test on different environments

### 2. **Feature Requests**
If many users say "Missing features":
- Read their detailed feedback
- Prioritize most requested features
- Add to roadmap

### 3. **Usability Problems**
If users say "Too complex":
- Improve documentation
- Add tutorials
- Simplify UI

### 4. **Competition Analysis**
If users say "Found a better plugin":
- Ask what features they prefer
- Research competitor advantages
- Add missing features

### 5. **Measure Success**
Track feedback over time:
- Decreasing "bugs" feedback = better quality
- Increasing "temporary" = users coming back
- Decreasing total feedback = better retention

---

## 🔧 Advanced Customization

### Add Custom Fields

In `create_feedback_table()` method:

```php
$sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    reason varchar(255) NOT NULL,
    details text,
    
    -- ADD YOUR CUSTOM FIELDS HERE
    user_role varchar(50),
    theme_name varchar(100),
    total_posts int(11),
    
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id)
) $charset_collate;";
```

Then collect data in `save_feedback()`:

```php
$wpdb->insert(
    $table_name,
    [
        'reason' => $reason,
        'details' => $details,
        // ... existing fields ...
        
        // ADD YOUR CUSTOM DATA
        'user_role' => $current_user->roles[0],
        'theme_name' => wp_get_theme()->get('Name'),
        'total_posts' => wp_count_posts()->publish,
    ],
    ['%s', '%s', /* ... */ '%s', '%s', '%d']
);
```

### Export to External Service

Add to `save_feedback()` method:

```php
// Send to your CRM or analytics service
wp_remote_post('https://your-analytics-api.com/feedback', [
    'body' => json_encode([
        'plugin' => 'ProBuilder',
        'reason' => $reason,
        'details' => $details,
        'site' => get_site_url(),
        'timestamp' => time()
    ]),
    'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer YOUR_API_KEY'
    ]
]);
```

### Add Slack Notifications

```php
private function send_slack_notification($reason, $details) {
    $webhook_url = 'https://hooks.slack.com/services/YOUR/WEBHOOK/URL';
    
    $message = [
        'text' => '🚨 ProBuilder Deactivation',
        'attachments' => [[
            'color' => '#ef4444',
            'fields' => [
                [
                    'title' => 'Reason',
                    'value' => ucwords(str_replace('-', ' ', $reason)),
                    'short' => true
                ],
                [
                    'title' => 'Site',
                    'value' => get_site_url(),
                    'short' => true
                ],
                [
                    'title' => 'Details',
                    'value' => $details ?: 'No details provided',
                    'short' => false
                ]
            ]
        ]]
    ];
    
    wp_remote_post($webhook_url, [
        'body' => json_encode($message),
        'headers' => ['Content-Type' => 'application/json']
    ]);
}
```

Then call in `save_feedback()`:

```php
$this->send_slack_notification($reason, $details);
```

---

## 🧪 Testing

### Test the Modal:

1. Go to **Plugins** page
2. Find ProBuilder
3. Click **"Deactivate"**
4. Modal should appear
5. Select a reason
6. Add details (optional)
7. Click **"Submit & Deactivate"**

### Test Skip Option:

1. Click **"Deactivate"**
2. Click **"Skip & Deactivate"** without selecting reason
3. Plugin should deactivate without saving feedback

### Test Admin Page:

1. Submit some test feedback (deactivate/reactivate)
2. Go to **ProBuilder → Feedback**
3. Should see statistics and table
4. Verify data is correct

### Test Email:

1. Deactivate and submit feedback
2. Check admin email inbox
3. Should receive notification email

---

## 📊 Sample Email Format

```
Subject: [ProBuilder] Deactivation Feedback - Not Working

New deactivation feedback received:

Reason: Not Working
Details: Conflicts with my theme's header

Site Info:
- URL: https://example.com
- WordPress: 6.4.2
- PHP: 8.1.0
- ProBuilder: 3.0.0
- Pages Created: 5
- Time: 2025-01-15 10:30:00
```

---

## 🎯 Best Practices

### 1. **Review Regularly**
- Check feedback weekly
- Look for patterns
- Prioritize common issues

### 2. **Respond When Possible**
- If user provides email, respond to their concerns
- Show you care about their experience
- Offer help or solutions

### 3. **Track Over Time**
- Export data monthly
- Create trends charts
- Measure improvement

### 4. **Act on Feedback**
- Fix bugs mentioned
- Add requested features
- Improve documentation

### 5. **Follow Up**
- If you fix an issue, email users who reported it
- Ask them to try again
- Build goodwill

---

## 📱 Mobile Responsiveness

The modal is fully responsive:
- ✅ Works on phones, tablets, desktops
- ✅ Touch-friendly buttons
- ✅ Readable on small screens
- ✅ Auto-adjusts layout

---

## 🔒 Privacy & GDPR

### Data Collected:
- User email (WordPress admin)
- Site URL
- Technical info (versions)

### Compliance:
1. **Inform users**: Add to privacy policy
2. **Allow deletion**: Users can request data removal
3. **Secure storage**: Data in WordPress database
4. **Purpose limitation**: Only for improvement

### Add to Privacy Policy:

```
When you deactivate ProBuilder, we may collect:
- Your site URL
- WordPress and PHP versions
- Number of pages created
- Your feedback (optional)

This data helps us improve the plugin. 
You can request deletion at any time.
```

---

## 🚨 Troubleshooting

### Modal Doesn't Appear

**Check:**
1. JavaScript console for errors
2. jQuery is loaded
3. Not conflicting with other plugins
4. Try in incognito mode

**Fix:** Clear browser cache

### Feedback Not Saving

**Check:**
1. Database table exists: `wp_probuilder_feedback`
2. User has `activate_plugins` capability
3. AJAX nonce is valid

**Fix:** 
```php
// Manually create table
ProBuilder_Uninstall_Feedback::instance()->create_feedback_table();
```

### Email Not Sending

**Check:**
1. WordPress mail function working
2. Email address is correct
3. Not blocked by spam filter

**Test:**
```php
wp_mail('your@email.com', 'Test', 'Testing WordPress mail');
```

### Admin Page Not Showing

**Check:**
1. User has `manage_options` capability
2. ProBuilder menu exists
3. Template file exists

**Fix:** Clear WordPress cache

---

## 📈 Analytics Integration

### Google Analytics Event

Add to `save_feedback()`:

```php
// Send to Google Analytics
?>
<script>
gtag('event', 'plugin_deactivate', {
    'event_category': 'ProBuilder',
    'event_label': '<?php echo $reason; ?>',
    'value': <?php echo $pages_created; ?>
});
</script>
<?php
```

### Track in Mixpanel

```php
wp_remote_post('https://api.mixpanel.com/track', [
    'body' => json_encode([
        'event' => 'ProBuilder Deactivation',
        'properties' => [
            'distinct_id' => get_site_url(),
            'reason' => $reason,
            'details' => $details,
            'pages_created' => $pages_created,
            'wp_version' => get_bloginfo('version')
        ]
    ])
]);
```

---

## 🎉 Success Metrics

Track these KPIs:

1. **Deactivation Rate**
   - Total deactivations / Total activations
   - Goal: < 10%

2. **Top Reason**
   - Most common deactivation reason
   - Goal: Change over time as you fix issues

3. **Pages Created Before Deactivation**
   - Average pages created
   - Goal: Increase over time

4. **Detailed Feedback Rate**
   - % of users providing details
   - Goal: > 50%

5. **Bug Reports**
   - "Not working" feedback count
   - Goal: Decrease over time

---

## 🔗 Resources

### Database Table Structure

```sql
CREATE TABLE wp_probuilder_feedback (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    reason varchar(255) NOT NULL,
    details text,
    site_url varchar(255),
    wp_version varchar(50),
    php_version varchar(50),
    plugin_version varchar(50),
    pages_created int(11) DEFAULT 0,
    user_email varchar(255),
    user_id bigint(20),
    created_at datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);
```

### Export Feedback Data

```sql
SELECT * FROM wp_probuilder_feedback 
ORDER BY created_at DESC 
INTO OUTFILE '/tmp/probuilder_feedback.csv'
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n';
```

---

## ✅ Feature Checklist

- [x] Modal popup on deactivation
- [x] Multiple reason options
- [x] Optional detailed feedback
- [x] Skip option (don't force feedback)
- [x] Database storage
- [x] Email notifications
- [x] Admin dashboard
- [x] Statistics & charts
- [x] Mobile responsive
- [x] Smooth animations
- [x] Non-intrusive
- [x] Privacy compliant
- [x] Easy to customize

---

## 🎯 Next Steps

1. **Test the feature** - Deactivate and see the modal
2. **View feedback** - Go to ProBuilder → Feedback
3. **Customize email** - Change recipient address
4. **Monitor data** - Check weekly for insights
5. **Take action** - Fix issues, add features
6. **Measure success** - Track improvements over time

---

**The Uninstall Feedback feature is now active!** 🎉

Try deactivating ProBuilder to see it in action, then reactivate to continue using it.


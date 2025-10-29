# Safia Products - Deployment & Distribution Guide

**Complete guide for securely packaging and distributing your licensed products**

---

## Quick Start Deployment

### Prerequisites

- [ ] License server is set up and running
- [ ] Products tested with license activation
- [ ] All documentation is complete
- [ ] EULA/Terms of Service finalized
- [ ] Company branding updated everywhere
- [ ] Version numbers updated

---

## Step-by-Step Deployment Process

### Step 1: Update Company Information

**Replace all placeholder URLs with your actual company domain:**

#### Files to Update:

**Plugin (`probuilder/includes/class-license-manager.php`):**
```php
// Line ~21 - Change license server URL
private $license_server = 'https://YOUR-DOMAIN.com/license-api/';

// Lines ~26-27 - Update company info
private $company_name = 'Your Company Name';
private $company_website = 'https://YOUR-DOMAIN.com';
```

**Theme (`ecocommerce-pro/inc/class-theme-license-manager.php`):**
```php
// Same changes as plugin
```

**Important URLs to Replace:**
- `https://yourdomain.com` → Your actual domain
- `https://safia.com` → Your company website
- `support@safia.com` → Your support email
- `legal@safia.com` → Your legal contact email

### Step 2: Verify License System Integration

**Check that license managers are loaded:**

**Plugin** (`probuilder/probuilder.php`):
```php
// Should already be included:
require_once PROBUILDER_PATH . 'includes/class-license-manager.php';
require_once PROBUILDER_PATH . 'includes/class-license-page.php';

// Should be initialized:
ProBuilder_License_Manager::instance();
ProBuilder_License_Page::instance();
```

**Theme** (`ecocommerce-pro/functions.php`):
```php
// Should already be included:
require_once get_template_directory() . '/inc/class-theme-license-manager.php';

// Should be initialized:
Safia_Theme_License_Manager::instance();
```

### Step 3: Update Version Numbers

**Plugin** (`probuilder/probuilder.php`):
```php
* Version: 3.0.0
```

**Theme** (`ecocommerce-pro/style.css`):
```css
Version: 2.0.0
```

**Update VERSION constants:**
```php
define('PROBUILDER_VERSION', '3.0.0');
```

### Step 4: Test License Activation

**Test Scenarios:**

1. **Fresh Install Without License:**
```
→ Install plugin/theme
→ Should show license activation notice
→ Navigate to license page
→ Verify UI is correct
```

2. **License Activation:**
```
→ Enter test license key
→ Click "Activate License"
→ Verify activation works
→ Check database for stored license
→ Verify no errors in console
```

3. **License Validation:**
```
→ Check that license status is "active"
→ Verify product features work
→ Check that updates are available
```

4. **License Deactivation:**
```
→ Deactivate license
→ Verify status changes
→ Check restrictions are applied
```

5. **Invalid License:**
```
→ Try activating fake license key
→ Should show error message
→ Should not activate
```

### Step 5: Generate Integrity Hashes

**First Activation Will Generate Hashes:**

When you first activate a license, the system will:
1. Calculate hash of critical files
2. Store in database (`probuilder_integrity_hash` option)
3. Use for future verification

**Verify it's working:**
```php
// In WordPress, run:
$hash = get_option('probuilder_integrity_hash');
var_dump($hash); // Should show a sha256 hash
```

### Step 6: Prepare Distribution Files

#### Plugin Distribution

**Create clean package:**

```bash
cd /home/saiful/wordpress/wp-content/plugins/
zip -r probuilder-safia-edition-v3.0.0.zip probuilder/ \
  -x "probuilder/.git/*" \
  -x "probuilder/node_modules/*" \
  -x "probuilder/*.md" \
  -x "probuilder/debug-*" \
  -x "probuilder/test-*" \
  -x "probuilder/check-*"
```

**Verify package contents:**
```bash
unzip -l probuilder-safia-edition-v3.0.0.zip | head -20
```

**Must include:**
- ✓ `probuilder.php` (with Safia branding)
- ✓ `includes/class-license-manager.php`
- ✓ `includes/class-license-page.php`
- ✓ `assets/css/license.css`
- ✓ `assets/js/license.js`
- ✓ All widget files
- ✓ All includes files
- ✓ `LICENSE.txt` (EULA)

**Must NOT include:**
- ✗ `.git/` directory
- ✗ `node_modules/`
- ✗ Test files (`test-*.php`, `debug-*.php`)
- ✗ Development documentation (`.md` files)
- ✗ Development scripts

#### Theme Distribution

```bash
cd /home/saiful/wordpress/wp-content/themes/
zip -r safia-theme-v2.0.0.zip ecocommerce-pro/ \
  -x "ecocommerce-pro/.git/*" \
  -x "ecocommerce-pro/node_modules/*" \
  -x "ecocommerce-pro/*.md"
```

**Must include:**
- ✓ `style.css` (with Safia branding)
- ✓ `functions.php` (with license manager)
- ✓ `inc/class-theme-license-manager.php`
- ✓ All template files
- ✓ `LICENSE.txt` (EULA)

### Step 7: Code Signing (Optional but Recommended)

**Generate GPG Key:**
```bash
gpg --gen-key
# Follow prompts
```

**Sign the packages:**
```bash
gpg --armor --detach-sign probuilder-safia-edition-v3.0.0.zip
gpg --armor --detach-sign safia-theme-v2.0.0.zip
```

**Distribute both the `.zip` and `.asc` files**

**Verification (customers can verify):**
```bash
gpg --verify probuilder-safia-edition-v3.0.0.zip.asc
```

### Step 8: Create Installation Documentation

**Create `INSTALLATION.txt` in package:**

```
SAFIA PROBUILDER - INSTALLATION GUIDE
=====================================

STEP 1: INSTALL THE PLUGIN/THEME
---------------------------------
1. Download the .zip file
2. WordPress Admin → Plugins → Add New → Upload Plugin
3. Choose the .zip file and click "Install Now"
4. Activate the plugin

STEP 2: ACTIVATE YOUR LICENSE
------------------------------
1. Go to ProBuilder → License in WordPress admin
2. Enter your license key (from purchase email)
3. Click "Activate License"
4. Wait for confirmation message

STEP 3: VERIFY ACTIVATION
--------------------------
1. Check that license status shows "Active"
2. Check that all features are available
3. Verify updates are available

TROUBLESHOOTING
---------------
- License won't activate?
  → Check internet connection
  → Verify license key is correct
  → Ensure domain matches purchase
  → Contact support@safia.com

- Installation issues?
  → Check PHP version (7.4+)
  → Check WordPress version (5.8+)
  → Disable conflicting plugins
  → Check error logs

SUPPORT
-------
Email: support@safia.com
Website: https://safia.com/support
Documentation: https://safia.com/docs

Copyright © 2025 Safia Technologies
```

---

## License Server Configuration

### Required Endpoints

Your license server must implement these endpoints:

#### 1. Activate License
```
POST /license-api/activate_license
```

**Parameters:**
- `license_key` - The license key
- `product_id` - Product identifier
- `domain` - Site domain
- `install_hash` - Installation ID
- `timestamp` - Request timestamp
- `signature` - HMAC signature

**Return:**
```json
{
  "status": "active",
  "customer_name": "Customer Name",
  "license_type": "single|multi|developer",
  "expires": "2026-10-28",
  "activations_left": 0,
  "max_activations": 1
}
```

#### 2. Check License
```
POST /license-api/check_license
```

**Called daily to verify license is still valid**

#### 3. Deactivate License
```
POST /license-api/deactivate_license
```

**Allows moving license to new domain**

### Signature Verification

**Server-side verification (PHP example):**
```php
function verify_signature($data) {
    $signature = $data['signature'];
    unset($data['signature']);
    
    // Secret must match the one in license manager
    $secret = 'safia_probuilder_' . YOUR_AUTH_SALT;
    
    ksort($data);
    $string = implode('|', $data);
    
    $expected = hash_hmac('sha256', $string, $secret);
    
    return hash_equals($expected, $signature);
}
```

### License Key Generation

**Format:** `XXXX-XXXX-XXXX-XXXX` (16 characters)

**Example generation:**
```php
function generate_license_key() {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $key = '';
    
    for ($i = 0; $i < 4; $i++) {
        for ($j = 0; $j < 4; $j++) {
            $key .= $chars[random_int(0, strlen($chars) - 1)];
        }
        if ($i < 3) $key .= '-';
    }
    
    return $key;
}
```

---

## Sales Integration

### Recommended Platforms

#### 1. WooCommerce + Software Licensing
**Best if you already use WordPress:**
- Install WooCommerce
- Add Software Licensing extension
- Create products
- Configure license generation
- Automatic delivery

#### 2. Easy Digital Downloads
**Great for digital products:**
- Lightweight
- Built for downloads
- Software Licensing add-on available
- Automatic license management

#### 3. Gumroad
**Simplest option:**
- No hosting needed
- Handles payments
- License keys via webhooks
- 10% commission

#### 4. Freemius
**Built for WordPress products:**
- Complete solution
- Hosted license server
- Handles everything
- 30% commission

### Post-Purchase Flow

1. **Customer Purchases**
   - Pays via your platform
   - Receives order confirmation

2. **License Generation**
   - System generates unique license key
   - Stores in database
   - Associates with customer

3. **Delivery**
   - Email with:
     - Download link
     - License key
     - Installation instructions
     - Support information

4. **Installation**
   - Customer downloads product
   - Installs on their site
   - Activates license key

5. **Validation**
   - Product contacts license server
   - Verifies license is valid
   - Enables all features

---

## Marketing Materials

### Product Descriptions

**ProBuilder - Safia Edition:**
```
Build stunning WordPress pages with ProBuilder - a professional 
Elementor-style page builder with 90+ premium widgets, advanced 
controls, and responsive design modes.

✓ 90+ Premium Widgets
✓ Drag & Drop Interface
✓ Fully Responsive
✓ Grid Layout System
✓ WooCommerce Integration
✓ Premium Support
✓ Regular Updates
✓ Lifetime License Available

Includes 12 months of updates and premium support.
30-day money-back guarantee.
```

**Safia Theme:**
```
A lightning-fast, professional WooCommerce theme designed for 
modern online stores. Optimized for conversions and speed.

✓ WooCommerce Optimized
✓ Lightning Fast Performance
✓ Mobile Responsive
✓ 10+ Homepage Layouts
✓ Advanced Customizer
✓ SEO Optimized
✓ Premium Support
✓ Regular Updates

Includes 12 months of updates and premium support.
30-day money-back guarantee.
```

### Screenshots to Include

1. Product overview
2. License activation page
3. Key features in action
4. Mobile responsive view
5. Customization options
6. Support/documentation
7. Before/after examples

---

## Maintenance Schedule

### Daily
- Monitor license activations
- Check for support tickets
- Review security logs

### Weekly  
- Check for piracy (Google alerts)
- Review customer feedback
- Update documentation if needed

### Monthly
- Security audit
- Backup customer database
- Review refund requests
- Check competitor updates

### Quarterly
- Major feature updates
- Security updates
- Performance optimizations
- Customer satisfaction survey

---

## Legal Compliance

### Required Documents

1. **EULA** ✓ (Already created: `SAFIA_LICENSE_EULA.md`)
2. **Privacy Policy** - Create for your website
3. **Terms of Service** - Create for your website
4. **Refund Policy** - Define your policy
5. **Cookie Policy** - If using analytics

### GDPR Compliance

**Data collected by license system:**
- Domain name
- License key (encrypted)
- IP address
- Installation ID
- WordPress/PHP versions

**You must:**
- Disclose in privacy policy
- Allow data deletion requests
- Store securely
- Not share with third parties
- Have data processing agreement

**Privacy Policy Section:**
```
LICENSE VALIDATION DATA

Our products collect minimal data for license validation:
- Your domain name
- License activation status  
- Installation ID (anonymized)
- IP address (for security)
- WordPress and PHP versions

This data is used solely for license validation and 
fraud prevention. It is encrypted, stored securely, and 
never sold or shared with third parties.

You may request deletion of this data by contacting
privacy@safia.com.
```

---

## Support Setup

### Documentation Portal

**Create docs for:**
- Installation guide
- License activation
- Troubleshooting common issues
- Feature tutorials
- Video walkthroughs
- FAQs
- Changelog

### Support Channels

1. **Email Support**
   - support@safia.com
   - Response time: 24-48 hours
   - Priority for active licenses

2. **Documentation**
   - https://safia.com/docs
   - Searchable knowledge base
   - Video tutorials

3. **Community Forum** (optional)
   - Peer-to-peer support
   - Feature requests
   - Showcase

### Support Email Templates

**License Activation Help:**
```
Subject: License Activation Assistance

Hi [Name],

I'm here to help with your license activation.

Please provide:
1. Your license key
2. The domain you're installing on
3. Any error messages you see

I'll get you activated within 24 hours!

Best regards,
[Your Name]
Safia Support Team
```

---

## Checklist: Ready to Launch?

### Technical
- [ ] License server is running
- [ ] Test license activation works
- [ ] All company URLs updated
- [ ] Version numbers correct
- [ ] EULA included in package
- [ ] Installation guide included
- [ ] Packages tested on clean WordPress
- [ ] No debug code in production
- [ ] All features work with license
- [ ] Security features tested

### Legal
- [ ] Copyright registered (recommended)
- [ ] EULA finalized
- [ ] Privacy policy created
- [ ] Terms of service created
- [ ] Refund policy defined
- [ ] GDPR compliance checked

### Marketing
- [ ] Product page created
- [ ] Screenshots prepared
- [ ] Demo site available
- [ ] Pricing decided
- [ ] Payment processor set up
- [ ] Email delivery automated

### Support
- [ ] Documentation written
- [ ] Support email set up
- [ ] Response time defined
- [ ] Support tools ready

---

## Post-Launch

### Monitor These Metrics

1. **Sales**
   - Conversion rate
   - Revenue
   - Average order value

2. **Activations**
   - Successful activations
   - Failed activations
   - Average time to activate

3. **Support**
   - Ticket volume
   - Common issues
   - Response time

4. **Security**
   - Piracy attempts
   - Integrity check failures
   - Suspicious activations

5. **Product**
   - Active installations
   - Feature usage
   - Error rates

### Continuous Improvement

- Release updates regularly (monthly)
- Add requested features
- Fix bugs promptly
- Improve documentation
- Enhance security
- Optimize performance

---

## Troubleshooting Common Issues

### "License won't activate"

**Possible causes:**
1. License server down
2. Invalid license key
3. Domain mismatch
4. Max activations reached
5. Firewall blocking requests

**Solution:**
```php
// Enable debug logging
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);

// Check debug.log for license errors
```

### "Updates not showing"

**Check:**
1. License is active
2. Update server is running
3. WordPress can connect to internet

### "Integrity check failing"

**Causes:**
1. Files modified after activation
2. FTP upload corruption
3. Plugin/theme update

**Fix:**
```php
// Reset integrity hash
delete_option('probuilder_integrity_hash');
delete_option('safia_theme_integrity_hash');
// Then reactivate license
```

---

## Advanced: Code Obfuscation

**Optional for extra security:**

### Using ionCube

```bash
# Install ionCube Encoder
# Encode critical files only
ioncube_encoder.sh \
  --encode 'class-license-manager.php' \
  --into 'encoded/' \
  --optimize max
```

**Pros:**
- Makes code unreadable
- Harder to crack

**Cons:**
- Requires ionCube loader on server
- Can break updates
- Adds complexity

**Recommendation:** Only if targeting high-value market

---

## Conclusion

You now have a comprehensive, secure licensing system!

**Key Points:**
- License activation required
- Domain-locked security
- Code integrity checks
- Professional distribution
- Legal protection

**Next Steps:**
1. Set up license server
2. Update all company URLs
3. Test thoroughly
4. Package products
5. Create sales page
6. Launch!

**Questions?** security@safia.com

---

**Document Version:** 1.0  
**Last Updated:** October 28, 2025  
**Copyright © 2025 Safia Technologies**


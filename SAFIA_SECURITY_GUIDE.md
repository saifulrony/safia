# Safia Products - Security & Protection Guide

**Comprehensive Security Implementation for Theme & Plugin**

---

## Table of Contents
1. [Security Overview](#security-overview)
2. [License Protection System](#license-protection-system)
3. [Anti-Piracy Measures](#anti-piracy-measures)
4. [Code Protection Techniques](#code-protection-techniques)
5. [Deployment Best Practices](#deployment-best-practices)
6. [License Server Setup](#license-server-setup)
7. [Monitoring & Enforcement](#monitoring--enforcement)
8. [Legal Protection](#legal-protection)

---

## Security Overview

### Multi-Layer Protection Strategy

Our products implement **7 layers of security**:

1. **License Activation** - Domain-locked license keys
2. **Code Integrity Checks** - Tamper detection
3. **Installation Fingerprinting** - Unique installation IDs
4. **Encrypted Communication** - HMAC signatures
5. **Daily Validation** - Regular license verification
6. **Activity Logging** - Security event tracking
7. **Legal Enforcement** - Copyright protection

---

## License Protection System

### How It Works

```
User Purchase → License Key Generated → Activation Required → Daily Validation
     ↓                    ↓                      ↓                    ↓
Domain Lock         Unique Hash          Installation ID      Server Check
```

### Key Components

#### 1. License Manager (`class-license-manager.php`)
**Location:** 
- Plugin: `/wp-content/plugins/probuilder/includes/class-license-manager.php`
- Theme: `/wp-content/themes/ecocommerce-pro/inc/class-theme-license-manager.php`

**Features:**
- License activation/deactivation
- Remote server validation
- Code integrity verification
- Security event logging
- Installation fingerprinting

#### 2. License Activation Page
**Features:**
- User-friendly activation interface
- License status display
- Security information
- Company branding
- Support information

#### 3. Installation Hash
**Purpose:** Unique identifier for each installation

**Calculation:**
```php
hash('sha256', site_url + admin_email + install_time + product_id)
```

**Benefits:**
- Prevents license sharing
- Tracks multiple activations
- Enables license transfer
- Identifies unauthorized use

---

## Anti-Piracy Measures

### 1. Domain Locking

**How it works:**
- License key is bound to specific domain
- Cannot be used on multiple domains (without proper license)
- Server validates domain on each check

**Implementation:**
```php
$response = call_license_server('activate_license', [
    'license_key' => $license_key,
    'domain' => get_site_url(),
    'install_hash' => $install_hash,
]);
```

### 2. Code Integrity Verification

**Purpose:** Detect file tampering

**Process:**
1. Calculate hash of critical files on first activation
2. Store hash in database
3. Recalculate on each check
4. Compare with stored hash
5. Log security event if mismatch

**Protected Files:**
- Plugin: `probuilder.php`, `class-license-manager.php`
- Theme: `functions.php`, `style.css`, `class-theme-license-manager.php`

### 3. Encrypted Signatures

**HMAC SHA-256 Signatures:**
```php
$signature = hash_hmac('sha256', $data_string, $secret_key);
```

**Prevents:**
- Request forgery
- Man-in-the-middle attacks
- License key generation
- Fake server responses

### 4. Activity Logging

**Logged Events:**
- License activation/deactivation
- Integrity check failures
- Server communication errors
- Invalid license attempts
- File modifications

**Log Format:**
```php
[
    'timestamp' => '2025-10-28 10:30:00',
    'event' => 'license_activated',
    'message' => 'License key activated successfully',
    'ip' => '192.168.1.100',
    'user_id' => 1
]
```

---

## Code Protection Techniques

### 1. PHP Code Obfuscation (Optional)

**Tools:**
- ionCube PHP Encoder
- Zend Guard
- SourceGuardian

**Pros:**
- Makes code unreadable
- Prevents easy modification
- Protects proprietary logic

**Cons:**
- Requires decoder on server
- Can impact performance
- Not foolproof (can be decoded)

**Recommendation:** Use for premium features only

### 2. License-Gated Features

**Implementation:**
```php
public function premium_feature() {
    if (!$this->is_license_active()) {
        return false; // or show upgrade notice
    }
    
    // Feature code here
}
```

**Benefits:**
- Features disabled without valid license
- Encourages legitimate purchases
- Allows trial/demo versions

### 3. Namespace & Class Prefixing

**Purpose:** Make code harder to extract and reuse

**Example:**
```php
namespace SafiaTechnologies\ProBuilder\Core;

class Safia_ProBuilder_License_Manager_v3 {
    // Unique naming makes extraction harder
}
```

### 4. Minification & Concatenation

**For JavaScript/CSS:**
```bash
npm install -g uglify-js
uglifyjs editor.js -c -m -o editor.min.js
```

**Benefits:**
- Harder to understand/modify
- Smaller file size
- Performance improvement

---

## Deployment Best Practices

### Pre-Deployment Checklist

- [ ] Update version numbers
- [ ] Update copyright dates
- [ ] Generate integrity hashes
- [ ] Test license activation
- [ ] Remove debug code
- [ ] Minify assets
- [ ] Test on clean WordPress install
- [ ] Verify all security features work
- [ ] Check for hardcoded credentials
- [ ] Review error messages (no sensitive info)

### Secure Distribution

1. **Never Distribute:**
   - With demo license keys
   - With disabled security checks
   - Through public repositories
   - Without code signing

2. **Always Include:**
   - EULA/License agreement
   - Copyright notices
   - Installation instructions
   - Support information

3. **Package Structure:**
```
safia-theme.zip
├── safia/
│   ├── inc/
│   │   └── class-theme-license-manager.php ✓
│   ├── functions.php ✓
│   ├── style.css ✓
│   └── LICENSE.txt ✓

probuilder-plugin.zip
├── probuilder/
│   ├── includes/
│   │   ├── class-license-manager.php ✓
│   │   └── class-license-page.php ✓
│   ├── probuilder.php ✓
│   └── LICENSE.txt ✓
```

---

## License Server Setup

### Requirements

You need to set up a license server to handle:
- License activation
- License deactivation  
- License validation
- License generation
- Customer management

### Server Endpoints

#### 1. Activate License
**Endpoint:** `POST /license-api/activate_license`

**Request:**
```json
{
    "license_key": "XXXX-XXXX-XXXX-XXXX",
    "product_id": "probuilder-safia-edition",
    "domain": "example.com",
    "install_hash": "abc123...",
    "timestamp": 1234567890,
    "signature": "hmac_signature..."
}
```

**Response (Success):**
```json
{
    "status": "active",
    "customer_name": "John Doe",
    "license_type": "single",
    "expires": "2026-10-28",
    "activations_left": 0,
    "max_activations": 1
}
```

**Response (Error):**
```json
{
    "status": "error",
    "message": "Invalid license key or maximum activations reached"
}
```

#### 2. Check License
**Endpoint:** `POST /license-api/check_license`

**Purpose:** Daily validation of license status

#### 3. Deactivate License
**Endpoint:** `POST /license-api/deactivate_license`

**Purpose:** Allow license transfer to new domain

### Recommended Solutions

#### Option 1: WordPress-Based License Server
**Plugin:** Easy Digital Downloads + Software Licensing Extension
- **Pros:** Easy setup, WordPress-based, proven
- **Cons:** Requires WordPress, monthly cost
- **Cost:** ~$199/year

#### Option 2: Custom Laravel/PHP API
- **Pros:** Full control, customizable
- **Cons:** Requires development
- **Cost:** Development time

#### Option 3: Third-Party Services
**Services:** 
- Freemius (freemius.com)
- Keygen (keygen.sh)
- **Pros:** Ready-made, reliable
- **Cons:** Monthly fees, less control

### Database Schema

**licenses table:**
```sql
CREATE TABLE licenses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    license_key VARCHAR(100) UNIQUE,
    product_id VARCHAR(100),
    customer_id INT,
    license_type ENUM('single', 'multi', 'developer'),
    status ENUM('active', 'inactive', 'expired', 'suspended'),
    max_activations INT DEFAULT 1,
    created_at DATETIME,
    expires_at DATETIME,
    INDEX(license_key),
    INDEX(customer_id)
);
```

**activations table:**
```sql
CREATE TABLE activations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    license_id INT,
    domain VARCHAR(255),
    install_hash VARCHAR(255),
    activated_at DATETIME,
    last_checked DATETIME,
    ip_address VARCHAR(45),
    FOREIGN KEY (license_id) REFERENCES licenses(id),
    UNIQUE(license_id, domain)
);
```

---

## Monitoring & Enforcement

### 1. Automated Monitoring

**Set up alerts for:**
- Multiple activations from same license
- Activations from suspicious domains
- Integrity check failures
- License sharing patterns
- Known piracy sites

### 2. Manual Audits

**Monthly checks:**
- Review activation logs
- Check for unusual patterns
- Monitor piracy sites/forums
- Search GitHub for code
- Check nulled theme sites

**Tools:**
- Google Alerts (for product name + "nulled")
- GitHub search
- DMCA takedown services

### 3. DMCA Takedowns

**When you find piracy:**
1. Document the violation (screenshots)
2. Send DMCA takedown notice
3. Contact hosting provider
4. Report to Google (for search results)

**DMCA Notice Template:**
```
To Whom It May Concern,

I am writing to notify you of copyright infringement on your platform.

Copyrighted Work: [Product Name]
Copyright Owner: Safia Technologies
Infringing URL: [URL]

I have a good faith belief that the use of this material is not authorized.
I swear, under penalty of perjury, that the information in this notification is accurate.

[Your Name]
[Your Contact Info]
```

---

## Legal Protection

### 1. Copyright Registration

**Highly Recommended:**
- Register copyright with US Copyright Office (or your country)
- Provides legal protection
- Required for statutory damages
- Cost: ~$65 per work

**Benefits:**
- Up to $150,000 statutory damages per violation
- Attorney fees can be recovered
- Easier to enforce rights

### 2. Trademark Protection

**Consider trademarking:**
- "Safia" brand name
- "ProBuilder - Safia Edition"
- Logo/visual elements

**Benefits:**
- Prevents similar names
- Stronger brand protection
- Can stop domain squatters

### 3. Terms of Service

**Include in your website:**
- License terms
- Usage restrictions
- Refund policy
- Support terms
- Privacy policy

### 4. Customer Agreements

**Require acceptance of:**
- EULA (End User License Agreement)
- Terms of Service
- Privacy Policy

**Implementation:**
- Checkbox during purchase
- Click-wrap agreement
- Logged acceptance

---

## Additional Protection Strategies

### 1. Update Mechanism Security

**Secure Updates:**
- Sign update packages with GPG
- Verify signature before applying
- Use HTTPS for downloads
- Check license before providing updates

### 2. Community Building

**Engaged community = Less piracy:**
- Excellent support
- Regular updates
- Active development
- Customer success stories
- Fair pricing

### 3. Freemium Model

**Consider:**
- Free basic version
- Premium paid features
- Makes piracy less appealing
- Builds user base

### 4. Value-Added Services

**Bundle with services that can't be pirated:**
- Priority support
- Custom development
- Training/documentation
- Cloud hosting/CDN
- Regular updates

---

## Testing Security

### Security Audit Checklist

Test these scenarios:

- [ ] Install without license - verify restrictions
- [ ] Activate with valid license - verify success
- [ ] Activate with invalid license - verify rejection
- [ ] Activate on multiple domains - verify blocking
- [ ] Modify core files - verify integrity check triggers
- [ ] Deactivate and reactivate - verify works
- [ ] Expired license - verify update blocking
- [ ] Network interruption - verify graceful handling
- [ ] Remove license manager class - verify detection

---

## Conclusion

**Remember:** 
- No protection is 100% foolproof
- Goal is to make piracy harder than buying
- Focus on value and customer satisfaction
- Legal protection is your ultimate backup
- Regular monitoring is essential

**The Best Anti-Piracy Measure:**
Build an amazing product with great support at a fair price. Make buying easier than pirating!

---

## Support & Questions

For questions about security implementation:
- Email: security@safia.com
- Documentation: https://safia.com/docs/security

---

**Document Version:** 1.0  
**Last Updated:** October 28, 2025  
**Copyright © 2025 Safia Technologies**


#  Subscription Based Content Platform

A video streaming platform that offers exclusive content through tiered subscription models. Users can access premium videos by selecting from various membership tiers (Basic, Standard, Premium), each offering different levels of content and features.

##  Table of Contents
- [About](#about)
- [Features](#features)
- [Technologies](#technologies)
- [Database Setup](#database-setup)
- [SMS Verification](#sms-verification)
- [Payment Integration](#payment-integration)
- [Login Credentials](#login-credentials)
- [Project Structure](#project-structure)
- [Usage Guide](#usage-guide)

##  About

This platform provides a Netflix-style subscription service where:
- Users subscribe to access exclusive video content
- Multiple membership tiers offer different content levels
- Content creators can upload and monetize their videos
- Administrators manage users, content, and subscriptions

The platform ensures a consistent revenue stream through recurring subscriptions while providing users with curated, high-quality video content.

<img src="https://github.com/WinTer1165/Subscription-Based-Content-Platform/blob/main/images/home.png?raw=true" width="600" />

<img src="https://github.com/WinTer1165/Subscription-Based-Content-Platform/blob/main/images/user%20register.png?raw=true" width="600" />

<img src="https://github.com/WinTer1165/Subscription-Based-Content-Platform/blob/main/images/browse.png?raw=true" width="600" />

<img src="https://github.com/WinTer1165/Subscription-Based-Content-Platform/blob/main/images/upload%20video.png?raw=true" width="600" />

<img src="https://github.com/WinTer1165/Subscription-Based-Content-Platform/blob/main/images/admin%20dashboard.png?raw=true" width="600" />


##  Features

### User Features
- **Tiered Subscriptions**: Basic, Standard, and Premium membership levels
- **Video Streaming**: Access to exclusive video content based on subscription tier
- **User Authentication**: Secure registration and login system
- **Password Recovery**: SMS-based verification via Twilio
- **Payment Processing**: PayPal sandbox integration for subscription payments
- **Responsive Design**: Optimized for both mobile and desktop viewing

### Admin Features
- **Content Management**: Upload and manage video content
- **User Management**: Monitor and manage user subscriptions
- **Analytics Dashboard**: Track platform usage and revenue
- **Single Admin System**: Secure single-administrator setup

##  Technologies

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Payment Gateway**: PayPal Sandbox API
- **SMS Service**: Twilio API
- **Local Development**: XAMPP

##  Database Setup

1. **Import Database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create a new database (if not exists)
   - Import the provided SQL file: `wintercat65_streamhub.sql`
   - The database contains all necessary tables for users, admins, subscriptions, and password resets

##  SMS Verification

### Twilio Configuration
The platform uses Twilio for sending SMS verification codes during password reset.

** Important Limitations:**
- Currently using a **free Twilio account** with limited capacity
- Only **one verified number**: `deno number` (without country code)
- This number is associated with username: `demo`
- SMS delivery may not always be reliable due to free tier limitations

### Troubleshooting SMS Issues
If you don't receive the verification code:

1. **Wait a few minutes** - SMS delivery may be delayed
2. **Check network connection** - Ensure stable connectivity
3. **Manual retrieval from database**:
   ```
   - Navigate to: localhost/phpmyadmin
   - Open database: wintercat65_streamhub
   - Go to table: passwordreset
   - Find the code for your username
   - Note: Codes expire after 10 minutes
   ```

## 💳 Payment Integration

### PayPal Sandbox (Demo Only)
The platform uses PayPal sandbox for demonstration purposes. **No real money is exchanged.**

**Test Credentials:**
```
Email: sb-9gp7n34269285@personal.example.com
Password: x@:o70EZ
```

### Payment Process
1. Select "PayPal" during checkout
2. Use the sandbox credentials above
3. Complete the demo payment
4. Account is registered and redirected to login page

**Alternative**: You can sign up without PayPal by selecting other payment options during registration.

## 🔐 Login Credentials

### User Accounts

**Registration Page**: `localhost/register.html`  
**Login Page**: `localhost/login.html`

**Demo User Accounts:**

| Account Type | Username | Password |
|-------------|----------|----------|
| Basic       | basic    | admin123 |
| Standard    | standard | admin123 |
| Premium     | premium  | admin123 |

### Admin Account

**Registration Page**: `localhost/admin_register.php`  
**Login Page**: `localhost/admin/login.html`

**Admin Credentials:**
```
Username: admin
Password: admin123
```

**⚠️ Admin Registration Note:**
- Only **one admin account** can exist
- If an admin already exists, registration will be closed
- To create a new admin, manually delete the existing one from the `admin` table in the database

## 📁 Project Structure

```
main project/
├── admin/                 # Admin panel files
├── admin-css/            # Admin stylesheets
├── admin-js/             # Admin JavaScript files
├── images/               # Image assets
├── php/                  # PHP backend files
├── uploads/              # User uploads directory
│   └── videos/          # Video storage
├── user-css/            # User interface stylesheets
├── user-js/             # User interface JavaScript
├── login.html           # User login page
├── register.html        # User registration page
├── admin_register.php   # Admin registration
└── wintercat65_streamhub.sql  # Database file
```

## 💻 Usage Guide

### For New Users
1. Navigate to `localhost/register.html`
2. Choose a subscription tier (Basic/Standard/Premium)
3. Complete registration with payment method
4. Login at `localhost/login.html`
5. Access video content based on your subscription tier

### For Administrators
1. Access admin panel at `localhost/admin/login.html`
2. Use admin credentials to login
3. Upload and manage video content
4. Monitor user subscriptions
5. View platform analytics

### Password Reset Process
1. Click "Forgot Password" on login page
2. Enter your username
3. Enter phone number (use `demo number` for testing)
4. Receive SMS verification code
5. Enter code to reset password
6. If SMS fails, retrieve code manually from database

## 🎯 Subscription Tiers

| Tier | Features | Content Access |
|------|----------|----------------|
| **Basic** | Standard quality streaming | Limited video library |
| **Standard** | HD quality streaming | Extended video library |
| **Premium** | 4K quality streaming | Full video library + exclusive content |

## ⚠️ Known Issues

- SMS verification limited to one phone number due to Twilio free tier
- PayPal sandbox may occasionally fail (demo environment)
- Only one admin account supported at a time
- Video upload size limited by PHP configuration

## 📝 Notes

- This is a demonstration/educational project
- PayPal integration uses sandbox environment only
- SMS features require Twilio account configuration
- Ensure XAMPP services are running before accessing the platform

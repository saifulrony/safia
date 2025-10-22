# WordPress Development Environment Script

## Overview
The `dev.sh` script is a comprehensive tool to start and manage your WordPress development environment with all necessary services.

## Features
- 🚀 **One-command startup** - Start all services with a single command
- 🗄️ **MySQL Database** - Automatically starts and configures MySQL
- 🌐 **WordPress Server** - Runs WordPress on port 7000
- 🔧 **phpMyAdmin** - Database management interface (if installed)
- 📊 **Service Monitoring** - Real-time status monitoring
- 🛠️ **Easy Management** - Start, stop, restart, and status commands

## Quick Start

### Start Everything
```bash
./dev.sh
```

### Available Commands
```bash
./dev.sh start     # Start all services (default)
./dev.sh stop      # Stop all services
./dev.sh restart   # Restart all services
./dev.sh status    # Show service status
./dev.sh help      # Show help information
```

## What Gets Started

### 1. MySQL Database
- **Database**: `wordpress_db`
- **Username**: `wordpress_user`
- **Password**: `wordpress_password_123`
- **Host**: `localhost`

### 2. WordPress Development Server
- **URL**: http://localhost:7000
- **Admin**: http://localhost:7000/wp-admin
- **Port**: 7000

### 3. phpMyAdmin (if installed)
- **URL**: http://localhost:7000/phpmyadmin
- **Login**: Use WordPress database credentials above

### 4. Apache2 (optional)
- **Status**: Starts if available
- **Purpose**: Alternative to PHP development server

## Access Information

Once started, you'll see:
```
🎉 Development Environment Ready!

📱 Access Your WordPress Site:
   → http://localhost:7000

🔧 WordPress Admin:
   → http://localhost:7000/wp-admin

🗄️ phpMyAdmin Database Manager:
   → http://localhost:7000/phpmyadmin

Database Credentials:
   Username: wordpress_user
   Password: wordpress_password_123
   Database: wordpress_db
```

## Troubleshooting

### MySQL Won't Start
```bash
sudo systemctl status mysql
sudo journalctl -u mysql -n 50
```

### Port 7000 Already in Use
The script automatically kills any existing process on port 7000 before starting.

### Database Connection Issues
```bash
# Test database connection manually
mysql -u wordpress_user -pwordpress_password_123 -e "USE wordpress_db; SELECT 'OK';"
```

### phpMyAdmin Not Working
```bash
# Install phpMyAdmin
sudo apt update
sudo apt install phpmyadmin -y
```

## Service Management

### Stop All Services
```bash
./dev.sh stop
```

### Check Status
```bash
./dev.sh status
```

### Restart Everything
```bash
./dev.sh restart
```

## Logs and Debugging

### WordPress Debug Log
```bash
tail -f wp-content/debug.log
```

### MySQL Logs
```bash
sudo tail -f /var/log/mysql/error.log
```

### Apache Logs (if using Apache)
```bash
sudo tail -f /var/log/apache2/wordpress-error.log
```

## Configuration

The script uses these default settings (can be modified in the script):
- WordPress Directory: `/home/saiful/wordpress`
- WordPress Port: `7000`
- Database Name: `wordpress_db`
- Database User: `wordpress_user`
- Database Password: `wordpress_password_123`

## Requirements

- PHP 7.4+ with MySQL extension
- MySQL Server
- WordPress installation
- phpMyAdmin (optional but recommended)

## Notes

- The script runs WordPress using PHP's built-in development server
- All services are started in the background except the WordPress server
- Press `Ctrl+C` to stop the development server
- The script automatically handles port conflicts
- Database is created automatically if it doesn't exist

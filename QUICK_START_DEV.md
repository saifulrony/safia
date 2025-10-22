# 🚀 Quick Start - WordPress Development

## One Command to Start Everything

```bash
cd /home/saiful/wordpress
./dev.sh
```

That's it! This will start:
- ✅ MySQL Database
- ✅ WordPress on port 7000
- ✅ phpMyAdmin (if installed)
- ✅ Apache2 (optional)

## Access Your Site

- **WordPress Site**: http://localhost:7000
- **WordPress Admin**: http://localhost:7000/wp-admin
- **phpMyAdmin**: http://localhost:7000/phpmyadmin

## Database Credentials
- **Username**: wordpress_user
- **Password**: wordpress_password_123
- **Database**: wordpress_db

## Other Commands

```bash
./dev.sh stop      # Stop all services
./dev.sh restart   # Restart everything
./dev.sh status    # Check what's running
./dev.sh help      # Show all options
```

## Stop the Server
Press `Ctrl+C` in the terminal where you ran `./dev.sh`

---

**That's it!** Your WordPress development environment is ready to go! 🎉

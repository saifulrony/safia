# 🌐 WordPress Network Access Setup

## ✅ Status: CONFIGURED & RUNNING

Your WordPress development server is now configured to be accessible from any device on your network!

## 🔗 Access URLs

### From This Computer (Local):
- **WordPress Site**: http://localhost:7000
- **WordPress Admin**: http://localhost:7000/wp-admin
- **phpMyAdmin**: http://localhost:7000/phpmyadmin

### From Other Devices (Network):
- **WordPress Site**: http://192.168.10.203:7000
- **WordPress Admin**: http://192.168.10.203:7000/wp-admin
- **phpMyAdmin**: http://192.168.10.203:7000/phpmyadmin

## 📊 Current Status

```
✓ MySQL: Running
✓ Apache2: Running
✓ WordPress Server: Running on port 7000
✓ Network Access: Enabled (listening on 0.0.0.0:7000)
✓ Local IP: 192.168.10.203
```

## 🚀 Quick Commands

```bash
# Start everything (already running)
./dev.sh start

# Check status
./dev.sh status

# Stop all services
./dev.sh stop

# Restart everything
./dev.sh restart

# Open firewall port (if needed)
./open-port-7000.sh
```

## 🔥 Firewall Configuration

If you can't access the site from other devices, you may need to open port 7000 in your firewall:

```bash
sudo ./open-port-7000.sh
```

Or manually:
```bash
sudo ufw allow 7000/tcp
sudo ufw status
```

## 📱 Testing Network Access

From another device on your network, try:
- Open a browser
- Go to: http://192.168.10.203:7000
- You should see your WordPress site!

## 🗄️ Database Access

**Local MySQL Access** (from this computer):
```bash
mysql -u wordpress_user -pwordpress_password_123 wordpress_db
```

**phpMyAdmin Access** (web interface):
- URL: http://192.168.10.203:7000/phpmyadmin
- Username: `wordpress_user`
- Password: `wordpress_password_123`
- Database: `wordpress_db`

## 🔧 What Changed

1. **Updated `dev.sh`** to bind to `0.0.0.0` instead of `localhost`
   - This allows connections from any network interface
   - Server now listens on: `0.0.0.0:7000` (accessible from network)
   - Previously: `localhost:7000` (local only)

2. **Added network URLs** to the startup message
   - Shows both local and network access URLs
   - Displays your current IP address automatically

3. **Created firewall helper** script
   - `open-port-7000.sh` to easily configure firewall if needed

## 🛠️ Troubleshooting

### Can't Access from Other Devices?

1. **Check if server is running:**
   ```bash
   ./dev.sh status
   ```

2. **Verify port is open:**
   ```bash
   ss -tuln | grep 7000
   ```
   Should show: `0.0.0.0:7000`

3. **Check firewall:**
   ```bash
   sudo ufw status
   sudo ./open-port-7000.sh
   ```

4. **Test from another device:**
   - Make sure both devices are on the same network
   - Use IP: 192.168.10.203
   - Port: 7000
   - Full URL: http://192.168.10.203:7000

### IP Address Changed?

If your IP address changes (e.g., after reboot), get new IP:
```bash
hostname -I | awk '{print $1}'
```

The `dev.sh` script automatically detects and displays your current IP when starting.

## 📝 Notes

- The server runs in the background when started with `./dev.sh start`
- Press `Ctrl+C` if running in foreground mode
- All services start automatically with one command
- The script is smart enough to handle port conflicts

## 🎯 Next Steps

1. Access your site from your phone/tablet: http://192.168.10.203:7000
2. Test phpMyAdmin: http://192.168.10.203:7000/phpmyadmin
3. Start developing! All changes will be visible across all devices

---

**Your WordPress development environment is ready and accessible from anywhere on your network!** 🚀

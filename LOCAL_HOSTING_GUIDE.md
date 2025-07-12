# Local Network Hosting Guide

This guide will help you run your Laravel application locally and allow other people to access it using your IP address.

## Quick Start

### Option 1: Using the Batch File (Windows)
```bash
# Simply double-click or run:
run-local-server.bat
```

### Option 2: Using PowerShell Script
```powershell
# Run in PowerShell:
.\run-local-server.ps1
```

### Option 3: Manual Commands
```bash
# Find your IP address first
ipconfig

# Start Laravel server accessible from network
php artisan serve --host=0.0.0.0 --port=8000
```

## Detailed Setup Steps

### 1. Find Your Local IP Address

**Windows PowerShell:**
```powershell
Get-NetIPAddress -AddressFamily IPv4 | Where-Object { $_.IPAddress -like "192.168.*" -or $_.IPAddress -like "10.*" }
```

**Windows Command Prompt:**
```cmd
ipconfig | findstr "IPv4"
```

Look for an IP address like:
- `192.168.1.xxx` (most common)
- `192.168.0.xxx`
- `10.0.0.xxx`

### 2. Start the Laravel Server

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

**What this does:**
- `--host=0.0.0.0` - Makes the server accessible from any IP address
- `--port=8000` - Sets the port to 8000 (you can change this)

### 3. Share Your URL

Once the server is running, share this URL with others:
```
http://[YOUR_IP_ADDRESS]:8000
```

**Example:**
If your IP is `192.168.1.100`, people can access your app at:
```
http://192.168.1.100:8000
```

## Alternative Ports

If port 8000 is busy, try other ports:
```bash
php artisan serve --host=0.0.0.0 --port=8080
php artisan serve --host=0.0.0.0 --port=3000
php artisan serve --host=0.0.0.0 --port=9000
```

## Environment Configuration

### Update .env File (Optional)

For better configuration, you can update your `.env` file:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://192.168.1.100:8000  # Replace with your actual IP
```

## Firewall Configuration

### Windows Firewall
You might need to allow PHP through Windows Firewall:

1. Open Windows Security → Firewall & network protection
2. Click "Allow an app through firewall"
3. Add PHP or allow the port 8000

### Manual Firewall Rule
```powershell
# Run as Administrator
netsh advfirewall firewall add rule name="Laravel Dev Server" dir=in action=allow protocol=TCP localport=8000
```

## Troubleshooting

### Common Issues:

1. **"Connection refused" or "Can't connect"**
   - Check if your firewall is blocking the connection
   - Ensure you're using the correct IP address
   - Make sure the server is running with `--host=0.0.0.0`

2. **Server not accessible from other devices**
   - Verify you're on the same network/WiFi
   - Check if your router has AP isolation enabled
   - Try disabling Windows Firewall temporarily to test

3. **Assets not loading (CSS/JS)**
   - Make sure to run `npm run build` before starting the server
   - Check that Vite assets are properly built

### Testing Connection:
```bash
# Test from another device on the same network
curl http://192.168.1.100:8000
```

## Security Notes

⚠️ **Important Security Considerations:**

1. **Only use this for development/testing**
2. **Never expose this to the internet**
3. **Only share with trusted people on your local network**
4. **Don't use this setup for production**

## Advanced: Using Vite Dev Server

If you're using Vite for frontend assets and want hot reloading:

```bash
# Terminal 1: Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2: Start Vite dev server
npm run dev -- --host=0.0.0.0 --port=5173
```

Update your `.env`:
```env
VITE_DEV_SERVER_URL=http://192.168.1.100:5173
```

## Quick Commands Reference

```bash
# Start server for local network access
php artisan serve --host=0.0.0.0 --port=8000

# Find your IP (Windows)
ipconfig | findstr "IPv4"

# Build assets
npm run build

# Run migrations (if needed)
php artisan migrate

# Clear cache (if needed)
php artisan cache:clear
php artisan config:clear
```

## Stop the Server

To stop the server, press `Ctrl+C` in the terminal where it's running. 
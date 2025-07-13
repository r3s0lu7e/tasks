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

### Update .env File (IMPORTANT for Mobile Access)

For proper mobile access, you **MUST** update your `.env` file with your actual IP address:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://192.168.1.100:8000  # Replace with your actual IP and port

# Optional: For Vite development server
VITE_DEV_SERVER_URL=http://192.168.1.100:5173
```

**Why this is important:**
- Laravel uses `APP_URL` to generate asset URLs
- If it's set to `localhost`, mobile devices won't be able to load CSS/JS files
- This is the most common cause of missing styles on mobile devices

### Create .env file from example:
```bash
cp .env.example .env
# Edit the .env file with your IP address
```

## CSS/Assets Not Loading on Mobile? (COMMON ISSUE)

### Quick Fix Steps:

1. **Update your .env file** with your computer's IP address:
   ```env
   APP_URL=http://192.168.1.100:8000  # Use YOUR IP
   ```

2. **Clear Laravel cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Rebuild assets:**
   ```bash
   npm run build
   ```

4. **Restart the server:**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

### Detailed Troubleshooting:

**Issue:** CSS/JS files don't load on mobile devices but work on desktop

**Causes and Solutions:**

1. **Wrong APP_URL in .env:**
   ```env
   # ❌ Wrong - won't work on mobile
   APP_URL=http://localhost:8000
   
   # ✅ Correct - works on mobile
   APP_URL=http://192.168.1.100:8000
   ```

2. **Assets not built:**
   ```bash
   # Build production assets
   npm run build
   
   # Verify assets exist in public/build/
   ls -la public/build/assets/
   ```

3. **Vite configuration issues:**
   - The `vite.config.js` has been updated to support mobile access
   - Make sure you're using built assets (`npm run build`) not dev server

4. **Cache issues:**
   ```bash
   # Clear all Laravel caches
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

5. **Mixed content (HTTP/HTTPS):**
   - Ensure both server and assets use the same protocol
   - Usually should be HTTP for local development

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

3. **Assets not loading (CSS/JS) - MOST COMMON ISSUE**
   - **First, check your .env file** - APP_URL must be your IP, not localhost
   - Make sure to run `npm run build` before starting the server
   - Check that Vite assets are properly built in `public/build/assets/`
   - Clear Laravel cache: `php artisan config:clear`
   - Restart the server after changing .env

4. **Styles work on desktop but not mobile**
   - This is almost always an APP_URL configuration issue
   - Update APP_URL in .env to your computer's IP address
   - Clear cache and restart server

### Testing Connection:
```bash
# Test from another device on the same network
curl http://192.168.1.100:8000

# Test asset loading specifically
curl http://192.168.1.100:8000/build/assets/app-[hash].css
```

### Debug Asset Loading:
```bash
# Check if assets are built
ls -la public/build/assets/

# Check manifest file
cat public/build/manifest.json

# Verify asset URLs in browser developer tools
# Should show your IP address, not localhost
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

**Note:** For mobile access, it's usually better to use built assets (`npm run build`) rather than the dev server.

## Quick Commands Reference

```bash
# Start server for local network access
php artisan serve --host=0.0.0.0 --port=8000

# Find your IP (Windows)
ipconfig | findstr "IPv4"

# Build assets (IMPORTANT for mobile)
npm run build

# Clear cache (after changing .env)
php artisan config:clear
php artisan cache:clear

# Run migrations (if needed)
php artisan migrate

# Check built assets
ls -la public/build/assets/
```

## Step-by-Step Mobile Setup Checklist

1. ✅ Find your computer's IP address: `ipconfig`
2. ✅ Update `.env` file with your IP in `APP_URL`
3. ✅ Build assets: `npm run build`
4. ✅ Clear cache: `php artisan config:clear`
5. ✅ Start server: `php artisan serve --host=0.0.0.0 --port=8000`
6. ✅ Test on mobile: `http://YOUR_IP:8000`
7. ✅ If CSS is missing, double-check steps 2-4

## Environment File Template

Create a `.env` file with these settings (replace IP with yours):

```env
APP_NAME="Ива's workstation"
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://192.168.1.100:8000  # ← CHANGE THIS TO YOUR IP

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Optional: For Vite dev server
VITE_DEV_SERVER_URL=http://192.168.1.100:5173
``` 
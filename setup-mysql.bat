@echo off
echo Setting up MySQL database for Laravel Tasks application...
echo.

echo Step 1: Creating database 'tasks_db'
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS tasks_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo.
echo Step 2: Showing existing databases
mysql -u root -p -e "SHOW DATABASES;"

echo.
echo Step 3: Running Laravel migrations
php artisan migrate

echo.
echo Step 4: Seeding database (optional)
php artisan db:seed

echo.
echo MySQL setup complete!
echo Your Laravel application is now configured to use MySQL database 'tasks_db'
pause 
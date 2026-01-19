# School Management System - Installation & Troubleshooting Guide

## Error Analysis
**Current Error:** `SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost' (using password: YES)`

**Cause:** The database configuration file is trying to connect with password `'password'`, but your MySQL root user has a different password (or no password).

---

## Solution Steps

### Option 1: Update Database Configuration (Recommended)

1. **Find your MySQL root password:**
   - For XAMPP: Default is usually **empty** (no password)
   - For WAMP: Default is usually **empty** or **root**
   - For MAMP: Default is usually **root**
   - If you set a custom password, use that

2. **Edit the configuration file:**
   - Navigate to: `school_app/config/Database.php`
   - Find line 10: `private static $password = 'password';`
   - Change it to match your MySQL setup:

   ```php
   // For XAMPP (no password):
   private static $password = '';

   // OR if you have a password:
   private static $password = 'your_actual_password';
   ```

3. **Save the file** and refresh your browser

---

### Option 2: Change MySQL Root Password

If you prefer to set your MySQL password to match the configuration:

#### For XAMPP:
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Click "User accounts" tab
3. Find user "root" with host "localhost"
4. Click "Edit privileges"
5. Click "Change password"
6. Choose "Password" option
7. Enter: `password`
8. Click "Go"

#### Using MySQL Command Line:
```sql
ALTER USER 'root'@'localhost' IDENTIFIED BY 'password';
FLUSH PRIVILEGES;
```

---

## Complete Installation Steps

### 1. Prerequisites
- ✓ XAMPP/WAMP/MAMP installed
- ✓ Apache and MySQL services running
- ✓ PHP 7.4 or higher

### 2. Database Setup

1. **Start MySQL** (via XAMPP/WAMP control panel)

2. **Create the database:**
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Click "New" in the left sidebar
   - Database name: `school_app`
   - Collation: `utf8mb4_unicode_ci`
   - Click "Create"

3. **Import the schema:**
   - Select the `school_app` database
   - Click "Import" tab
   - Choose file: `schema.sql` (from your project folder)
   - Click "Go"

### 3. Configure Database Connection

Edit `school_app/config/Database.php`:

```php
<?php
class Database
{
    private static $host = 'localhost';
    private static $db_name = 'school_app';
    private static $username = 'root';
    private static $password = '';  // ← Change this to match your MySQL password
    private static $connection = null;
    
    // ... rest of the code
}
```

### 4. Place Files in Web Server

Copy the entire `CSE3101-Internet-Computing-staging` folder to:
- **XAMPP:** `C:\xampp\htdocs\`
- **WAMP:** `C:\wamp\www\`
- **MAMP:** `/Applications/MAMP/htdocs/`

### 5. Access the Application

Open your browser and navigate to:
```
http://localhost/CSE3101-Internet-Computing-staging/
```

---

## Default Login Credentials

After importing the schema, use these credentials to log in:

**Admin Account:**
- Username: `admin`
- Password: `admin123`

**Teacher Account:**
- Username: `teacher`
- Password: `teacher123`

---

## Common Issues & Solutions

### Issue 1: "Database not found"
**Solution:** Create the database named `school_app` in phpMyAdmin

### Issue 2: "Tables don't exist"
**Solution:** Import the `schema.sql` file through phpMyAdmin

### Issue 3: "Password error persists"
**Solution:** 
1. Check your MySQL password using phpMyAdmin
2. Update `Database.php` line 10 to match
3. Common XAMPP default is empty string: `''`

### Issue 4: "Page not loading"
**Solution:**
- Ensure Apache is running in XAMPP/WAMP
- Check URL path matches folder name
- Look for .htaccess issues

### Issue 5: "Permission denied"
**Solution:**
- Ensure MySQL user 'root' has proper privileges
- Grant all privileges in phpMyAdmin

---

## Quick Fix Commands

### Test MySQL Connection (PHP)
Create a file `test_db.php` in your project root:

```php
<?php
$host = 'localhost';
$db = 'school_app';
$user = 'root';
$pass = '';  // Try empty first, then 'password', then 'root'

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    echo "✓ Connected successfully!";
} catch(PDOException $e) {
    echo "✗ Connection failed: " . $e->getMessage();
}
?>
```

Visit: `http://localhost/your-folder/test_db.php`

---

## Verify Installation Checklist

- [ ] MySQL service is running
- [ ] Database `school_app` exists
- [ ] Schema imported successfully
- [ ] `Database.php` password matches MySQL password
- [ ] Files in correct htdocs/www folder
- [ ] Apache service is running
- [ ] Can access http://localhost/your-folder/

---

## Need More Help?

1. Check error logs:
   - XAMPP: `xampp/apache/logs/error.log`
   - Check browser console (F12)

2. Enable PHP error reporting by adding to your main PHP file:
   ```php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```

3. Common password values to try in `Database.php`:
   - `''` (empty string)
   - `'password'`
   - `'root'`
   - Your custom password

---

## Summary

**The most likely fix:** Change line 10 in `school_app/config/Database.php` from:
```php
private static $password = 'password';
```

To:
```php
private static $password = '';  // Empty for default XAMPP
```

Then refresh your browser!

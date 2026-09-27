# Network Infrastructure Documentation System - Installation Guide

## System Requirements

### Server Requirements
- PHP 8.1 or higher
- Composer (PHP dependency manager)
- MySQL 8.0 or higher / MariaDB 10.3+
- Node.js 16+ (optional, for frontend build tools)

### Recommended Server Configuration
- Minimum 2GB RAM
- Minimum 10GB disk space
- Dual-core processor
- Linux-based server (Ubuntu 20.04+ or CentOS 8+)

## Pre-Installation Setup

### 1. Install Required Software

#### Ubuntu/Debian:
```bash
# Update system
sudo apt-get update
sudo apt-get upgrade -y

# Install PHP and extensions
sudo apt-get install -y php8.1 php8.1-cli php8.1-fpm php8.1-mysql php8.1-pdo php8.1-dom php8.1-curl php8.1-json php8.1-mbstring php8.1-xml php8.1-zip php8.1-bcmath

# Install MySQL
sudo apt-get install -y mysql-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Verify installations
php --version
composer --version
mysql --version
```

#### CentOS/RHEL:
```bash
# Update system
sudo yum update -y

# Install PHP and extensions
sudo yum install -y php81 php81-cli php81-fpm php81-mysql php81-pdo php81-dom php81-curl php81-json php81-mbstring php81-xml php81-zip php81-bcmath

# Install MySQL
sudo yum install -y mysql-server

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

### 2. Create Database

```bash
# Connect to MySQL as root
mysql -u root -p

# Create database and user
CREATE DATABASE network_infrastructure;
CREATE USER 'net_app'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON network_infrastructure.* TO 'net_app'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## Installation Steps

### Step 1: Clone or Upload Project

```bash
# Option A: Clone from repository (if available)
git clone https://github.com/your-org/network-infrastructure.git
cd network-infrastructure

# Option B: Extract uploaded files
unzip network-infrastructure.zip
cd network-infrastructure
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

This will install all required Laravel packages and dependencies.

### Step 3: Environment Configuration

```bash
# Copy example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Edit .env File

Edit the `.env` file and configure:

```env
# Application
APP_NAME="Network Infrastructure"
APP_ENV=production
APP_KEY=base64:your_generated_key_here
APP_DEBUG=false
APP_URL=https://your-server-address

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=network_infrastructure
DB_USERNAME=net_app
DB_PASSWORD=secure_password_here

# Mail (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls

# Audit Configuration
AUDIT_RETENTION_DAYS=30
AUDIT_AUTO_CLEANUP=true

# Session
SESSION_LIFETIME=480
```

### Step 5: Run Database Migrations

```bash
# Create all database tables
php artisan migrate

# Or with specific seed data
php artisan migrate --seed
```

### Step 6: Set File Permissions

```bash
# Set proper ownership
sudo chown -R www-data:www-data /path/to/application

# Set permissions
chmod -R 755 /path/to/application
chmod -R 775 /path/to/application/storage
chmod -R 775 /path/to/application/bootstrap/cache
```

### Step 7: Create Initial Admin User

```bash
# Create admin user via Artisan command
php artisan tinker

# In tinker shell:
```

```php
use App\Models\User;

User::create([
    'username' => 'admin',
    'email' => 'admin@company.com',
    'password' => bcrypt('initial_secure_password'),
    'full_name' => 'System Administrator',
    'role' => 'admin',
    'is_active' => true,
]);

exit();
```

Or manually using SQL:

```bash
mysql -u net_app -p network_infrastructure

INSERT INTO users (username, email, password, full_name, role, is_active, created_at, updated_at) 
VALUES ('admin', 'admin@company.com', '$2y$12$...', 'System Administrator', 'admin', 1, NOW(), NOW());
```

## Web Server Configuration

### Apache Configuration

Create `/etc/apache2/sites-available/network-infrastructure.conf`:

```apache
<VirtualHost *:80>
    ServerName your-server-address
    ServerAlias www.your-server-address
    DocumentRoot /var/www/network-infrastructure/public

    <Directory /var/www/network-infrastructure/public>
        AllowOverride All
        Require all granted
        
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.*)$ index.php/$1 [L]
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/network-infrastructure-error.log
    CustomLog ${APACHE_LOG_DIR}/network-infrastructure-access.log combined
</VirtualHost>

<VirtualHost *:443>
    ServerName your-server-address
    ServerAlias www.your-server-address
    DocumentRoot /var/www/network-infrastructure/public

    SSLEngine on
    SSLCertificateFile /path/to/ssl/certificate.crt
    SSLCertificateKeyFile /path/to/ssl/private.key

    <Directory /var/www/network-infrastructure/public>
        AllowOverride All
        Require all granted
        
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.*)$ index.php/$1 [L]
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/network-infrastructure-error.log
    CustomLog ${APACHE_LOG_DIR}/network-infrastructure-access.log combined
</VirtualHost>
```

Enable the site:
```bash
sudo a2ensite network-infrastructure.conf
sudo a2enmod rewrite
sudo systemctl reload apache2
```

### Nginx Configuration

Create `/etc/nginx/sites-available/network-infrastructure`:

```nginx
upstream laravel {
    server 127.0.0.1:9000;
}

server {
    listen 80;
    server_name your-server-address www.your-server-address;
    root /var/www/network-infrastructure/public;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass laravel;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site:
```bash
sudo ln -s /etc/nginx/sites-available/network-infrastructure /etc/nginx/sites-enabled/
sudo systemctl reload nginx
```

## Post-Installation

### 1. Verify Installation

```bash
# Run tests
php artisan test

# Check cache
php artisan optimize
php artisan view:cache

# Clear cache if needed
php artisan cache:clear
php artisan config:clear
```

### 2. Set Up SSL Certificate

Using Let's Encrypt (recommended):

```bash
sudo apt-get install -y certbot python3-certbot-apache
sudo certbot certonly --apache -d your-server-address

# For automatic renewal
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

### 3. Configure Backup

```bash
# Create backup directory
sudo mkdir -p /backup/network-infrastructure
sudo chown www-data:www-data /backup/network-infrastructure

# Create backup script: /usr/local/bin/backup-network-infrastructure.sh
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/network-infrastructure"

# Backup database
mysqldump -u net_app -p database_password network_infrastructure > $BACKUP_DIR/db_backup_$DATE.sql

# Backup application
tar -czf $BACKUP_DIR/app_backup_$DATE.tar.gz /var/www/network-infrastructure/

# Keep only last 30 days
find $BACKUP_DIR -type f -mtime +30 -delete

# Make executable
chmod +x /usr/local/bin/backup-network-infrastructure.sh

# Add to crontab (daily at 2 AM)
sudo crontab -e
# Add: 0 2 * * * /usr/local/bin/backup-network-infrastructure.sh
```

### 4. Setup Scheduled Tasks

```bash
# Add to crontab
sudo crontab -e

# Add the following line:
* * * * * cd /var/www/network-infrastructure && php artisan schedule:run >> /dev/null 2>&1
```

This runs the Laravel scheduler which handles:
- Automatic audit log purge (30-day retention)
- Backup tasks
- Maintenance tasks

### 5. Monitor Application

```bash
# Check logs
tail -f /var/www/network-infrastructure/storage/logs/laravel.log

# Monitor system
ps aux | grep php
free -h
df -h
```

## Troubleshooting

### 1. Database Connection Error

```bash
# Verify MySQL is running
sudo systemctl status mysql

# Check database credentials in .env
cat .env | grep DB_

# Test connection
mysql -u net_app -p -h 127.0.0.1 network_infrastructure -e "SELECT 1"
```

### 2. Permission Issues

```bash
# Reset permissions
sudo chown -R www-data:www-data /var/www/network-infrastructure
chmod -R 755 /var/www/network-infrastructure
chmod -R 775 /var/www/network-infrastructure/storage
chmod -R 775 /var/www/network-infrastructure/bootstrap/cache
```

### 3. PHP Extensions Missing

```bash
# Check PHP info
php -m | grep mysql
php -m | grep curl

# Install missing extension
sudo apt-get install php8.1-{extension-name}
sudo systemctl reload php8.1-fpm
```

### 4. Application Key Error

```bash
# Regenerate key
php artisan key:generate

# Verify in .env
grep APP_KEY .env
```

### 5. Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## Verification Checklist

- [ ] PHP 8.1+ installed
- [ ] MySQL installed and running
- [ ] Database created
- [ ] Composer dependencies installed
- [ ] .env configured correctly
- [ ] Database migrations completed
- [ ] Admin user created
- [ ] Web server configured
- [ ] SSL certificate installed
- [ ] File permissions set correctly
- [ ] Application accessible via web browser
- [ ] Login successful with admin credentials
- [ ] Dashboard loads without errors

## Security Recommendations

1. **Firewalls**: Allow only necessary ports (80, 443)
2. **SSL/TLS**: Always use HTTPS in production
3. **Database**: Use strong passwords, restrict access
4. **Backups**: Regular automated backups
5. **Updates**: Keep PHP, MySQL, and Laravel packages updated
6. **Monitoring**: Monitor system logs and audit logs
7. **Access Control**: Use strong passwords, enable 2FA if possible
8. **Rate Limiting**: Configure rate limits to prevent abuse

## Support & Documentation

For additional help:
- Check Laravel documentation: https://laravel.com/docs
- View README.md for project overview
- Consult API_DOCUMENTATION.md for API endpoints
- Review troubleshooting section above

---

**Installation completed successfully!** 🎉

Your Network Infrastructure Documentation System is now ready to use. Access it at `https://your-server-address` and log in with your admin credentials.

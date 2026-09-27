# Deployment Guide for Vertex Platform

This guide provides step-by-step instructions for deploying the Network Infrastructure Documentation System to Vertex.

## Prerequisites

- Vertex account with appropriate permissions
- Vertex CLI installed and configured
- Project repository (Git)
- SSH key pair for deployment

## Step 1: Prepare Your Application

### 1.1 Optimize Production Build

```bash
# Create production build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Remove development files
rm -rf node_modules
npm install --production

# Clean up unnecessary files
rm -rf .env.example
rm -rf .git (optional, for size reduction)
```

### 1.2 Create Vertex Configuration File

Create `.vertex.yml` in project root:

```yaml
# Vertex Configuration
runtime: php81
framework: laravel

# Build settings
build:
  php_version: 8.1
  node_version: 16
  extensions:
    - mysql
    - pdo
    - pdo_mysql
    - curl
    - json
    - mbstring
    - dom
    - xml
    - zip
    - bcmath
  
  # Build commands
  commands:
    - composer install --no-dev
    - php artisan key:generate
    - php artisan migrate --force

# Environment
env:
  APP_ENV: production
  APP_DEBUG: false
  LOG_CHANNEL: stack

# Web server settings
web:
  root: public
  port: 8080
  
# Database
database:
  engine: mysql
  version: 8.0
  name: network_infrastructure
  
# Storage
storage:
  persistent:
    - storage/logs
    - storage/app
  
# Security
security:
  ssl: true
  hsts: true
```

### 1.3 Create Vertex Dockerfile (Alternative)

Create `Dockerfile.vertex`:

```dockerfile
FROM vertex/php:8.1-laravel

# Install system dependencies
RUN apt-get update && apt-get install -y \
    mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /app

# Copy application files
COPY . /app

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Run migrations on start
RUN echo 'php artisan migrate --force' > /usr/local/bin/startup.sh
RUN chmod +x /usr/local/bin/startup.sh

# Expose port
EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=40s --retries=3 \
    CMD curl -f http://localhost:8080/health || exit 1

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/app/public"]
```

## Step 2: Deploy to Vertex

### 2.1 Using Vertex CLI

```bash
# Initialize Vertex project
vertex init

# Follow prompts:
# - Project name: network-infrastructure
# - Runtime: PHP 8.1
# - Framework: Laravel
# - Database: MySQL 8.0

# Login to Vertex
vertex login

# Deploy application
vertex deploy

# Set environment variables
vertex env:set APP_ENV=production
vertex env:set APP_DEBUG=false
vertex env:set AUDIT_RETENTION_DAYS=30
```

### 2.2 Using Vertex Dashboard

1. Log in to Vertex console
2. Create new project: "network-infrastructure"
3. Connect Git repository:
   - Select: GitHub / GitLab / Bitbucket
   - Choose repository
   - Set branch: `main` (or `production`)
4. Configure deployment:
   - Runtime: PHP 8.1
   - Build command: `composer install && php artisan migrate --force`
   - Start command: `php artisan serve --host=0.0.0.0`
5. Set environment variables (see below)
6. Click "Deploy"

### 2.3 Environment Variables

Set in Vertex dashboard under "Settings" → "Environment":

```
APP_NAME=Network Infrastructure
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.vertex.app

DB_CONNECTION=mysql
DB_HOST=mysql.vertex.internal
DB_PORT=3306
DB_DATABASE=network_infrastructure
DB_USERNAME=net_app
DB_PASSWORD=<generate-secure-password>

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=<your-email>
MAIL_PASSWORD=<your-password>

AUDIT_RETENTION_DAYS=30
AUDIT_AUTO_CLEANUP=true

LOG_CHANNEL=stack
```

## Step 3: Database Setup

### 3.1 Create Database in Vertex

```bash
# Using Vertex CLI
vertex db:create network_infrastructure

# Connect to database
vertex db:connect

# Run migrations
vertex run "php artisan migrate --force"

# Seed initial data (optional)
vertex run "php artisan db:seed"
```

### 3.2 Create Admin User

```bash
# SSH into application
vertex ssh

# Inside container:
php artisan tinker

# Create admin user:
User::create([
    'username' => 'admin',
    'email' => 'admin@company.com',
    'password' => bcrypt('temporary_password'),
    'full_name' => 'System Administrator',
    'role' => 'admin',
    'is_active' => true,
]);

exit;
```

## Step 4: Configure Domain & SSL

### 4.1 Add Custom Domain

1. In Vertex Dashboard: "Settings" → "Domains"
2. Click "Add Domain"
3. Enter: `network-infrastructure.company.com`
4. Vertex auto-configures SSL (Let's Encrypt)
5. Update DNS records as instructed

### 4.2 Verify SSL

```bash
# Test SSL certificate
curl -I https://network-infrastructure.company.com

# Should show: HTTP/2 200
```

## Step 5: Monitoring & Logging

### 5.1 View Logs

```bash
# Real-time logs
vertex logs --follow

# Filter by level
vertex logs --level=error

# Last 100 lines
vertex logs --tail=100
```

### 5.2 Monitor Performance

1. Dashboard → "Monitoring"
2. View metrics:
   - CPU usage
   - Memory usage
   - Requests/sec
   - Response times
   - Error rates

### 5.3 Set Up Alerts

```bash
# Email alert for errors
vertex alert:create \
  --name "High Error Rate" \
  --metric=error_rate \
  --threshold=10 \
  --action=email
```

## Step 6: Backup Configuration

### 6.1 Automated Backups

```bash
# Enable backups
vertex backup:enable

# Set schedule
vertex backup:schedule --frequency=daily --time=02:00

# Set retention
vertex backup:retention --days=30
```

### 6.2 Manual Backup

```bash
# Create manual backup
vertex backup:create

# List backups
vertex backup:list

# Restore from backup
vertex backup:restore --backup-id=<id>
```

## Step 7: Continuous Deployment

### 7.1 Configure Auto-Deploy

```bash
# Enable auto-deploy on push
vertex deploy:auto --branch=production

# Disable
vertex deploy:auto --disable
```

### 7.2 Deployment Hooks

Create `.vertex/hooks/pre-deploy.sh`:

```bash
#!/bin/bash
# Pre-deployment checks
echo "Running tests..."
php artisan test

echo "Checking coding standards..."
./vendor/bin/phpcs

echo "Linting..."
./vendor/bin/pint --test
```

Create `.vertex/hooks/post-deploy.sh`:

```bash
#!/bin/bash
# Post-deployment tasks
echo "Running migrations..."
php artisan migrate --force

echo "Clearing cache..."
php artisan cache:clear

echo "Warming up caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment complete!"
```

## Step 8: Testing Deployment

### 8.1 Smoke Tests

```bash
# Test application availability
curl -f https://network-infrastructure.company.com || exit 1

# Test login page
curl -f https://network-infrastructure.company.com/login || exit 1

# Test API endpoint
curl -f https://network-infrastructure.company.com/api/dashboard/stats || exit 1
```

### 8.2 Full Test Suite

```bash
# Run tests
vertex run "php artisan test"

# Run specific test
vertex run "php artisan test --filter=LanPortTest"
```

## Troubleshooting Deployments

### Issue: Build Fails

```bash
# Check build logs
vertex logs --filter=build

# Rebuild
vertex deploy --rebuild

# Clear cache and retry
vertex cache:clear
vertex deploy
```

### Issue: Database Connection Error

```bash
# Verify database credentials
vertex env:show | grep DB_

# Test connection
vertex run "mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE -e 'SELECT 1'"

# Check database status
vertex db:status
```

### Issue: Application Won't Start

```bash
# Check application logs
vertex logs --level=error

# SSH and debug
vertex ssh
php artisan optimize:clear
php -l public/index.php

# Run migration manually
php artisan migrate --force
```

### Issue: High Memory Usage

```bash
# Check memory metrics
vertex metrics:show memory

# Increase resources
vertex scale --memory=2Gb

# Optimize code
php artisan optimize
```

## Performance Optimization

### 1. Enable Caching

```bash
# Cache configuration
vertex run "php artisan config:cache"

# Cache routes
vertex run "php artisan route:cache"

# Cache views
vertex run "php artisan view:cache"
```

### 2. Database Optimization

```bash
# Check query performance
vertex run "php artisan tinker"
# Inside tinker:
DB::enableQueryLog();
// Run queries
dd(DB::getQueryLog());
```

### 3. CDN Setup

1. Dashboard → "CDN"
2. Enable CDN for static assets
3. Configure cache headers in `.htaccess` or nginx config

## Security Checklist

- [ ] SSL/TLS enabled
- [ ] HTTPS redirects configured
- [ ] Database passwords strong and unique
- [ ] API rate limiting enabled
- [ ] Audit logging configured (30-day retention)
- [ ] Backups automated and tested
- [ ] Environment variables secured
- [ ] Admin user created with strong password
- [ ] Firewall rules configured
- [ ] Regular security updates applied

## Maintenance Tasks

### Weekly
```bash
# Check application health
vertex health:check

# Review audit logs
vertex db:query "SELECT COUNT(*) FROM audit_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"

# Verify backups
vertex backup:list --limit=5
```

### Monthly
```bash
# Purge old audit logs
vertex run "php artisan audit:purge"

# Update dependencies
vertex run "composer update"

# Run security audit
vertex run "composer audit"
```

### Quarterly
```bash
# Full database optimization
vertex db:optimize

# SSL certificate renewal (automated)
# Verify in logs

# Disaster recovery test
vertex backup:test --backup-id=<id>
```

## Scaling Your Application

### Horizontal Scaling

```bash
# Add more instances
vertex scale --instances=3

# Configure load balancer
vertex loadbalancer:config --algorithm=round-robin
```

### Vertical Scaling

```bash
# Increase resources
vertex scale --cpu=2 --memory=4Gb

# Check metrics after scaling
vertex metrics:show --duration=24h
```

## Rollback Deployment

```bash
# Rollback to previous version
vertex deploy:rollback

# Rollback to specific version
vertex deploy:rollback --version=<commit-hash>

# Verify rollback
curl https://network-infrastructure.company.com
```

---

## Deployment Completed! ✅

Your Network Infrastructure Documentation System is now live on Vertex!

**Access your application:**
- **URL**: https://network-infrastructure.company.com
- **Login**: Use credentials created in Step 3.2

**Next Steps:**
1. Verify all features are working
2. Test audit logging
3. Create additional technician users
4. Import/populate initial data
5. Set up monitoring alerts

**For Support:**
- Vertex Documentation: https://docs.vertex.app
- Laravel Documentation: https://laravel.com/docs
- Project README: See README.md

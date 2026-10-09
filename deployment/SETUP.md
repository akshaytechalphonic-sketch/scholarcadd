# ScholarAcad Full Stack - New Server Setup Guide

## Stack
- **Frontend**: Next.js 16 (Node.js 22, PM2, Apache reverse proxy)
- **Backend**: Laravel (PHP 8.2, Apache, MySQL 8)
- **DB**: MySQL 8 (~560MB)

---

## 1. Server Requirements
```
Ubuntu 24.04
PHP 8.2 + extensions: bcmath curl gd json mbstring mysql openssl pdo_mysql tokenizer xml zip
Node.js 22
Composer
Apache2 (mod_proxy, mod_proxy_http, mod_proxy_balancer, mod_ssl, mod_rewrite, mod_headers)
MySQL 8
PM2 (npm install -g pm2)
```

## 2. Clone Repos
```bash
git clone https://github.com/somil149/scholaracad-backend.git /var/www/knowledgeadmin.scholaracad.com/public_html/ScholarAcad
git clone https://github.com/somil149/scholaracad-frontend.git /var/www/scholaracad.com/ScholarAcadNextjs
```

## 3. Restore Database
```bash
# Create DB
mysql -u root -p -e "CREATE DATABASE scholaracad CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Restore from backup (in backend repo)
gunzip -c database/scholaracad_latest.sql.gz | mysql -u root -p scholaracad
```

## 4. Backend Setup (Laravel)
```bash
cd /var/www/knowledgeadmin.scholaracad.com/public_html/ScholarAcad

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Configure environment
cp .env.example .env
# Edit .env: fill in DB_*, MAIL_*, JWT_SECRET, RAZORPAY_*, CCAVENUE_*, APP_URL, etc.
nano .env

# Generate app key
php artisan key:generate

# Run migrations (skip if DB restored from backup)
# php artisan migrate

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Symlink storage
php artisan storage:link

# Install JS deps & build admin assets
npm install
npm run build
```

## 5. Frontend Setup (Next.js)
```bash
cd /var/www/scholaracad.com/ScholarAcadNextjs

# Configure environment
cp .env.example .env
# Edit .env: set NEXT_PUBLIC_API_BASE_URL and NEXT_PUBLIC_CLIENT_BASE_URL
nano .env

# Install dependencies
npm install

# Build
npm run build

# Start with PM2
pm2 start deployment/ecosystem.config.js
pm2 save
pm2 startup  # follow the printed command to enable on boot
```

## 6. Apache Setup
```bash
# Enable required modules
a2enmod proxy proxy_http proxy_balancer lbmethod_byrequests ssl rewrite headers

# Copy configs (adjust ServerName and SSL cert paths for new domain/IP)
cp deployment/apache-frontend.conf /etc/apache2/sites-available/scholaracad.conf
cp deployment/apache-frontend-ssl.conf /etc/apache2/sites-available/scholaracad-ssl.conf
cp deployment/apache-backend.conf /etc/apache2/sites-available/knowledgeadmin.conf
cp deployment/apache-backend-ssl.conf /etc/apache2/sites-available/knowledgeadmin-ssl.conf

# Enable sites
a2ensite scholaracad.conf knowledgeadmin.conf

# For local/dev without SSL, edit configs to remove SSL blocks and use port 80 only
# For production with SSL, place your certs and enable SSL sites too

systemctl restart apache2
```

## 7. Notes for Dev/Local Environment
- For local testing, skip SSL configs and use plain HTTP (port 80)
- In `apache-frontend.conf`, change ProxyPass to point to `http://127.0.0.1:3000/`
- Update `.env` files with `localhost` URLs instead of production domains
- The `storage/app/public/documents/` folder contains all uploaded media (702 files, ~213MB) — already in this repo via Git LFS

## 8. Verify
- Backend: `http://knowledgeadmin.yourserver.com` → Laravel admin panel
- Frontend: `http://yourserver.com` → Next.js public site
- Check Laravel logs: `tail -f storage/logs/laravel-*.log`
- Check PM2 logs: `pm2 logs scholaracad`

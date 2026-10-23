# Production Deployment & Infrastructure Operational Guide

## 1. Overview & Architecture Assumptions

This guide details the end-to-end production deployment process for the **PT Nihon Plast Indonesia Corporate Website & Multilingual CMS**.

### Environment Targets
- **Operating Systems**: Ubuntu Server 22.04 / 24.04 LTS, Debian 12, or Windows Server 2022.
- **Web Server**: Apache 2.4+ (with `mod_rewrite`, `mod_headers`, `mod_deflate`, `mod_expires`) or Nginx 1.24+ reverse proxy to PHP-FPM 8.3/8.5.
- **PHP Runtime**: PHP 8.3.x or 8.5.x (ZTS or NTS).
  - Mandatory extensions: `mysqli`, `mbstring`, `json`, `fileinfo`, `gd`, `openssl`, `curl`.
- **Database Server**: MySQL 8.0+ or MariaDB 10.6+ (InnoDB engine, `utf8mb4` charset, `utf8mb4_unicode_ci` collation).
- **Node.js**: Node 18+ LTS and npm 9+ (build-time dependency for React 18 admin dashboard).

---

## 2. Server Provisioning & Prerequisites

### 2.1 Linux (Ubuntu/Debian) Package Installation
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y apache2 libapache2-mod-php8.3 \
    php8.3 php8.3-cli php8.3-mysql php8.3-mbstring php8.3-xml \
    php8.3-gd php8.3-curl php8.3-zip nodejs npm git

# Enable necessary Apache modules
sudo a2enmod rewrite headers deflate expires ssl
sudo systemctl restart apache2
```

### 2.2 MySQL / MariaDB Server Setup
```sql
CREATE DATABASE `db_cms_compro` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'npi_cms_user'@'localhost' IDENTIFIED BY 'STRONG_RANDOM_PASSWORD_HERE';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, LOCK TABLES ON `db_cms_compro`.* TO 'npi_cms_user'@'localhost';
FLUSH PRIVILEGES;
```

---

## 3. Fresh Installation Procedure

### Step 1: Clone Repository
```bash
cd /var/www
sudo git clone <repo_url> cms-compro
sudo chown -R www-data:www-data /var/www/cms-compro
cd /var/www/cms-compro
```

### Step 2: Environment Configuration
Copy the template file and configure production parameters:
```bash
cp .env.example .env
chmod 600 .env
```
Edit `.env` with production credentials:
```ini
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USER=npi_cms_user
DB_PASS=STRONG_RANDOM_PASSWORD_HERE
DB_NAME=db_cms_compro
APP_ENV=production
APP_DEBUG=false
```

### Step 3: Database Import & Migrations
```bash
mysql -u npi_cms_user -p db_cms_compro < database/db_cms_compro.sql
php scripts/optimize_indexes.php
```

### Step 4: Bootstrap Super Administrator Account
Never use default credentials. Run the CLI bootstrap utility to provision a dedicated superadmin:
```bash
php scripts/create_admin.php --username=rootadmin --generate
```
*Take note of the output generated password and store it in an enterprise password manager.*

### Step 5: Compile React Admin Frontend
```bash
cd dashboard-v14
npm ci --production=false
npm run build
cd ..
```

### Step 6: File Permissions & Security Hardening
Ensure the web server has write access only to `uploads/` and read-only access to source code:
```bash
sudo chown -R www-data:www-data /var/www/cms-compro
sudo find /var/www/cms-compro -type d -exec chmod 755 {} \;
sudo find /var/www/cms-compro -type f -exec chmod 644 {} \;
sudo chmod -R 775 /var/www/cms-compro/uploads
```

---

## 4. Web Server Virtual Host Configuration

### 4.1 Apache 2.4 VirtualHost with HTTPS (Let's Encrypt / Certbot)
```apache
<VirtualHost *:80>
    ServerName nihonplast.co.id
    ServerAlias www.nihonplast.co.id
    Redirect permanent / https://nihonplast.co.id/
</VirtualHost>

<VirtualHost *:443>
    ServerName nihonplast.co.id
    ServerAlias www.nihonplast.co.id
    DocumentRoot /var/www/cms-compro

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/nihonplast.co.id/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/nihonplast.co.id/privkey.pem

    # Security Headers
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains; preload"
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"

    <Directory /var/www/cms-compro>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Deny access to sensitive files
    <FilesMatch "(\.env|\.sql|\.git|\.md|composer\.json|package\.json)$">
        Require all denied
    </FilesMatch>

    ErrorLog ${APACHE_LOG_DIR}/npi_cms_error.log
    CustomLog ${APACHE_LOG_DIR}/npi_cms_access.log combined
</VirtualHost>
```

### 4.2 Nginx + PHP-FPM Configuration
```nginx
server {
    listen 80;
    server_name nihonplast.co.id www.nihonplast.co.id;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name nihonplast.co.id www.nihonplast.co.id;
    root /var/www/cms-compro;
    index index.php index.html;

    ssl_certificate /etc/letsencrypt/live/nihonplast.co.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/nihonplast.co.id/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    }

    # Block script execution in uploads directory
    location ~* ^/uploads/.*\.php$ {
        deny all;
    }

    # Block access to hidden and sensitive files
    location ~* /(\.git|\.env|\.sql|\.md|package\.json|package-lock\.json) {
        deny all;
        return 404;
    }

    access_log /var/log/nginx/npi_access.log;
    error_log /var/log/nginx/npi_error.log;
}
```

---

## 5. Post-Deployment Verification Checklist

Execute the automated verification test suite to ensure the live environment functions with zero defects:
```bash
# 1. Verify 0 PHP Warnings / Errors across all 12 public pages
php scripts/test_zero_warnings.php

# 2. Verify all 15 Admin Controllers render cleanly
php scripts/test_admin_pages.php

# 3. Verify Dynamic Section Toggles and RBAC permissions
php scripts/test_rbac_sections.php

# 4. Verify 60 Multilingual rendering scenarios
php scripts/test_all_languages_and_pages.php

# 5. Run static analysis and SQLi/CSRF security audit
php scripts/comprehensive_audit.php
```

---

## 6. Zero-Downtime Upgrade / Update Procedure

1. Pull new code from the release branch:
   ```bash
   git pull origin main
   ```
2. Re-compile frontend if UI assets changed:
   ```bash
   cd dashboard-v14 && npm run build && cd ..
   ```
3. Run database migrations if any schema updates were introduced:
   ```bash
   php scripts/optimize_indexes.php
   ```
4. Verify system integrity:
   ```bash
   php scripts/comprehensive_audit.php
   php scripts/test_all_languages_and_pages.php
   ```
5. Clear opcode cache if using OPcache:
   ```bash
   sudo systemctl reload php8.3-fpm || sudo systemctl reload apache2
   ```

---

## 7. Rollback & Emergency Recovery

### Application Code Rollback
```bash
git log -n 5 --oneline
git checkout <previous_stable_commit_hash>
cd dashboard-v14 && npm run build && cd ..
sudo systemctl reload apache2
```

### Database Rollback
If a schema or data change corrupted state, restore from the latest snapshot:
```bash
mysql -u npi_cms_user -p db_cms_compro < /backups/db_backup_PRE_UPGRADE.sql
```
*For detailed backup and disaster recovery procedures, consult [`docs/BACKUP_RESTORE.md`](file:///c:/Devaron/www/cms-compro/docs/BACKUP_RESTORE.md).*

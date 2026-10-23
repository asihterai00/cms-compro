# Database & Media Backup and Disaster Recovery Guide

## 1. Objectives & Recovery Metrics

This document defines standard operating procedures for backing up and restoring data for the **PT Nihon Plast Indonesia CMS**, targeting:
- **RPO (Recovery Point Objective)**: Maximum 24 hours of lost content.
- **RTO (Recovery Time Objective)**: Full site restoration within 30 minutes.
- **Scope**: MySQL database (`db_cms_compro`) and persistent user uploads (`uploads/`).

---

## 2. Automated Daily Backup Script

Create a root-level script `/usr/local/bin/backup_npi_cms.sh`:
```bash
#!/usr/bin/env bash
set -euo pipefail

BACKUP_DIR="/var/backups/npi_cms"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
DB_NAME="db_cms_compro"
DB_USER="npi_cms_user"
DB_PASS="YOUR_DB_PASSWORD"
WEB_ROOT="/var/www/cms-compro"

mkdir -p "${BACKUP_DIR}/daily"
mkdir -p "${BACKUP_DIR}/media"

echo "[$(date)] Starting backup ${TIMESTAMP}..."

# 1. Atomic Database Dump with single transaction
mysqldump --user="${DB_USER}" --password="${DB_PASS}" \
    --host="127.0.0.1" \
    --single-transaction \
    --quick \
    --routines \
    --triggers \
    --default-character-set=utf8mb4 \
    "${DB_NAME}" | gzip -9 > "${BACKUP_DIR}/daily/db_${DB_NAME}_${TIMESTAMP}.sql.gz"

# 2. Archive User Uploads (media & images)
tar -czf "${BACKUP_DIR}/media/uploads_${TIMESTAMP}.tar.gz" -C "${WEB_ROOT}" uploads/

# 3. Compute SHA256 Checksums for integrity verification
sha256sum "${BACKUP_DIR}/daily/db_${DB_NAME}_${TIMESTAMP}.sql.gz" > "${BACKUP_DIR}/daily/db_${DB_NAME}_${TIMESTAMP}.sha256"
sha256sum "${BACKUP_DIR}/media/uploads_${TIMESTAMP}.tar.gz" > "${BACKUP_DIR}/media/uploads_${TIMESTAMP}.sha256"

# 4. Retention Policy: Purge local backups older than 30 days
find "${BACKUP_DIR}/daily" -type f -mtime +30 -delete
find "${BACKUP_DIR}/media" -type f -mtime +30 -delete

echo "[$(date)] Backup completed successfully."
```

Make it executable and register in cron:
```bash
chmod 700 /usr/local/bin/backup_npi_cms.sh

# Cron job: Run every night at 02:00 AM
echo "0 2 * * * root /usr/local/bin/backup_npi_cms.sh >> /var/log/npi_backup.log 2>&1" | sudo tee -a /etc/crontab
```

---

## 3. Offsite Replication & Secret Storage

1. **Replication**: Sync `/var/backups/npi_cms/` to an isolated offsite S3-compatible or SFTP storage bucket via `rclone` or `rsync` over SSH.
2. **Encryption**: For compliance, encrypt backup tarballs at rest:
   ```bash
   gpg --symmetric --cipher-algo AES256 "${BACKUP_DIR}/daily/db_${DB_NAME}_${TIMESTAMP}.sql.gz"
   ```

---

## 4. Disaster Recovery & Restoration Procedure

### Step 1: Verify Checksums
Before restoring, confirm the archive has not suffered bit rot or tampering:
```bash
sha256sum -c db_db_cms_compro_20261023_020000.sha256
sha256sum -c uploads_20261023_020000.sha256
```

### Step 2: Database Restoration
```bash
# Decompress and import into MySQL
gunzip < db_db_cms_compro_20261023_020000.sql.gz | mysql -u npi_cms_user -p db_cms_compro
```

### Step 3: Media Uploads Restoration
```bash
tar -xzf uploads_20261023_020000.tar.gz -C /var/www/cms-compro/
sudo chown -R www-data:www-data /var/www/cms-compro/uploads
sudo chmod -R 775 /var/www/cms-compro/uploads
```

### Step 4: Verification of Database & Media Consistency
Run the automated test suites to ensure all database references point to existing physical files:
```bash
cd /var/www/cms-compro
php scripts/test_zero_warnings.php
php scripts/test_admin_pages.php
php scripts/comprehensive_audit.php
```
If all commands exit with code `0`, the restoration is complete and certified.

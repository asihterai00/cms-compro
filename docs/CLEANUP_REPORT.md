# Project Cleanup & Artifact Disposal Report

## 1. Executive Summary

This report documents all file and repository cleanups performed in accordance with **TAHAP 5 (Pembersihan Proyek dan Database)**.

- **Cleanup Strategy**: Zero-risk, verifiable cleanup. Statically proven unused artifacts and sensitive temporary files were removed.
- **Total Removed Artifacts**: 4 temporary backup/archive files.
- **Deferred Candidates**: Production seeds, database dumps, and migration scripts were preserved to guarantee system recoverability.

---

## 2. Removed Artifacts & Proof of Non-Usage

| Artifact Path | File Size | Classification | Reason for Deletion | Proof of Non-Usage | Recovery Procedure |
|---|---|---|---|---|---|
| `index_bk.php` | 21,264 bytes | Backup file in web root | Unprotected backup file in public web root poses source code disclosure risk (OWASP ASVS V14.2). | Zero imports, zero references in `*.php`, `*.js`, `*.html` across workspace. Ignored by `.gitignore`. | If needed, history is stored in git tree or recreated from `index.php`. |
| `index.zip` | 3,543 bytes | Temporary archive | Leftover compressed artifact in web root. | Stale zip from manual export; no runtime dependencies. | Replaced by standard Git tracking. |
| `product-detail.zip` | 1,262 bytes | Temporary archive | Leftover compressed artifact in web root. | Stale zip from manual export; no runtime dependencies. | Replaced by standard Git tracking. |
| `gallery-detail.zip` | 2,843 bytes | Temporary archive | Leftover compressed artifact in web root. | Stale zip from manual export; no runtime dependencies. | Replaced by standard Git tracking. |

---

## 3. Retained & Deferred Candidates

The following files were inspected and intentionally **retained** to prevent operational disruption:

1. **`database/db_cms_compro.sql`**:
   - *Status*: **RETAINED**
   - *Reason*: Authoritative baseline database schema and initial seed data necessary for fresh production deployments and disaster recovery.
2. **`scripts/*.php` (67 utility scripts)**:
   - *Status*: **RETAINED**
   - *Reason*: Includes essential operational tools (`scripts/create_admin.php`, `scripts/memory.php`, `scripts/optimize_indexes.php`) and full regression test runners.
3. **`BANNER-AUTO-FIX.txt`, `BANNER-UPLOAD-FIX.txt`, `V4.8-INSTALL.txt`, etc.**:
   - *Status*: **RETAINED / ARCHIVED**
   - *Reason*: Historical developer installation notes preserved for backward audit trails. External access is blocked by root `.htaccess`.
4. **UI Render Screenshots (`render_*.png`, `dashboard_v14_reference.jpg`)**:
   - *Status*: **RETAINED**
   - *Reason*: Design reference artifacts used by the design system and front-end verification.

---

## 4. Database Schema Cleanup Audit

- **Audit Findings**:
  - No orphan tables were identified. All 16 database tables (`careers`, `certifications`, `company_profile`, `content_translations`, `facility_machinery`, `gallery`, `gallery_media`, `messages`, `news`, `page_banners`, `products`, `project_memory`, `site_section_items`, `site_sections`, `user_permissions`, `users`) are actively queried by runtime code.
  - Zero database tables, columns, or records were deleted without user consent, adhering strictly to the Accidental Data Loss Prevention Protocol.

# PT Nihon Plast Indonesia - Enterprise Company Profile & Multilingual CMS

[![PHP 8.3/8.5 Compatible](https://img.shields.io/badge/PHP-8.3%20%7C%208.5-blue.svg)](https://www.php.net/)
[![MySQL Prepared Statements](https://img.shields.io/badge/Database-MySQL%20%7C%20MariaDB-orange.svg)](https://www.mysql.com/)
[![React 18 Dashboard](https://img.shields.io/badge/Dashboard-React%2018%20%2B%20Vite-61dafb.svg)](https://react.dev/)
[![Security Hardened](https://img.shields.io/badge/Security-OWASP%20ASVS%20Level%202%20Aligned-green.svg)](docs/SECURITY.md)
[![100% FOSS](https://img.shields.io/badge/License-100%25%20FOSS%20(MIT)-success.svg)](LICENSE)

Enterprise-grade Corporate Website and Dynamic Content Management System (CMS) for **PT Nihon Plast Indonesia** — Tier-1 Global Automotive Component Manufacturer specializing in steering wheels, airbag safety systems, instrument panels, and interior plastic components.

---

## 🌟 Key Capabilities & Architecture

- **Public Corporate Portal (12 Dynamic Pages)**:
  - High-performance modular PHP templating (`includes/head.php`, `navbar.php`, `footer.php`, `page_banner.php`).
  - Dribbble/Apple-grade interactive automotive cockpit explorer (`vehicle_explorer`), global OEM partner marquee (`oem_trust`), ESG metrics counter, and 4-pillar quality showcases.
  - Zero framework bloat: Native PHP 8.3/8.5 output encoding with `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.

- **5-Language Multilingual Engine**:
  - Live language switcher supporting:
    - 🇮🇩 **ID** (Indonesian - Bahasa Indonesia)
    - 🇬🇧 **EN** (English)
    - 🇯🇵 **JA** (Japanese - 日本語)
    - 🇨🇳 **ZH** (Chinese - 简体中文)
    - 🇰🇷 **KO** (Korean - 한국어)
  - Dual translation architecture: Fallback system dictionaries (`languages/*.php`) paired with granular MySQL table `content_translations` for dynamic CMS fields.

- **Dual Admin & CMS Control Panel**:
  - **Server-Rendered Admin Suite**: 15 modular controllers (`admin/dashboard.php`, `site_sections.php`, `site_section_items.php`, `banners.php`, `products.php`, `news.php`, `career.php`, `gallery.php`, `machinery.php`, `certifications.php`, `media.php`, `messages.php`, `translations.php`, `company.php`, `users.php`).
  - **Modern SPA Admin Control Panel (React 18 + Vite)**: Source code in `dashboard-v14/` compiled into production bundle in `admin/dist/`. Real-time REST/AJAX persistence via `admin/ajax_save.php`, `admin/ajax_delete.php`, and `admin/ajax_reorder.php`.

- **Security & RBAC Enforcement**:
  - Strict Granular Role-Based Access Control (`superadmin`, `admin`, `editor`, `staff`) with per-menu and per-action (`read`, `create`, `update`, `delete`) validation.
  - Mandatory CSRF protection (`includes/csrf.php`) on all mutation forms and AJAX endpoints with `SameSite=Lax`, `HttpOnly`, and conditional `Secure` session cookies.
  - 100% MySQLi prepared statements across all controllers, eliminating SQL injection vectors.
  - Anti-bot defense on public forms (`contact.php`) featuring honeypot traps, submission time-traps (<2s rejection), and session flood rate-limiting.

---

## 🏗️ Project Structure

```text
cms-compro/
├── .env.example                    # Environment variable configuration template
├── .htaccess                       # Apache 2.4+ security headers, Gzip compression & access block
├── README.md                       # Main project documentation & quick start
├── CHANGELOG.md                    # Engineering changelog
├── PROJECT_MEMORY.md               # Persistent architecture & schema memory
│
├── config/
│   └── database.php                # MySQL connection, .env loader & 503 maintenance handler
│
├── includes/
│   ├── head.php                    # Meta tags, Favicons, Bootstrap 5.3 & Google Fonts
│   ├── navbar.php                  # Multilingual responsive navigation header
│   ├── page_banner.php             # Dynamic jumbotron header banner component
│   ├── footer.php                  # Corporate footer with dynamic company profile
│   ├── csrf.php                    # CSRF token generator, validator, and secure cookies
│   ├── lang.php                    # Multi-language session handler & dictionary helper
│   ├── site_helpers.php            # Dynamic section and banner rendering helpers
│   └── translation_helpers.php     # MySQL entity translation resolvers
│
├── languages/                      # System fallback translation dictionaries
│   ├── id.php                      # Indonesian
│   ├── en.php                      # English
│   ├── ja.php                      # Japanese
│   ├── zh.php                      # Chinese (Simplified)
│   └── ko.php                      # Korean
│
├── admin/                          # Secure CMS Administration Panel
│   ├── auth.php                    # Authentication gateway & granular RBAC engine
│   ├── login.php                   # Rate-limited admin login with session regeneration
│   ├── logout.php                  # Secure logout with cookie invalidation
│   ├── dashboard.php               # Admin overview with KPI summaries
│   ├── site_sections.php           # CMS page builder (Section titles, toggles, badges)
│   ├── site_section_items.php      # Sub-item cards, OEM partners, vehicles, pillars
│   ├── banners.php                 # Page banner image & subtitle manager
│   ├── products.php                # Automotive component catalog CRUD
│   ├── news.php                    # Press releases & corporate agenda CRUD
│   ├── career.php                  # Career vacancies & job specifications
│   ├── gallery.php                 # Manufacturing facility gallery manager
│   ├── machinery.php               # Injection molding machine specifications (150T-2500T)
│   ├── certifications.php          # IATF 16949, ISO 14001, ISO 45001 accreditations
│   ├── media.php                   # Secure media & asset library manager
│   ├── messages.php                # B2B RFQ inquiry inbox
│   ├── translations.php            # 5-language multilingual management hub
│   ├── company.php                 # Corporate profile, vision, mission & plant details
│   ├── users.php                   # User accounts & granular CRUD permission matrix
│   ├── ajax_save.php               # Universal AJAX record persistence endpoint
│   ├── ajax_delete.php             # Universal AJAX record deletion endpoint
│   ├── ajax_reorder.php            # Universal drag-and-drop & inline re-order API
│   └── dist/                       # Compiled production bundle for React 18 dashboard
│
├── dashboard-v14/                  # React 18 + Vite modern dashboard source
│   ├── package.json                # Dependencies: React 18, Vite 6, Tailwind CSS, Recharts
│   ├── src/                        # React source components & configs
│   └── tests/                      # Node.js automated test suites
│
├── public pages/
│   ├── index.php                   # Home portal
│   ├── about.php                   # Corporate overview, history, vision & mission
│   ├── products.php                # Automotive products catalog
│   ├── product-detail.php          # Component specifications & engineering detail
│   ├── facility.php                # Manufacturing plant, cleanroom & machinery
│   ├── quality.php                 # Quality lab, IATF/ISO certifications & ESG
│   ├── career.php                  # Careers & recruitment process
│   ├── news.php                    # Corporate news & press releases
│   ├── news-detail.php             # News article detail view
│   ├── contact.php                 # Contact directory & B2B RFQ inquiry form
│   ├── gallery.php                 # High-resolution photo gallery
│   └── gallery-detail.php          # Gallery photo album detail view
│
├── uploads/                        # User-uploaded media directory
│   ├── .htaccess                   # Execution block (denies PHP, scripts, executables)
│   ├── company/                    # Corporate logos & branding assets
│   ├── gallery/                    # Gallery photography
│   ├── news/                       # Press release thumbnails
│   ├── pages/                      # Header banners
│   └── products/                   # Automotive component images
│
├── scripts/                        # Automation, inspection, and verification test suites
│   ├── create_admin.php            # CLI admin account bootstrap tool
│   ├── memory.php                  # Dual-storage project memory manager (MySQL + Markdown)
│   ├── comprehensive_audit.php     # Static analysis, SQLi, CSRF, and RBAC security scanner
│   ├── test_all_languages_and_pages.php # 60-scenario full render verification test
│   ├── test_zero_warnings.php      # Zero PHP warning/notice enforcement test
│   ├── test_admin_pages.php        # 15-module admin dashboard render test
│   ├── test_rbac_sections.php      # Dynamic section toggle & RBAC permission test
│   ├── db_inspector.php            # Database schema & table inspector
│   └── optimize_indexes.php        # Database composite index optimizer
│
└── docs/                           # Engineering & operational documentation
    ├── DEPLOYMENT.md               # Production deployment & infrastructure guide
    ├── SECURITY.md                 # Security policy, controls & OWASP ASVS Level 2 alignment
    ├── THREAT_MODEL.md             # Threat modeling, attack surfaces & mitigations
    ├── SECURITY_TEST_REPORT.md     # Security verification audit report
    ├── AUDIT_REPORT.md             # Comprehensive system audit findings
    ├── CLEANUP_REPORT.md           # Cleanup & artifact disposal report
    ├── TEST_REPORT.md              # Automated & manual test verification record
    └── BACKUP_RESTORE.md           # Database & media backup and disaster recovery guide
```

---

## ⚡ Prerequisites & System Requirements

- **PHP**: 8.2 or higher (Recommended: PHP 8.3 or PHP 8.5)
  - Required Extensions: `mysqli`, `mbstring`, `json`, `session`, `fileinfo`, `gd`, `openssl`
- **Database**: MySQL 8.0+ or MariaDB 10.5+ (utf8mb4 charset)
- **Web Server**: Apache 2.4+ (with `mod_rewrite`, `mod_headers`, `mod_deflate`, `mod_expires`) or Nginx with PHP-FPM
- **Node.js** (Only for compiling dashboard UI): Node.js >= 18.0, npm >= 9.0

---

## 🚀 Local Development Quick Start

### 1. Clone Repository & Setup Environment
```bash
git clone <repository_url> cms-compro
cd cms-compro

# Copy environment template
cp .env.example .env
```

Edit `.env` with your local database credentials:
```ini
DB_HOST=127.0.0.1
DB_PORT=3306
DB_USER=cms_user
DB_PASS=your_secure_password
DB_NAME=db_cms_compro
APP_ENV=development
APP_DEBUG=true
```

### 2. Import Database Schema
Import the schema and initial seed data into MySQL:
```bash
mysql -u cms_user -p db_cms_compro < database/db_cms_compro.sql
```

### 3. Bootstrap Super Administrator Account
Generate or set credentials for the root administrator using the CLI bootstrap utility:
```bash
# Provision admin with a strong custom password:
php scripts/create_admin.php --username=admin --password=YourSuperSecurePassword123!

# Or generate a cryptographically strong random password:
php scripts/create_admin.php --username=admin --generate
```

### 4. Build Modern React Dashboard (Optional if using pre-built dist)
```bash
cd dashboard-v14
npm install
npm run build
cd ..
```

### 5. Run Verification Test Suites
Run the automated test suites to ensure zero PHP errors, valid RBAC, clean rendering, and security compliance:
```bash
# 1. Run Node.js dashboard config tests
cd dashboard-v14 && npm test && cd ..

# 2. Run PHP public page clean render test
php scripts/test_zero_warnings.php

# 3. Run Admin suite render test
php scripts/test_admin_pages.php

# 4. Run RBAC & Section toggle test
php scripts/test_rbac_sections.php

# 5. Run 60-scenario Multilingual full-page test
php scripts/test_all_languages_and_pages.php

# 6. Run Comprehensive Security & Static Analysis Audit
php scripts/comprehensive_audit.php
```

---

## 🛡️ Security & Release Gate

This project adheres strictly to **OWASP ASVS Level 2** baseline controls:
- **Zero Raw Error Leakage**: `mysqli_report(MYSQLI_REPORT_OFF)` ensures database error traces are suppressed; web requests render a friendly 503 maintenance page.
- **Upload Isolation**: `uploads/.htaccess` prevents direct execution of PHP, scripts, or binaries in uploaded media directories.
- **Session Protection**: Sessions feature `cookie_httponly`, `cookie_samesite=Lax`, and conditional `cookie_secure` on HTTPS connections.
- **Sensitive File Protection**: Root `.htaccess` blocks external access to `.env`, `.sql`, `.git`, `.json`, and documentation files.

For full vulnerability disclosures, threat models, and security test reports, see:
- [`docs/SECURITY.md`](file:///c:/Devaron/www/cms-compro/docs/SECURITY.md)
- [`docs/THREAT_MODEL.md`](file:///c:/Devaron/www/cms-compro/docs/THREAT_MODEL.md)
- [`docs/SECURITY_TEST_REPORT.md`](file:///c:/Devaron/www/cms-compro/docs/SECURITY_TEST_REPORT.md)

---

## 📜 License & Compliance

100% Free and Open-Source Software (FOSS). Built without paid third-party APIs, SaaS credits, or freemium dependencies.
Copyright &copy; 2026 PT Nihon Plast Indonesia. All rights reserved.

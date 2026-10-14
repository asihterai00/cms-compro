# 🧠 CMS Company Profile - Project Memory Bank

> Auto-exported from MySQL Database `db_cms_compro.project_memory`
> Generated on: 2026-10-14 08:35:44


## 📂 Category: ARCHITECTURE

### #1 - CMS Architecture &amp; Tech Stack
- **Recorded At:** 2026-10-14 13:22:33
- **Author:** antigravity-ai
- **Metadata:** `{"tech":["PHP 8.3","MySQL","Bootstrap 5","Laragon"]}`

PT Nihon Plast Indonesia Company Profile CMS.
- Backend: Native PHP 8.3 (Laragon)
- Database: MySQL 8 (Host: 192.168.1.6:3306, DB: db_cms_compro, User: root)
- Frontend: Bootstrap 5, Bootstrap Icons, responsive custom CSS (home-scroll.css, content-clickable.css, product-detail-layout.css)
- Templating: Modular includes (includes/head.php, includes/navbar.php, includes/footer.php, includes/page_banner.php, includes/content_helpers.php)
- Admin Panel: Located in /admin/ protected by session auth (admin/auth.php).
- Uploads: Stored under uploads/company/, uploads/gallery/, uploads/products/, uploads/pages/.

---

### #13 - Multilingual Engine (5 Languages), Dynamic CMS Sections &amp; Menu-Level CRUD RBAC
- **Recorded At:** 2026-10-14 15:35:38
- **Author:** antigravity-ai

Successfully implemented 4 major capabilities: 1) 100% FOSS Multilingual Engine across 5 languages (ID, EN, JA, ZH, KO) with authentic automotive tier-1 terminology and browser auto-detection. 2) Zero videos, YouTube iframes, or GIFs across entire site; pure high-res photography. 3) 100% Dynamic CMS with site_sections table, active/inactive 1-click toggles, customizable titles, subtitles, badges, icons, images, backgrounds, and buttons. 4) Multi-user authorization with granular Create, Read, Update, Delete (CRUD) permissions matrix per menu.

---


## 📂 Category: CHANGELOG

### #6 - Memory System &amp; Tooling Initialization
- **Recorded At:** 2026-10-14 13:22:33
- **Author:** antigravity-ai
- **Metadata:** `{"action":"init"}`

Initial setup performed:
- Created `project_memory` table in MySQL `db_cms_compro`.
- Built `scripts/memory.php` CLI tool for fast MySQL memory management.
- Built `scripts/db_check.php` for database status checks.
- Verified and created `gallery_media` table.
- Installed open-source MCP packages globally.
- Configured `mcp_config.json` with memory, sequential-thinking, and mysql MCP servers.
- Generated `PROJECT_MEMORY.md`, `CHANGELOG.md`, `GEMINI.md` workspace rules, and web development skill.

---

### #7 - Created Admin Account &amp; Secured setup.php
- **Recorded At:** 2026-10-14 13:56:04
- **Author:** antigravity-ai

Created default admin account (username: admin, password verified with password_hash). Secured admin/setup.php by adding guard that automatically redirects to login.php if users table already contains at least one record.

---

### #9 - Comprehensive Japanese Monozukuri Modernization &amp; ISO Certification Module
- **Recorded At:** 2026-10-14 14:22:58
- **Author:** antigravity-ai

Modernized PT Nihon Plast Indonesia CMS to Tier-1 Automotive standard: 1. Created certifications table in MySQL and seeded with IATF 16949:2016, ISO 9001:2015, ISO 14001:2015, ISO 45001:2018 / SMK3 PP 50/2012, and Kemenperin/SNI. 2. Created admin/certifications.php CRUD and updated admin header & dashboard. 3. Upgraded assets/css/style.css to Japanese precision palette (Precision Deep Navy #002B49, High-Tech Cyan #0088CC, Cleanroom White, Plus Jakarta Sans font). 4. Completely revamped index.php with Hero badge, Stats bar, 4-Pillar core products, and dedicated Certifications & ISO compliance section. 5. Upgraded quality.php to comprehensive Quality & Indonesian Regulatory Compliance showcase. 6. Upgraded includes/navbar.php and includes/footer.php to Tier-1 enterprise standard.

---

### #11 - Full Japanese Precision Tier-1 Modernization &amp; Competitor Gap Elimination
- **Recorded At:** 2026-10-14 14:49:32
- **Author:** antigravity-ai

Revamped entire web application based on Japan, ID, CN, KR competitor benchmarks: 1. Implemented Interactive Vehicle Zone Explorer in index.php and products.php; 2. Overhauled facility.php with authentic 150T-2500T injection machine matrix, Cleanroom Class 8, and CMM metrology lab; 3. Added OEM Trust Bar (Toyota, Honda, Nissan, Daihatsu, Mitsubishi, Suzuki, Subaru, Mazda); 4. Modernized contact.php with B2B RFQ form, Cikampek Indotaisei address, and FOSS OpenStreetMap embed; 5. Elevated about.php with Nihon Plast 1948 global network and Monozukuri DNA; 6. Standardized styling across all pages to Nihon Plast Forest Green (#005A36) and added bilingual ID/EN switcher in navbar.

---

### #12 - Comprehensive Responsive Auto-Fit &amp; Typographic Harmonization Fix
- **Recorded At:** 2026-10-14 15:03:10
- **Author:** antigravity-ai

Fixed cross-cutting theme inconsistencies and broken responsive layouts: 1. Discovered and neutralized home-scroll.css and content-clickable.css which were leaking old blue (#0b6ea8, #142e43, #8ed0ff) and breaking font scales; 2. Enforced strict Bootstrap color overrides (.text-primary, .bg-primary, .btn-primary all map to --np-green #005A36); 3. Standardized fluid typography scale with clamp() across all headings (h1-h6) and body copy; 4. Fixed responsive auto-fit for desktop, tablet, and mobile (prevented stats-bar collision on mobile, added touch horizontal scrolling on zone tabs and tables, fixed aspect ratio distortions); 5. Rebuilt career.php to dynamically fetch from database careers table and updated news.php and gallery.php to match the unified Nihon Plast design system.

---


## 📂 Category: DATABASE

### #3 - Database Schema &amp; Tables Overview
- **Recorded At:** 2026-10-14 13:22:33
- **Author:** antigravity-ai
- **Metadata:** `{"tables_count":10}`

Complete list of tables in db_cms_compro:
- careers: Job vacancies and recruitment information.
- company_profile: Singleton row holding company identity, vision, mission, contact info, logo path.
- gallery: Photo and media category / album records.
- gallery_media: Individual media files (image, local video, YouTube embed) linked to gallery.id.
- messages: Inbound contact form submissions.
- news: Company news articles and press releases.
- page_banners: Page header banners (home, about, products, gallery, quality, facility, career, news, contact).
- products: Company product catalog (name, description, image).
- users: Admin accounts authenticated with password_hash/password_verify.
- project_memory: AI & developer persistent memory records across sessions.

---


## 📂 Category: ENVIRONMENT

### #2 - Development Environment &amp; Binaries
- **Recorded At:** 2026-10-14 13:22:33
- **Author:** antigravity-ai
- **Metadata:** `{"os":"Windows","php":"8.3.33","node":"22.19.0"}`

Local environment specifications:
- OS: Windows
- Web server suite: Laragon (Apache/Nginx/MySQL)
- PHP Binary: C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe
- Node.js: v22.19.0 (C:\Program Files\nodejs\node.exe)
- NPM: 10.9.3
- Git: 2.55.0.windows.4
- MySQL Connection: 192.168.1.6:3306 (user: root, db: db_cms_compro)
- Workspace Root: C:\laragon\www\cms-compro

---


## 📂 Category: FINDINGS

### #4 - Initial Codebase &amp; Security Audit Findings
- **Recorded At:** 2026-10-14 13:22:33
- **Author:** antigravity-ai
- **Metadata:** `{"status":"audited","critical_items":2}`

Key audit observations:
1. Zero Admin Users: The `users` table is currently empty, meaning admin panel cannot be accessed until an admin is created.
2. Security Risk on admin/setup.php: The setup script remains in the project and is publicly accessible without protection.
3. Dynamic gallery_media creation: gallery_media table creation was only in admin/gallery.php and gallery-detail.php; now synchronized across the schema.
4. Input & File Upload Validation: Admin uploads currently check extensions; recommending adding MIME verification (finfo) and CSRF tokens for production hardening.
5. Public Website: Responsively renders company profile data, product showcase, news feed, gallery modal, and contact message persistence.

---

### #8 - Benchmark &amp; Analysis: Nihon Plast Japan and Tier-1 Web Trends
- **Recorded At:** 2026-10-14 14:10:17
- **Author:** antigravity-ai

Comprehensive benchmark of Nihon Plast Co., Ltd. (Japan - n-plast.co.jp) and 2025/2026 Tier-1 Automotive supplier website trends: 1. Core Products: Safety Systems (Steering Wheels, Airbag Modules), Interior Resin Systems (Cockpit, Instrument Panels, Ducts, Consoles), Exterior/Aero (Cowl tops, Wheel resonators). 2. Corporate Philosophy: Monozukuri spirit, Pride in Products, Contributing to Society, 'One-of-a-kind Irreplaceable Partner'. 3. Visual Identity: Japanese precision minimalism, Deep Corporate Navy (#002B49), Technical Cyan (#0088CC), Carbon Slate (#0F172A), Cleanroom White/Gray (#F8FAFC). 4. Modern UX Trends: Dual-persona UX (Procurement & Engineers), Interactive Vehicle Component Explorer, Certifications & Quality metrics above-the-fold, Bilingual (ID/EN) readiness.

---

### #10 - Tier-1 Automotive Competitor Benchmark (ID, JP, CN, KR)
- **Recorded At:** 2026-10-14 14:36:05
- **Author:** antigravity-ai

Benchmarked key Tier-1 interior and safety competitors: Japan (Toyoda Gosei, Tokai Rika, Ashimori), Indonesia (PT TGSSI, PT Astra Sugity, PT Kasai Teck See), China (Yanfeng, Joyson Safety), Korea (Hyundai Mobis, Seoyon E-Hwa). Identified 8 critical feature gaps: 1. Interactive Vehicle Zone Component Explorer; 2. Technical Machinery & Injection Tonnage Matrix; 3. ISO/IATF 16949 & TKDN Public Verification Hub; 4. OEM Customer Trust & Awards Showcase; 5. In-house Safety Testing & CMM Lab Specs; 6. Green Monozukuri & Recycled Resin ESG Roadmap; 7. B2B RFQ / Company Profile PDF Download; 8. Dual Language (ID/EN) Switcher.

---


## 📂 Category: TOOLS_MCP

### #5 - Configured Free &amp; Open-Source MCP Servers
- **Recorded At:** 2026-10-14 13:22:33
- **Author:** antigravity-ai
- **Metadata:** `{"license":"MIT","cost":"$0"}`

100% Free & Open-Source (MIT/Apache) MCP servers installed and configured in ~/.gemini/config/mcp_config.json:
1. @modelcontextprotocol/server-memory (MIT License): Knowledge graph memory server for long-term relational context.
2. @modelcontextprotocol/server-sequential-thinking (MIT License): MCP server for deep step-by-step problem-solving.
3. @benborla29/mcp-server-mysql (MIT License): Direct MySQL querying and schema exploration tool for db_cms_compro.
- Zero API costs, zero subscriptions, fully self-hosted locally.

---


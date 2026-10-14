# 🧠 CMS Company Profile - Project Memory Bank

> Auto-exported from MySQL Database `db_cms_compro.project_memory`
> Generated on: 2026-10-14 06:22:36


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


---
name: cms-compro-dev
description: >-
  Expert guide for developing, debugging, auditing, and maintaining the PT Nihon Plast Indonesia Company Profile PHP/MySQL web application. Use this skill when modifying public pages, admin features, database queries, uploading media, or updating project memory.
---

# CMS Company Profile Developer & Memory Runbook

This skill guides the agent in developing features, fixing bugs, and maintaining persistent long-term memory for the PT Nihon Plast Indonesia Company Profile web application.

## 1. Environment & Execution
- **PHP CLI**: Always invoke using:
  `& "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" <script.php>`
- **Linting**: Before committing or concluding any edit, lint changed files:
  `& "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" -l <file.php>`
- **Database Status**: Check table row counts anytime with:
  `& "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" scripts/db_check.php`

## 2. Mandatory Long-Term Memory Protocol
Whenever a change or finding occurs:
1. **Log to Database**:
   `& "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" scripts/memory.php add --category=<cat> --title="<title>" --content="<content>"`
   Categories: `architecture`, `environment`, `database`, `findings`, `changelog`, `security`, `todo`, `notes`.
2. **Sync to Markdown**:
   `& "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" scripts/memory.php export-md`
3. **Update CHANGELOG.md**:
   Add entry under `CHANGELOG.md` for full version history.

## 3. Strict 100% Free & Open-Source (FOSS) Policy
- Never install or use packages, APIs, or tools that require payment or subscription.
- MCP servers installed are:
  - `@modelcontextprotocol/server-memory` (MIT)
  - `@modelcontextprotocol/server-sequential-thinking` (MIT)
  - `@benborla29/mcp-server-mysql` (MIT)

## 4. Coding & Security Rules
- **Prepared Statements**: Always use prepared statements (`$conn->prepare()`, `bind_param()`) for user-supplied data.
- **XSS Prevention**: Always sanitize public outputs with `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')`.
- **Admin Authentication**: All `/admin/` files must include `require_once __DIR__ . '/auth.php'`.
- **Upload Directory Locations**:
  - Logos: `uploads/company/`
  - Gallery items: `uploads/gallery/`
  - Products: `uploads/products/`
  - Banners: `uploads/pages/`

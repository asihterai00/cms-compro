# CMS Company Profile - Agent Guidelines & Persistent Memory Protocol

## 🚨 MANDATORY MEMORY PROTOCOL (Dual Storage: MySQL + Markdown)
To ensure permanent long-term memory across all future agent sessions:
1. **Always Consult Memory First**: Before proposing or executing architectural changes, check `PROJECT_MEMORY.md` and the MySQL `project_memory` table.
2. **Always Record Discoveries and Changes**:
   - Every time a new bug, feature, configuration, or architectural discovery is found, record it into the MySQL database using:
     `& "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" scripts/memory.php add --category=<cat> --title="<title>" --content="<content>"`
   - Every time a code change is made, update `CHANGELOG.md` and re-export markdown memory if needed:
     `& "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" scripts/memory.php export-md`
3. Categories supported: `architecture`, `environment`, `database`, `findings`, `changelog`, `security`, `todo`, `notes`.

---

## 🛡️ STRICT 100% FREE & OPEN SOURCE (FOSS) POLICY
- Use **ONLY** completely free and open-source software, packages, and tools (e.g. MIT, Apache 2.0, GPL, BSD).
- **NEVER** introduce, recommend, or configure any tools, APIs, services, or packages that have paid tiers, freemium limitations, credits, or pricing.
- All MCP servers and CLI utilities must run locally without external cloud billing or API keys.

---

## ⚙️ ENVIRONMENT & RUNTIME CONFIGURATION
- **Project Root**: `c:\laragon\www\cms-compro`
- **PHP CLI**: `C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe`
  *(Always use this absolute path when running PHP CLI commands in PowerShell)*
- **MySQL Database**:
  - Host: `192.168.1.6`
  - Port: `3306`
  - User: `root`
  - Password: `Emina.1525`
  - Database: `db_cms_compro`
  - Charset: `utf8mb4`
- **Node.js**: `C:\Program Files\nodejs\node.exe` (Node v22.19.0, npm 10.9.3)
- **Version Control**: Git 2.55 (available on PATH)

---

## 🛠️ MCP SERVERS CONFIGURED
Located in `~/.gemini/config/mcp_config.json`:
1. `memory` (`@modelcontextprotocol/server-memory`): Open source MCP Knowledge Graph Memory.
2. `sequential-thinking` (`@modelcontextprotocol/server-sequential-thinking`): Open source dynamic reasoning tool.
3. `mysql` (`@benborla29/mcp-server-mysql`): Open source MySQL query and schema inspection tool for `db_cms_compro`.

---

## 📋 CODEBASE CONVENTIONS & CODING STANDARDS
- **Templating**: Modular PHP includes. Always use `includes/head.php`, `includes/navbar.php`, `includes/footer.php`, `includes/page_banner.php`.
- **Database Access**: Always use `mysqli` prepared statements (`$stmt = $conn->prepare(...)`, `$stmt->bind_param(...)`) to prevent SQL injection.
- **Output Sanitization**: Always escape public text outputs with `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')`.
- **Admin Authentication**: All pages in `/admin/` must include `require_once __DIR__ . '/auth.php'`.
- **Upload Paths**:
  - Logos: `uploads/company/`
  - Gallery items: `uploads/gallery/`
  - Product images: `uploads/products/`
  - Page banners: `uploads/pages/`

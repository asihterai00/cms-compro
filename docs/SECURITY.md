# Security Policy & System Hardening Standards

## 1. Security Architecture & Principles

The security model of the **PT Nihon Plast Indonesia CMS** is built upon defense-in-depth, least privilege, deny-by-default, and complete alignment with **OWASP ASVS (Application Security Verification Standard) v4.0.3 Level 2**.

---

## 2. Core Security Controls Implemented

### 2.1 Authentication & Session Management
- **Password Storage**: Passwords are encrypted using native PHP `password_hash()` with `PASSWORD_DEFAULT` (Bcrypt, cost 10+). Reversible encryption or plaintext storage is strictly forbidden.
- **Session Security**:
  - `session.use_only_cookies = 1`
  - `session.cookie_httponly = 1` (blocks JavaScript access to session IDs)
  - `session.cookie_samesite = 'Lax'` (mitigates cross-site request forgery)
  - Conditional `session.cookie_secure = 1` when HTTPS is detected.
- **Session Fixation Prevention**: `session_regenerate_id(true)` is invoked immediately upon successful authentication.
- **Brute-Force Deterrence**:
  - `admin/login.php` locks out requests after 5 consecutive failed attempts for 30 seconds.
  - A 1-second server delay is triggered on failed attempts to deter credential stuffing.

### 2.2 Authorization & Role-Based Access Control (RBAC)
- **Granular Permissions**: 4 default roles (`superadmin`, `admin`, `editor`, `staff`) governed by table `user_permissions` with distinct flags for `can_read`, `can_create`, `can_update`, and `can_delete`.
- **Server-Side Enforcement**: All administrative controllers invoke `require_permission($menu, $action)`. Route or UI hiding is never treated as a security boundary.
- **Root Admin Safeguard**: Administrator account with ID 1 cannot be deleted by any administrative user.

### 2.3 SQL Injection & Data Sanitization
- **100% Prepared Statements**: All dynamic queries across all 15 controllers and AJAX endpoints use `mysqli::prepare()` and `mysqli_stmt::bind_param()`.
- **Query Concatenation Ban**: String concatenation in `$conn->query()` is completely prohibited and validated by automated static analysis.
- **Table Allowlisting**: Dynamic table lookups in `ajax_save.php`, `ajax_delete.php`, and `ajax_reorder.php` are matched against a strict whitelist array (`$allowedTables`).

### 2.4 CSRF Protection
- **Anti-CSRF Tokens**: All state-changing HTML forms (`POST`, `PUT`, `DELETE`) require `csrf_field()` or `csrf_meta()`.
- **Enforcement**: Validated via `verify_csrf()` using cryptographic comparison `hash_equals()`. Failed requests immediately terminate with HTTP 403 Forbidden.

### 2.5 Media Upload Isolation
- **Strict Format Whitelist**: Only `jpg`, `jpeg`, `png`, `webp`, and `svg` are permitted.
- **File Renaming**: Server generates unique alphanumeric filenames (`clean_name_timestamp.ext`) to prevent path traversal and arbitrary code execution.
- **Execution Barrier**: The `uploads/.htaccess` configuration enforces `Options -ExecCGI -Indexes` and explicitly denies requests to executable scripts (`.php`, `.phtml`, `.cgi`, `.py`, `.sh`, `.exe`, `.bat`).

### 2.6 Public Form Anti-Spam & Rate Limiting (`contact.php`)
- **Honeypot Trap**: Invisible field `hp_organization_website` catches automated bots.
- **Time-Trap Mechanism**: Submissions arriving in less than 2 seconds from form render are discarded.
- **Flood Control**: Enforces a 10-second cooldown per session on B2B RFQ message submissions.

### 2.7 HTTP Security Headers (`.htaccess`)
- `X-Content-Type-Options: "nosniff"`
- `X-Frame-Options: "SAMEORIGIN"`
- `X-XSS-Protection: "1; mode=block"`
- `Referrer-Policy: "strict-origin-when-cross-origin"`
- `Permissions-Policy: "geolocation=(), microphone=(), camera=()"`
- `Strict-Transport-Security: "max-age=31536000; includeSubDomains; preload"` (on HTTPS production virtual hosts)

---

## 3. Vulnerability Reporting Policy

If you discover a security vulnerability within this project, report it confidentially to:
**security@nihonplast.co.id**

Do not disclose security vulnerabilities publicly or file public GitHub issues prior to patch deployment and validation.

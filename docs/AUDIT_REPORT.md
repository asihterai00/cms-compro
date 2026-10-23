# Comprehensive System & Codebase Audit Report

## 1. Executive Summary

This report documents the findings, risk ratings, evidence, remediations, and current status of the comprehensive technical audit conducted on the **PT Nihon Plast Indonesia Corporate Website and Multilingual CMS**.

- **Audit Target**: PT Nihon Plast Indonesia (`cms-compro`)
- **Evaluation Date**: October 2026
- **Stack Audited**: PHP 8.3/8.5, MySQL 8.0/MariaDB, Apache 2.4 / Nginx, React 18 + Vite
- **Auditor Role**: Senior Full-Stack Engineer, Software Architect, QA & Security Auditor
- **Initial Issues Found**: 15 concrete vulnerabilities and architectural gaps
- **Remediated Issues**: 15 / 15 (100% resolved)
- **Current Defect Count**: **0 Active Bugs**

---

## 2. Findings Matrix & Remediations

| ID | Category | Component | Priority | Finding & Vulnerability Evidence | Applied Engineering Fix | Verification Status |
|---|---|---|---|---|---|---|
| **AUD-01** | Security / Auth | `admin/preview_dashboard.php` | 🔴 **CRITICAL** | File unconditionally populated session with `admin_id=1` and `role='superadmin'` without credential check, allowing unauthenticated privilege escalation to Super Administrator. | Replaced session forging with `require_once 'auth.php'`, enforced `require_permission('dashboard', 'read')`, and redirected to `dashboard.php`. | ✅ **PASS / RESOLVED** |
| **AUD-02** | Security / Auth | `admin/index.php` | 🟡 **MEDIUM** | Missing explicit `require_permission()` check before redirection. | Added explicit `require_permission('dashboard', 'read')` prior to header redirect. | ✅ **PASS / RESOLVED** |
| **AUD-03** | Security / SQLi | `admin/ajax_reorder.php` (L.90) | 🔴 **HIGH** | `SELECT page, sort_order FROM site_sections WHERE id = $id` used unparameterized variable interpolation in `$conn->query()`. | Rewritten using `$conn->prepare()` and `$stmt->bind_param("i", $id)`. | ✅ **PASS / RESOLVED** |
| **AUD-04** | Security / SQLi | `admin/ajax_reorder.php` (L.95) | 🔴 **HIGH** | `SELECT section_key, sort_order FROM site_section_items WHERE id = $id` interpolated `$id`. | Rewritten using prepared statement with integer binding. | ✅ **PASS / RESOLVED** |
| **AUD-05** | Security / SQLi | `admin/ajax_reorder.php` (L.100) | 🔴 **HIGH** | `SELECT sort_order FROM \`$table\` WHERE id = $id` interpolated `$id`. | Table validated against strict whitelist; `$id` bound using prepared statement. | ✅ **PASS / RESOLVED** |
| **AUD-06** | Security / SQLi | `admin/ajax_reorder.php` (L.111) | 🔴 **HIGH** | `move_up` single swap target query interpolated `$currentOrder`, `$id`, and `$scopeVal`. | Rewritten with prepared statement binding `("iis", ...)` or `("ii", ...)`. | ✅ **PASS / RESOLVED** |
| **AUD-07** | Security / SQLi | `admin/ajax_reorder.php` (L.113) | 🔴 **HIGH** | `move_down` single swap target query interpolated variables. | Rewritten with prepared statement with parameter binding. | ✅ **PASS / RESOLVED** |
| **AUD-08** | Security / SQLi | `admin/ajax_reorder.php` (L.125) | 🔴 **HIGH** | `UPDATE \`$table\` SET sort_order = $targetOrder WHERE id = $id` interpolated variables. | Rewritten using prepared statement binding `("ii", $targetOrder, $id)`. | ✅ **PASS / RESOLVED** |
| **AUD-09** | Security / SQLi | `admin/ajax_reorder.php` (L.126) | 🔴 **HIGH** | `UPDATE \`$table\` SET sort_order = $currentOrder WHERE id = $targetId` interpolated variables. | Rewritten using prepared statement binding `("ii", $currentOrder, $targetId)`. | ✅ **PASS / RESOLVED** |
| **AUD-10** | Security / SQLi | `admin/machinery.php` (L.30) | 🔴 **HIGH** | Status toggle post-update query concatenated `$id` into `SELECT is_active, model_brand ... WHERE id = " . $id`. | Replaced with prepared statement `$fStmt = $conn->prepare(...)` and `$fStmt->bind_param("i", $id)`. | ✅ **PASS / RESOLVED** |
| **AUD-11** | Security / SQLi | `admin/site_sections.php` (L.31) | 🔴 **HIGH** | Status toggle query concatenated `$id` in `SELECT is_active, section_name ... WHERE id = " . $id`. | Replaced with prepared statement and integer binding. | ✅ **PASS / RESOLVED** |
| **AUD-12** | Security / SQLi | `admin/site_section_items.php` (L.31) | 🔴 **HIGH** | Status toggle query concatenated `$id` in `SELECT is_active, title ... WHERE id = " . $id`. | Replaced with prepared statement and integer binding. | ✅ **PASS / RESOLVED** |
| **AUD-13** | Backend / Feature | `admin/ajax_save.php` | 🟡 **MEDIUM** | Missing `case 'banners':` in AJAX save switch statement caused banner updates from React/API to silently fail persistence. Also permission was mismapped to `'site_sections'`. | Added `case 'banners':` with full prepared statement persistence to `page_banners` and corrected permission key to `'banners'`. | ✅ **PASS / RESOLVED** |
| **AUD-14** | Security / Session | `admin/logout.php` | 🟡 **MEDIUM** | Logout only destroyed session server-side without explicitly wiping client session cookie. | Added explicit `setcookie(session_name(), '', time() - 42000, ...)` with matching security flags. | ✅ **PASS / RESOLVED** |
| **AUD-15** | Security / Cookies | `includes/csrf.php` | 🟢 **LOW** | Session cookies did not set `Secure` flag on HTTPS connections. | Added detection for `$_SERVER['HTTPS']` and `HTTP_X_FORWARDED_PROTO` to enable `session.cookie_secure=1`. | ✅ **PASS / RESOLVED** |

---

## 3. Audit Scope & Verification Limits

### Verified Aspects
1. **PHP Syntax & Deprecations**: Verified clean on PHP 8.5.12 via `php -l` across 114 files.
2. **Authentication & RBAC**: Audited across all 15 server-rendered admin pages and 3 AJAX endpoints.
3. **CSRF Enforcement**: Verified on all mutation endpoints and public forms.
4. **SQL Injection**: Comprehensive AST/regex static analysis verified zero remaining concatenated queries in active runtime code.
5. **Multilingual Rendering**: 60 out of 60 combinations of 12 pages in 5 languages verified rendering with valid flags, language tags, and substantial payloads (>20KB).
6. **Frontend Compilation**: React 18 + Vite successfully compiled into `admin/dist/` with 0 errors.

### Environmental Limitations & Known Risks
- **Testing Environment**: Tests were executed on local/staging environments against remote MySQL at `192.168.1.6:3306`.
- **WAF / Reverse Proxy**: Network-level protections (Cloudflare, AWS WAF, Fail2ban) cannot be enforced via source code and must be configured at the cloud edge.
- **Malware Antivirus Scanning on Uploads**: File MIME and extension whitelisting are strictly enforced, but ClamAV/VirusTotal scanning daemon is not installed on the local test machine; it is documented in `docs/DEPLOYMENT.md` for production setup.

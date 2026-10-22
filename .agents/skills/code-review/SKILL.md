---
name: code-review
description: >-
  Multi-persona code review and security audit skill inspired by Spencer Marx's Open Code Review and Alibaba OCR. Evaluates pull requests, git diffs, and staged PHP/JS/SQL changes through security, architecture, performance, and simplicity lenses.
---

# Code Review & Security Audit (Multi-Persona Engine)

Conducts an automated, multi-angle code review prior to committing or deploying changes to the PT Nihon Plast Indonesia CMS application.

---

## The 4 Review Lenses

### 1. 🛡️ Security Lens (Alibaba OCR Standards)
- **SQL Injection**: Every query with dynamic parameters MUST use `$stmt = $conn->prepare()` and `$stmt->bind_param()`. Flag any direct string concatenation in SQL.
- **XSS Prevention**: Public output of variables must pass through `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')`.
- **Authorization Guard**: Any page inside `/admin/` must have `require_once __DIR__ . '/auth.php'`.
- **Upload Safety**: Validate file extensions, MIME types, and file size limits for image uploads.
- **FOSS License Check**: Ensure no proprietary or paid dependencies are introduced.

### 2. 🏛️ Architecture & Modularity Lens
- **Templating Consistency**: Check that public pages reuse `includes/head.php`, `includes/navbar.php`, `includes/footer.php`, `includes/page_banner.php`.
- **Database Connection**: Ensure single shared connection `$conn` is used cleanly without duplicate connections.
- **Responsive Layout**: Test Bootstrap 5 grid classes for mobile/tablet/desktop compatibility.

### 3. ✂️ Simplicity Lens (Ponytail / Anti-Bloat)
- Are there unnecessary abstractions, unused variables, or dead code?
- Was this built with the minimum code footprint possible (YAGNI)?
- Can any custom helper be replaced with native PHP 8.3 standard library functions?

### 4. 🧪 Verification Lens
- Run PHP syntax linting:
  `& "C:\Devaron\bin\php\php-8.3.36-Win32-vs16-x64\php.exe" -l <modified_file.php>`
- Verify no fatal errors, warnings, or unhandled exceptions.

---

## Review Output Format
Output findings categorized by severity:
- 🔴 **BLOCKER**: Critical security hole or fatal crash. Must be fixed immediately.
- 🟡 **WARNING**: Suboptimal pattern, potential edge-case bug, or missing sanitization.
- 🟢 **NIT / SUGGESTION**: Minor cleanup or readability improvement.

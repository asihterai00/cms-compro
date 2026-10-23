# Threat Model & Attack Surface Analysis

## 1. Scope & Objective

This threat model identifies key assets, trust boundaries, threat actors, attack vectors, and prioritized mitigations for the **PT Nihon Plast Indonesia Corporate Portal & CMS**, adopting the **STRIDE** methodology.

---

## 2. System Assets & Trust Boundaries

### 2.1 Critical Assets
1. **Administrator Credentials & Sessions**: Superadmin / staff sessions and bcrypt password hashes.
2. **Corporate Content & Product IP**: CAD / specs of OEM automotive parts (steering wheels, airbag modules).
3. **B2B RFQ Inquiries & Client Leads**: Unreleased partnership inquiries and contact records in `messages`.
4. **Database Integrity**: MySQL instance hosting schema, dynamic sections, and translations.
5. **Server Filesystem & Storage**: Upload directories and source files.

### 2.2 Trust Boundaries
- **Boundary 1 (Public User ↔ Web Server)**: Unauthenticated requests from the public internet to public pages (`index.php`, `products.php`, `contact.php`).
- **Boundary 2 (Admin User ↔ Admin Controllers)**: Authenticated HTTP session traffic accessing `/admin/`.
- **Boundary 3 (Web Server ↔ MySQL Database)**: Internal network connection (`192.168.1.6:3306` or `127.0.0.1:3306`).
- **Boundary 4 (Web Server ↔ Uploads Filesystem)**: Media upload handling in `uploads/`.

---

## 3. Threat Actors

1. **Unauthenticated Public Attacker**: Attempts credential stuffing on `/admin/login.php`, spams `/contact.php`, or probes for unauthenticated access.
2. **Low-Privilege Staff Insider**: User with `staff` role attempting to elevate privileges or modify forbidden modules (`users`, `products`, `company`).
3. **Malicious Content Provider**: Uploads web shells (`shell.php`) disguised as image assets.
4. **Network Eavesdropper**: Attempts man-in-the-middle (MitM) session interception on unencrypted networks.

---

## 4. STRIDE Threat Analysis & Mitigations

| Category | Threat Description | Attacked Asset | Likelihood | Impact | Applied Countermeasure |
|---|---|---|---|---|---|
| **Spoofing** | Attacker guesses admin credentials via brute-force dictionary attack. | Admin Login | Medium | High | Login rate-limiting (5 attempts / 30s lockout) + 1-second delay + Bcrypt password hashing. |
| **Spoofing** | Attacker bypasses authentication by visiting `/admin/preview_dashboard.php`. | Admin Dashboard | High (previously) | Critical | Backdoor eliminated; file strictly enforces `require_permission('dashboard', 'read')` after `auth.php`. |
| **Tampering** | Attacker injects malicious SQL via unparameterized query in `ajax_reorder.php`. | MySQL Database | High (previously) | Critical | 100% prepared statements implemented across all queries with strict integer binding. |
| **Tampering** | Attacker submits unauthorized state change via Cross-Site Request Forgery (CSRF). | CMS Content | Medium | High | Cryptographically verified CSRF tokens (`hash_equals()`) enforced on all POST/mutation requests. |
| **Tampering** | Attacker uploads PHP web shell into `uploads/products/`. | Server Shell | Medium | Critical | Extension whitelisting (`jpg, png, webp, svg`) + filename sanitization + execution disabled in `uploads/.htaccess`. |
| **Repudiation** | Staff user modifies content and denies taking the action. | Content History | Low | Medium | Audit logging of administrative actions recorded into MySQL `project_memory` and session history. |
| **Information Disclosure** | Database connection failure dumps raw host, username, and stack trace to browser. | DB Credentials | Medium | High | `mysqli_report(MYSQLI_REPORT_OFF)` enabled; connection failures caught and rendered as generic 503 Maintenance screen. |
| **Information Disclosure** | Public access to `.env`, `.git`, or backup archives (`.sql`, `.zip`). | Secrets / Source Code | High | Critical | Web server `.htaccess` and Nginx rules deny all direct requests to `.env`, `.git`, `.sql`, `.zip`, `.md`. |
| **Denial of Service** | Spambot floods `contact.php` with thousands of automated submissions. | Mailbox / DB | High | Medium | Anti-bot honeypot trap (`hp_organization_website`), time-trap (<2s rejection), and 10s cooldown per session. |
| **Elevation of Privilege** | Staff user modifies another user's permissions or accesses `/admin/users.php`. | RBAC Engine | Medium | High | Server-side RBAC validation (`require_permission('users', 'read')`); Superadmin bypass restricted exclusively to active `role='superadmin'`. |

---

## 5. Residual Risks & Operational Recommendations

1. **Edge WAF & DDoS Protection**: While application-layer rate limits are active, enterprise DDoS attacks should be mitigated upstream using Cloudflare or an enterprise CDN.
2. **Database Firewall**: Ensure MySQL port 3306 is not publicly reachable from the public internet and accepts connections solely from trusted web server IP addresses.
3. **Credential Rotation**: All passwords and database credentials should be rotated every 90 days following enterprise security policies.

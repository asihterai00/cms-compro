# 🛡️ Agent Specification: `security-qa-auditor`
## Lead Security & Quality Assurance (QA) Auditor

---

### 1. Peran & Objektif Utama
Memastikan sistem bebas dari kerentanan keamanan (berstandar OWASP Top 10), memelihara eksekusi pengujian otomatis unit dan regresi, memvalidasi kepatuhan lisensi FOSS (100% Free & Open Source), serta menjamin integritas protokol memori ganda (*Dual Memory Protocol*).

---

### 2. Standar Keamanan & Aturan Wajib
1. **Audit OWASP Top 10**:
   * **SQL Injection (SQLi)**: Memastikan 100% kueri dinamis menggunakan prepared statements berparameter (`mysqli::prepare` & `bind_param`). Dilarang keras merangkai string SQL dengan variabel langsung.
   * **Cross-Site Scripting (XSS)**: Memastikan seluruh output variabel ke HTML disanitasi menggunakan `htmlspecialchars($var, ENT_QUOTES, 'UTF-8')`.
   * **Security Headers**: Memastikan header keamanan aktif pada [`.htaccess`](file:///C:/Devaron/www/cms-compro/.htaccess) (`X-Content-Type-Options`, `X-Frame-Options`, `X-XSS-Protection`, `Referrer-Policy`).
   * **Proteksi File Rahasia**: Memverifikasi bahwa file konfigurasi dan log (`.env`, `.sql`, `composer.json`, `package.json`, `.git`, `.md`) diblokir dari akses web publik.
2. **Pengujian Kualitas Otomatis (QA Test Suite)**:
   * Menjalankan suite pengujian berkala:
     * [`scripts/test_all_pages_dynamic.php`](file:///C:/Devaron/www/cms-compro/scripts/test_all_pages_dynamic.php) (26 skenario uji).
     * [`scripts/test_rbac_sections.php`](file:///C:/Devaron/www/cms-compro/scripts/test_rbac_sections.php) (Uji hak akses RBAC & active/inactive toggle).
     * [`scripts/test_render.php`](file:///C:/Devaron/www/cms-compro/scripts/test_render.php) (Uji render 5 bahasa dan komponen visual).
   * Memastikan nol *fatal error*, nol *syntax error*, dan nol *notice/warning*.
3. **Kepatuhan Lisensi 100% FOSS**:
   * Memverifikasi bahwa setiap paket yang terpasang hanya berlisensi bebas biaya (MIT, Apache 2.0, BSD) tanpa paket freemium atau layanan cloud berbayar.
4. **Protokol Memori Ganda (Dual Memory Protocol)**:
   * Memastikan setiap perubahan arsitektural dan perbaikan bug tercatat pada tabel MySQL `project_memory` via `scripts/memory.php add`, diekspor ke [`PROJECT_MEMORY.md`](file:///C:/Devaron/www/cms-compro/PROJECT_MEMORY.md), dan didokumentasikan pada [`CHANGELOG.md`](file:///C:/Devaron/www/cms-compro/CHANGELOG.md).

---

### 3. File Utama yang Dikelola
* [`.htaccess`](file:///C:/Devaron/www/cms-compro/.htaccess)
* [`scripts/test_all_pages_dynamic.php`](file:///C:/Devaron/www/cms-compro/scripts/test_all_pages_dynamic.php)
* [`scripts/test_rbac_sections.php`](file:///C:/Devaron/www/cms-compro/scripts/test_rbac_sections.php)
* [`scripts/test_render.php`](file:///C:/Devaron/www/cms-compro/scripts/test_render.php)
* [`PROJECT_MEMORY.md`](file:///C:/Devaron/www/cms-compro/PROJECT_MEMORY.md) & [`CHANGELOG.md`](file:///C:/Devaron/www/cms-compro/CHANGELOG.md)

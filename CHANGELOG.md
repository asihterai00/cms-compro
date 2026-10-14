# 📝 Project Changelog

Semua perubahan, penemuan, dan pembaruan arsitektur pada proyek PT Nihon Plast Indonesia CMS dicatat di sini secara kronologis.

---

## [Unreleased / Initial Agent Setup] - 2026-10-14

### Ditambahkan (Added)
- **Database Memory Storage (`project_memory`)**:
  - Dibuat tabel `project_memory` di MySQL `db_cms_compro` (Host: `192.168.1.6`).
  - Struktur: `id`, `category`, `title`, `content`, `metadata`, `author`, `created_at`, `updated_at`.
- **Memory CLI Tool (`scripts/memory.php`)**:
  - Script CLI PHP 100% free dan open source untuk mencatat temuan (`add`), melihat list (`list`), detail (`show`), statistik (`stats`), dan ekspor ke markdown (`export-md`).
- **Database Checker (`scripts/db_check.php`)**:
  - Tool CLI cepat untuk mengecek status dan jumlah row dari seluruh tabel di `db_cms_compro`.
- **Markdown Memory Bank (`PROJECT_MEMORY.md`)**:
  - File memori jangka panjang terstruktur yang diekspor otomatis dari database.
- **Workspace Rules (`GEMINI.md`)**:
  - Aturan permanen agen untuk selalu membaca memori sebelum bertindak, wajib mencatat setiap temuan ke database & `.md`, dan mewajibkan 100% FOSS / Free Open Source.
- **MCP Servers Open Source (MIT)**:
  - `@modelcontextprotocol/server-memory` (Knowledge graph memory lokal).
  - `@modelcontextprotocol/server-sequential-thinking` (Deep sequential thinking).
  - `@benborla29/mcp-server-mysql` (MySQL MCP Server untuk database `db_cms_compro`).
  - Dikonfigurasi di `~/.gemini/config/mcp_config.json`.
- **Tabel `gallery_media` Terverifikasi**:
  - Tabel `gallery_media` dibuat dan dipastikan aktif di MySQL agar galeri multi-media (foto, video lokal, youtube) siap digunakan.

### Ditemukan & Dianalisis (Audited & Discovered)
- **Tabel `users` kosong**: Belum ada akun admin yang tersimpan di database.
- **File `admin/setup.php` belum terlindungi**: Script setup admin masih terbuka dan dapat diakses publik. Direkomendasikan proteksi atau penghapusan setelah admin pertama dibuat.
- **Validasi file upload**: Masih berbasis ekstensi file, disarankan ditingkatkan dengan MIME verification (`finfo`) dan CSRF protection.
- **Integrasi banner & logo**: Database menyimpan 9 baris page banner dan 1 baris company profile, namun file fisik gambar banner dan logo perlu dihubungkan secara penuh di admin panel.

---

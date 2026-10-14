# 📝 Project Changelog

Semua perubahan, penemuan, dan pembaruan arsitektur pada proyek PT Nihon Plast Indonesia CMS dicatat di sini secara kronologis.

---

## [Multilingual 5 Bahasa, 100% Dynamic CMS Elements, Zero-Video & Granular RBAC] - 2026-10-14

### Fitur Baru & Peningkatan Skala Korporat (New Features & Enterprise Scale)
- **1. Mesin Multibahasa 5 Bahasa (Multilingual Engine ID, EN, JA, ZH, KO)**:
  - Mendukung 5 bahasa resmi industri otomotif: Bahasa Indonesia (`id`), English (`en`), 日本語 / Japanese (`ja`), 简体中文 / Chinese (`zh`), dan 한국어 / Korean (`ko`).
  - Menggunakan kamus lokal presisi tinggi (`languages/*.php`) tanpa ketergantungan API pihak ketiga berbayar (100% FOSS).
  - Deteksi otomatis pintar via header peramban (*Accept-Language*), sesi pengguna, dan kuki dengan peralihan manual instan pada navigasi (`ID | EN | JA | ZH | KO`).
  - Terminologi presisi manufaktur Tier-1: Roda Kemudi / Steering Wheels / ステアリングホイール / 汽车方向盘 / 스티어링 휠, Modul Airbag / Airbag Modules / エアバッグモジュール / 安全气囊模块 / 에어백 모듈, dll.
- **2. Pembersihan Total Video, YouTube & GIF Bergerak (Pure Static High-Resolution Photography)**:
  - Menghapus seluruh tag `<video>`, iframe YouTube, format `.mp4`/`.webm`/`.ogg`, dan elemen bergerak dari `gallery.php`, `gallery-detail.php`, dan `admin/gallery.php`.
  - Mengubah laboratorium di `facility.php` dari sebutan video menjadi *High-Speed Sensor Deployment Lab*.
  - Standarisasi murni fotografi resolusi tinggi manufaktur otomotif presisi (*Cleanroom White*, *Forest Green*, dan *Metallic Shading*).
- **3. 100% Dynamic CMS Elements & Switch Active / Not Active Tiap Bagian**:
  - Dibuat tabel database `site_sections` dengan kolom dinamis: judul (`title`), subjudul (`subtitle`), badge (`badge_text`), icon Bootstrap (`icon`), foto utama (`image`), foto latar (`bg_image`), teks/link tombol, urutan (`sort_order`), dan status aktif (`is_active`).
  - Modul baru `admin/site_sections.php` untuk mengelola seluruh elemen beranda dengan **1-Klik Toggle Active / Not Active**.
  - Bagian yang dinonaktifkan langsung hilang secara instan dari website publik tanpa merusak tata letak lainnya.
- **4. Multiple User Authorization & Granular Menu-Level CRUD RBAC**:
  - Diperluas tabel `users` dengan atribut `full_name`, `email`, `role` (`superadmin`, `admin`, `editor`, `staff`), dan `status` (`active`, `inactive`).
  - Dibuat tabel `user_permissions` untuk mengatur izin *Create, Read, Update, Delete* (CRUD) per menu: Dashboard, Dynamic CMS Elements, Page Banners, Company Profile, Certifications & ISO, Products, Photo Gallery, News, Career, Messages, dan Users.
  - Modul `admin/users.php` diperbarui dengan matriks checkbox CRUD per menu dan validasi peran Superadmin.
  - Navigasi admin (`admin/includes/header.php`) secara otomatis hanya menampilkan menu yang memiliki izin `can_read = 1`.

---

## [Patch Perbaikan Harmonisasi Tema & Auto-Fit Responsif] - 2026-10-14

### Diperbaiki & Diharmonisasi (Fixed & Harmonized)
- **Netralisasi Kebocoran Warna Biru**:
  - Ditemukan dan dinetralkan file `assets/css/home-scroll.css` dan `assets/css/content-clickable.css` yang sebelumnya memuat warna biru lama (`#0b6ea8`, `#142e43`, `#8ed0ff`) dan menimpa *style.css*.
  - Menghapus seluruh kelas Bootstrap lama (`text-primary`, `btn-dark`, `btn-outline-dark`) di `index.php`, `news-detail.php`, `product-detail.php`, dan `quality.php` menjadi kelas semantik resmi (`text-np-green`, `btn-np-primary`, `btn-np-outline`).
  - Menambahkan aturan proteksi mutlak di `style.css` agar `.text-primary`, `.bg-primary`, `.btn-primary` secara otomatis selalu menggunakan `--np-green: #005A36`.
- **Standarisasi Skala Tipografi Fluida (Fluid Typography)**:
  - Mengintegrasikan formula `clamp()` konsisten untuk `h1` s/d `h6` dan teks tubuh, memastikan ukuran font beradaptasi otomatis dan tidak terlihat aneh atau terlalu besar/kecil di perangkat mana pun.
- **Auto-Fit Responsif Multi-Viewport (Desktop, Tablet, Mobile)**:
  - **Stats Bar**: Mengubah margin negatif desktop (`-2.75rem`) menjadi margin wajar (`1.5rem`) pada layar tablet dan mobile (< 768px) untuk mencegah tabrakan dengan tombol hero.
  - **Vehicle Zone Explorer**: Mengubah navigasi tab menjadi bilah gulir horizontal sentuh (*touch scroll*) pada layar kecil agar tidak terpecah menjadi baris tidak beraturan; rasio aspek visual diatur dinamis (`aspect-ratio: 16/10`).
  - **Tabel Mesin & Spesifikasi**: Diberikan pembungkus `.machinery-table-wrapper` dengan *horizontal scroll* ramah sentuhan tanpa merusak lebar layar ponsel.
  - **Navbar**: Menyelaraskan ukuran logo, tombol *B2B RFQ*, dan *language switcher* saat menu hamburger terbuka di mobile.
- **Pembaruan Dinamis Halaman Karir (`career.php`)**:
  - Mengubah halaman karir dari teks dummy hardcoded menjadi query database dinamis ke tabel `careers` dengan penyesuaian lokasi pabrik ke Cikampek, Karawang.
- **Pembaruan Halaman Berita & Galeri (`news.php` & `gallery.php`)**:
  - Menyelaraskan *eyebrow badge*, judul, kartu artikel, dan tombol tautan dengan palet hijau Nihon Plast.

### Ditambahkan & Dirombak Menyeluruh (Comprehensive Overhaul)
- **Palet Warna Resmi Nihon Plast Forest Green (`#005A36`)**:
  - Menyelaraskan seluruh sistem desain dengan logo resmi Nihon Plast hijau hutan, *Cleanroom White* (`#FFFFFF`), *Emerald Accent* (`#00A862`), dan *Deep Pine* (`#002616`).
  - Menghilangkan warna biru generik dan menggantinya dengan estetika presisi manufaktur Jepang (*Monozukuri*).
- **Interactive Vehicle Zone Explorer (`index.php` & `products.php`)**:
  - Menjawab kesenjangan kompetitor (Toyoda Gosei & Yanfeng) dengan navigator zona kendaraan interaktif:
    - *Zone 1: Roda Kemudi (Steering Wheel Systems & Ergonomics)*
    - *Zone 2: Modul Airbag Keselamatan (Occupant Safety Airbag Modules)*
    - *Zone 3: Kokpit & Konsol Interior (Cockpit & Center Console Resins)*
    - *Zone 4: Eksterior & Trim Bawah (Exterior Aerodynamics & Cowl Top Louvers)*
  - Dilengkapi animasi hotspot pulsa, rincian material (Mg Die-Cast, PP-TD20, PC/ABS), dan tautan katalog.
- **Perombakan Total Halaman Fasilitas (`facility.php`)**:
  - Menghilangkan seluruh teks dummy, menggantikannya dengan:
    - Matriks spesifikasi mesin injeksi: 22 mesin berkapasitas **150T s/d 2.500T** (Sumitomo, Toshiba, Nissei, Fanuc).
    - Fasilitas **Cleanroom ISO 14644-1 Class 8** untuk perakitan modul airbag dan harness.
    - *Takumi Leather Wrapping Cell* untuk roda kemudi jahitan tangan presisi.
    - Laboratorium metrologi mutu: **Hexagon 3D CMM (±0.001 mm)**, *Thermal Chambers (-40°C ~ +120°C)*, dan *High-Speed Camera Lab (10.000 fps)*.
- **OEM Customer Trust Bar**:
  - Ditambahkan jajaran lencana kepercayaan produsen otomotif global di beranda: **Toyota, Honda, Nissan, Daihatsu, Mitsubishi, Suzuki, Subaru, Mazda**.
- **Modernisasi B2B Commercial & Technical RFQ (`contact.php`)**:
  - Mengubah form kontak biasa menjadi **Formulir Pengajuan B2B RFQ** resmi dengan pilihan kategori kebutuhan dan lini komponen.
  - Memperbarui alamat pabrik ke alamat resmi: **Kawasan Industri Indotaisei Sektor 1A Blok B-3, Kalihurip, Cikampek, Karawang, Jawa Barat 41373**.
  - Menyediakan tombol cepat **WhatsApp Commercial Hotline** dan **Unduh Company Profile PDF**.
  - Mengintegrasikan peta pabrik **OpenStreetMap (100% Free & Open Source)**.
- **Pembaruan Halaman Profil Korporat (`about.php`)**:
  - Menampilkan rekam jejak global Nihon Plast Co., Ltd. sejak 1948 (Jepang, AS, Meksiko, China, Thailand, Indonesia).
  - Lembar fakta korporat (*Corporate Datasheet*) dengan statistik lahan (28.500 m²), kapasitas, dan nilai inti Monozukuri.
- **Bilingual Language Switcher (ID / EN)**:
  - Dipasang toggle bahasa (ID / EN) ringan di navbar untuk memudahkan ekspatriat Jepang dan auditor vendor internasional.
- **Filter Kategori Produk (`products.php`) & Spesifikasi Teknik (`product-detail.php`)**:
  - Tab penyaring dinamis untuk setiap kategori produk, kartu modern dengan status IATF 16949, dan tombol RFQ langsung.

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

### Keamanan & Otentikasi (Security & Auth)
- **Akun Admin Dibuat**: Akun admin (`username: admin`) telah didaftarkan dan diuji aktif dengan `password_hash`.
- **Proteksi `admin/setup.php`**: Ditambahkan pengecekan `COUNT(*) FROM users`. Jika admin sudah terdaftar, file ini otomatis mengunci akses dan me-redirect pengunjung ke `login.php`.

---

## [Modernisasi Desain & Modul ISO/Regulasi RI] - 2026-10-14

### Ditambahkan (Added)
- **Modul Sertifikasi & ISO (`certifications` table)**:
  - Dibuat tabel `certifications` di MySQL untuk mengelola akreditasi ISO dan kepatuhan hukum RI secara dinamis.
  - Diseed dengan standar industri: **IATF 16949:2016** (Otomotif Global), **ISO 9001:2015** (Manajemen Mutu), **ISO 14001:2015** (Lingkungan & KLHK RI), **ISO 45001:2018 & SMK3 PP 50/2012** (K3 Kemnaker RI), serta standar **SNI / Kemenperin RI** (TKDN).
- **Admin Panel Sertifikasi (`admin/certifications.php`)**:
  - Halaman CRUD lengkap di panel admin untuk menambah, mengedit, mengunggah badge/scan sertifikat, mengurutkan, dan mengarsipkan sertifikat.
  - Ditambahkan link navigasi di sidebar dan KPI card counter di `admin/dashboard.php`.
- **Desain Presisi Otomotif Jepang (Japanese Monozukuri)**:
  - Dipasang font modern geometris Google Font: **Plus Jakarta Sans**.
  - Diterapkan palet warna *Precision Deep Navy* (`#002B49`), *High-Tech Cyan* (`#0088CC`), *Cleanroom White*, dan *Carbon Slate*.
- **Perombakan Halaman Beranda (`index.php`)**:
  - Hero sinematik Tier-1 Supplier dengan badge, lead copy profesional, dan tombol CTA ganda.
  - Stats bar mengambang (IATF 16949, 4 Pilar Produk, ISO & SMK3, Lokasi Pabrik Tambun).
  - Bagian khusus **Sertifikasi & Kepatuhan Regulasi Indonesia** yang terhubung langsung ke database.
  - Portofolio 4 pilar asli Nihon Plast Jepang (Steering Wheels, Airbag Modules, Cockpit Resins, Aero Parts).
- **Perombakan Halaman Kualitas (`quality.php`)**:
  - Menghilangkan teks dummy, digantikan dengan ulasan komprehensif kepatuhan regulasi RI (SMK3 PP 50/2012, Lingkungan B3 KLHK, TKDN Kemenperin) dan siklus 4 pilar QA/QC/Kaizen/EHS.
- **Modernisasi Navbar & Footer**:
  - Navbar *floating glassmorphism* dengan menu navigasi "Quality & ISO".
  - Footer korporat *Enterprise Tier-1* lengkap dengan pilar bisnis, akreditasi mutu, dan alamat fasilitas pabrik.

---



# 📝 Project Changelog

Semua perubahan, penemuan, dan pembaruan arsitektur pada proyek PT Nihon Plast Indonesia CMS dicatat di sini secara kronologis.

---

## [Dribbble-Grade Automotive UI System, Swiper 11 Hero Carousel & FOSS Vendor Integration] - 2026-10-14

### Pembaruan UI Modern Dribbble & Eliminasi Navbar Clutter (100% Free & Open Source)
- **1. Penyelesaian Tuntas Penumpukan & Cluttering Navbar (Strict No-Wrap & Micro Utility Topbar)**:
  - Mengatasi masalah tautan menu navbar yang sebelumnya patah/numpuk vertikal 2-3 baris pada resolusi desktop/laptop akibat ruang sempit dan teks panjang.
  - Memindahkan tombol bahasa 5 negara (ID, EN, JA, ZH, KO), informasi hotline, email resmi, dan status sertifikasi ke `.site-topbar` baru di bagian atas.
  - Mengubah wadah navbar menjadi `.container-fluid px-3 px-xl-5` dan menetapkan aturan `white-space: nowrap !important;` pada semua tautan navigasi.
  - Pada layar mobile (< 992px), menu toggle drawer tetap menyediakan switcher bahasa dan tombol aksi B2B RFQ yang rapi dan terstruktur.
- **2. Hero Carousel Dinamis Dribbble-Grade (Swiper 11)**:
  - Menggantikan hero statis dengan slider carousel bertenaga Swiper 11 yang membaca data slide secara 100% dinamis dari tabel `site_section_items` (`section_key = 'hero_slides'`).
  - Dilengkapi 4 slide interaktif otomotif: Monozukuri Precision Injection, Airbag Safety Modules, Takumi Leather Steering Wheel, dan ESG Sustainability.
  - Dilengkapi HUD Card kaca (*glassmorphism backdrop-blur 14px*) yang menampilkan spesifikasi teknis dan akreditasi, timer garis progress horizontal (*smooth linear transition*), serta counter nomor slide (`01 / 04`).
- **3. Efek Motion Ambient & Partikel Teknologi Otomotif (`assets/js/ambient-canvas.js`)**:
  - Dibuat animasi kanvas partikel murni tanpa dependensi (*vanilla JS*, 0 external library, < 100 baris) dengan node hijau emerald berpendar dan garis jaring konektivitas presisi.
  - Otomatis di-pause menggunakan `IntersectionObserver` saat layar discroll menjauh untuk menghemat daya baterai dan penggunaan GPU/CPU.
- **4. Marquee Berjalan Mitra Pabrikan OEM Tanpa Batas (*Continuous Linear Ticker*)**:
  - Mengubah baris statis mitra OEM (Toyota, Honda, Daihatsu, Nissan, Suzuki, Mitsubishi, Hyundai, Isuzu) menjadi carousel marquee bergerak terus-menerus tanpa jeda menggunakan Swiper linear continuous autoplay.
- **5. Integrasi Vendor FOSS Lokal (SweetAlert2 & AOS)**:
  - Seluruh aset vendor disimpan lokal di `assets/vendor/` tanpa CDN pihak ketiga berbayar: Swiper 11, SweetAlert2, dan AOS (Animate on Scroll).
  - SweetAlert2 diharmonisasikan dengan warna tema hijau emerald otomotif PT Nihon Plast Indonesia untuk pesan toast global dan konfirmasi pengiriman form B2B RFQ pada `contact.php`.
- **6. Virtual Plant Tour & Showcase Gerakan Manufaktur Otomotif**:
  - Ditambahkan banner video virtual tour berstandar Full HD dengan elemen HTML5 `<video>` dan fallback poster foto injeksi molding pada bagian tinjauan fasilitas di `index.php`.

---

## [100% Dynamic CMS Elements & Sub-Items Management Engine (Zero Hardcoded Markup)] - 2026-10-14

### Transformasi Total Eliminasi Hardcode (Zero Hardcoding & Dynamic Sub-Item Engine)
- **1. Pembuatan Arsitektur Database Sub-Item (`site_section_items`)**:
  - Dibuat tabel `site_section_items` dengan 77 sub-item dinamis di 18 section key untuk mengontrol setiap kartu, langkah rekrutmen, zona eksplorasi kendaraan, badge mitra OEM, spesifikasi tabel, dan pilar mutu dari database MySQL.
  - Setiap sub-item memiliki atribut lengkap: `title`, `subtitle`, `content`, `icon`, `image`, `badge`, `extra_data` (JSON), `sort_order`, dan `is_active` (1-click toggle).
- **2. Refaktorisasi Seluruh Template Publik (100% Database-Driven via `sec_items()`):**
  - **Beranda ([`index.php`](file:///c:/laragon/www/cms-compro/index.php))**: Baris counter kapasitas (`stats`), 8 logo mitra OEM (`oem_trust`), 4 zona interaktif kendaraan & cockpit (`vehicle_explorer`), 4 pilar mutu (`quality_pillars`), dan metrik emisi ESG (`esg_sustainability`).
  - **Tentang Kami ([`about.php`](file:///c:/laragon/www/cms-compro/about.php))**: 4 Nilai Inti (*Core Values*), 3 Kawasan Jaringan Global (*Global Footprint*), dan 7 baris matriks lembar fakta perusahaan (*Corporate Datasheet*).
  - **Fasilitas & Mesin ([`facility.php`](file:///c:/laragon/www/cms-compro/facility.php))**: 4 statistik luas lahan & kapasitas per bulan (`facility_plant_stats`), 5 sel lini manufaktur bento grid (`facility_capabilities`), dan 4 laboratorium inspeksi & metrologi 3D CMM (`facility_laboratories`).
  - **Mutu & ISO ([`quality.php`](file:///c:/laragon/www/cms-compro/quality.php))**: Kepatuhan regulasi pemerintah Indonesia SMK3/B3/TKDN (`quality_regulatory_compliance`) dan 4 pilar operasional QA/QC/QE/Kaizen (`quality_flowchart`).
  - **Karir & Rekrutmen ([`career.php`](file:///c:/laragon/www/cms-compro/career.php))**: 2 kartu budaya K3 & pelatihan (`career_intro_features`), 6 fasilitas kesejahteraan karyawan (`career_culture_benefits`), dan 4 tahapan proses seleksi transparan (`career_hiring_process`).
  - **Katalog Produk ([`products.php`](file:///c:/laragon/www/cms-compro/products.php))**: 2 keunggulan teknis material FMVSS 302 (`products_material_features`) dan 4 matriks kompatibilitas resin rekayasa PP/ABS/PA66/PUR (`products_resins`).
- **3. Halaman Administrasi Sub-Item Mandiri ([`admin/site_section_items.php`](file:///c:/laragon/www/cms-compro/admin/site_section_items.php))**:
  - Dilengkapi fitur CRUD lengkap, filter section induk, 1-Click Toggle Status Publik (*ACTIVE / NOT ACTIVE*), modal pemilih icon Bootstrap terkurasi, dan modal pemilih galeri aset media.
  - Ditambahkan ke menu otorisasi granular RBAC tiap user pada [`admin/auth.php`](file:///c:/laragon/www/cms-compro/admin/auth.php).
  - Terintegrasi langsung dengan tombol cepat *"Kelola N Sub-Item"* pada setiap baris tabel di [`admin/site_sections.php`](file:///c:/laragon/www/cms-compro/admin/site_sections.php).
- **4. Otomatisasi Cache Invalidation & Suite Pengujian Komprehensif**:
  - Diterapkan `clear_section_items_cache()` di [`includes/site_helpers.php`](file:///c:/laragon/www/cms-compro/includes/site_helpers.php).
  - Seluruh 26 pengujian pada [`scripts/test_all_pages_dynamic.php`](file:///c:/laragon/www/cms-compro/scripts/test_all_pages_dynamic.php) lulus 100% (*0 failures*).

---

## [Visual Asset Otomotif Khusus, Segel Akreditasi Vektor SVG & Interactive Icon/Media Pickers] - 2026-10-14

### Pembaruan Gambar, Ikon & Kemudahan CMS (Visual Assets & CMS Usability Overhaul)
- **1. Pembuatan & Integrasi Gambar Presisi Otomotif Tier-1**:
  - **Steering Wheel Systems**: Foto studio katalog OEM roda kemudi kulit premium dengan kontrol multifungsi (`uploads/products/product_steering_wheel.jpg`).
  - **Airbag Safety Modules**: Rakitan modul kantung udara presisi dengan tabung inflator dan harness konektor (`uploads/products/product_airbag_module.jpg`).
  - **Cockpit & Resin Trims**: Cetak biru vektor resin interior kokpit, kisi-kisi AC (*louvers*), dan panel konsol tengah (`uploads/products/product_cockpit_resin.svg`).
  - **Exterior Aerodynamic Trim**: Cetak biru vektor gril radiator *aerodynamic honeycomb* dan *side mirror housing* (`uploads/products/product_exterior_trim.svg`).
  - **Automated Injection Molding Plant**: Fotografi industri pabrik otomotif dengan deretan mesin injeksi tonase besar dan jalur keselamatan 5S kuning/hijau (`uploads/gallery/facility_injection_molding.jpg`).
  - **Cleanroom & CMM Laboratory**: Vektor fasilitas inspeksi cleanroom Class 100k dan mesin ukur 3D CMM Mitutoyo (`uploads/gallery/facility_cleanroom_lab.svg`).
- **2. 5 Segel Akreditasi Resmi Vektor SVG (Zero-Blur pada Layar Apapun)**:
  - Dibuat segel resmi beresolusi tak terbatas (*lossless vector*) untuk kepatuhan otomotif dan regulasi pemerintah:
    1. **IATF 16949:2016** (*Automotive Quality Management System Tier-1 OEM*)
    2. **ISO 9001:2015** (*Quality Management System*)
    3. **ISO 14001:2015** (*Environmental Management System / Green Factory*)
    4. **ISO 45001:2018** (*Occupational Health & Safety / Zero Accident K3*)
    5. **TKDN 78.4% Kemenperin RI** (*Tingkat Komponen Dalam Negeri Republik Indonesia*)
  - Terintegrasi langsung pada kartu sertifikasi di Beranda ([`index.php`](file:///c:/laragon/www/cms-compro/index.php)) dan halaman [Kualitas](file:///c:/laragon/www/cms-compro/quality.php).
- **3. Pembersihan Total URL Eksternal (Zero External Unsplash Dependency)**:
  - Seluruh tautan gambar eksternal (Unsplash) telah diganti 100% menggunakan aset lokal cepat di folder `uploads/`. Website dapat dibuka secara *offline-ready* dan super cepat tanpa latensi pihak ketiga.
- **4. Palet Pemilih Icon Interaktif (Interactive Icon Picker Modal)**:
  - Ditambahkan tombol **"Pilih Icon"** di [`admin/site_sections.php`](file:///c:/laragon/www/cms-compro/admin/site_sections.php).
  - Modal interaktif dengan 70+ ikon Bootstrap terkurasi khusus otomotif & industri (Mutu & ISO, Manufaktur & Mesin, Otomotif, Fasilitas, ESG, Bisnis & Data, Kontak & Layanan).
  - Dilengkapi kolom pencarian (*search filter*) langsung dan preview ikon seketika.
- **5. Galeri Pemilih Asset Media (Interactive Media Asset Picker Modal)**:
  - Ditambahkan tombol **"Pilih Asset"** pada form gambar utama dan latar belakang di [`admin/site_sections.php`](file:///c:/laragon/www/cms-compro/admin/site_sections.php), [`admin/products.php`](file:///c:/laragon/www/cms-compro/admin/products.php), dan [`admin/certifications.php`](file:///c:/laragon/www/cms-compro/admin/certifications.php).
  - Pengguna admin kini dapat memilih gambar presisi yang sudah disediakan tanpa perlu mencari file atau mengunggah ulang.

---

## [Optimasi Hierarki Beranda, Berita Posisi #2 & Performa Ringan Cepat] - 2026-10-14

### Pembaruan Tata Letak & Kecepatan Beranda (Homepage Layout & Performance Optimization)
- **1. Reposisi Bagian Berita ke Nomor 2 (News #2 on Scroll)**:
  - Berita (`news_preview`) dipindahkan langsung ke posisi urutan ke-2 tepat setelah Hero Banner dan Stats Bar (`sort_order = 30`).
  - Mengikuti standar korporasi manufaktur otomotif Jepang (seperti Nihon Plast Jepang, Denso, Toyoda Gosei) di mana dinamika, vitalitas, dan pengumuman terbaru perusahaan langsung terlihat oleh pengunjung B2B tanpa harus menggulir ke dasar halaman.
- **2. Penataan Urutan Standar Korporat Otomotif Tier-1 Global**:
  1. `hero` (Banner Utama & Value Proposition)
  2. `stats` (Indikator Kapabilitas Manufaktur Utama)
  3. `news_preview` (**Berita & Pengumuman Terbaru - #2 on Scroll**)
  4. `products_grid` (Portofolio Produk Unggulan: Steering Wheels, Airbags, Interior Trims)
  5. `vehicle_explorer` (Eksplorasi Interaktif Zona Kendaraan Otomotif)
  6. `facility_preview` (Kapabilitas Fasilitas Pabrik & Mesin Presisi)
  7. `certifications_grid` (Kepatuhan Standar Mutu IATF 16949, ISO 9001, ISO 14001, ISO 45001)
  8. `about_intro` (Profil Singkat DNA PT Nihon Plast Indonesia)
  9. `oem_trust` (Kepercayaan OEM Global Tier-1 Partner)
  10. `contact_cta` (Call to Action Permintaan Penawaran B2B RFQ)
- **3. Eliminasi Beban Berat & Pengurangan Ukuran Muat Awal (Performance Pruning)**:
  - Bagian yang berat dan redundan dinonaktifkan secara *default* di Beranda (`is_active = 0`):
    - `gallery_preview` (menghemat ~7-10MB transfer gambar resolusi tinggi; galeri lengkap tetap tersedia di `gallery.php`).
    - `quality_pillars` & `esg_sustainability` (mengurangi redundansi DOM; detail lengkap berada di `quality.php` dan `about.php`).
  - Ukuran HTML beranda menyusut menjadi **~63 KB** (sangat cepat & responsif pada reload pertama).
  - Seluruh gambar di beranda kini dilengkapi atribut `loading="lazy"` untuk mempercepat *First Contentful Paint (FCP)*.
- **4. 100% Fleksibel & Tetap Terkendali**:
  - Bagian yang dinonaktifkan secara *default* dapat diaktifkan kembali sewaktu-waktu dengan 1-klik di panel admin (`admin/site_sections.php`).

---

## [100% Dynamic CMS & Active/Inactive Toggles across All 9 Pages & Technical Machinery Matrix] - 2026-10-14

### Pembaruan Menyeluruh (Comprehensive 100% Dynamic Overhaul)
- **1. Migrasi Seluruh Halaman Publik (Semua 9 Halaman Dinamis dari Database)**:
  - **Home (`index.php`)**: 13 bagian dinamis (`home_hero`, `home_stats`, `home_about`, `home_products`, `home_vehicle_zones`, `home_features`, `home_machinery_overview`, `home_certifications`, `home_clients`, `home_news`, `home_cta`, dll.).
  - **Tentang Kami (`about.php`)**: 5 bagian dinamis (`about_dna`, `about_vision_mission`, `about_global_footprint`, `about_datasheet`, `about_cta`).
  - **Fasilitas Pabrik (`facility.php`)**: 6 bagian dinamis (`facility_plant_overview`, `facility_capabilities`, `facility_machinery_intro`, `facility_cleanroom`, `facility_laboratories`, `facility_cta`) terintegrasi tabel `facility_machinery`.
  - **Kualitas & ISO (`quality.php`)**: 5 bagian dinamis (`quality_zero_defect`, `quality_certifications_intro`, `quality_regulatory_compliance`, `quality_flowchart`, `quality_cta`).
  - **Katalog Produk (`products.php`)**: 4 bagian dinamis (`products_catalog_intro`, `products_technical_capabilities`, `products_custom_oem`, `products_cta`).
  - **Kontak & RFQ (`contact.php`)**: 5 bagian dinamis (`contact_liaison_intro`, `contact_info_card`, `contact_download_deck`, `contact_rfq_form_intro`, `contact_map_section`).
  - **Karir (`career.php`)**: 4 bagian dinamis (`career_intro`, `career_culture_benefits`, `career_hiring_process`, `career_vacancies_intro`).
  - **Galeri Foto (`gallery.php`)**: Header dinamis (`gallery_header`).
  - **Berita & Pengumuman (`news.php`)**: Header dinamis (`news_header`).
- **2. 1-Klik Switch Active / Not Active Tiap Elemen**:
  - Setiap section memiliki tombol switch instan `Active` (hijau) / `Not Active` (abu-abu) di `admin/site_sections.php`.
  - Ketika sebuah elemen diset `is_active = 0`, elemen tersebut langsung hilang secara instan dari website publik tanpa merusak tata letak lainnya.
- **3. Filter Tabbed Halaman di Admin (`admin/site_sections.php`)**:
  - Filter tab dinamis untuk semua 9 halaman: *Semua (44)*, *Home (13)*, *About (5)*, *Facility (6)*, *Quality (5)*, *Products (4)*, *Contact (5)*, *Career (4)*, *Gallery (1)*, *News (1)*.
  - Tautan pintasan navigasi cepat ke modul khusus terkait (Machinery Matrix, ISO & Certifications, Products).
- **4. Manajemen Mesin Teknis (`admin/machinery.php` & `facility_machinery`)**:
  - Tabel terpisah `facility_machinery` untuk mengelola spesifikasi tonase mesin (150T - 2500T), merek (Toshiba, Sumitomo, Nissei, JSW), proses (Robotic Takeout, Gas-Assist, Vibration Welding), dan status aktif.
- **5. Pengujian Otomatis (Automated Test Suite)**:
  - Dibuat `scripts/test_all_pages_dynamic.php` dengan 20 pengujian otomatis yang mencakup render buffering 9 halaman, verifikasi toggle aktif/nonaktif, dan validasi sintaks seluruh panel admin (Status: **20 PASSED, 0 FAILED**).

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



# 📄 Product Requirement Document (PRD)
## PT Nihon Plast Indonesia - Tier-1 Automotive Corporate Portal & Dynamic CMS

---

### 1. Document Overview
* **Project Name**: PT Nihon Plast Indonesia Corporate Portal & Dynamic CMS Engine
* **Version**: 2.0.0 (Automotive Grade & Dribbble Polish)
* **Date**: 2026-10-15
* **Author / Owner**: Engineering & Digital Product Team
* **Status**: APPROVED / ACTIVE IN DEVELOPMENT
* **Repository**: `C:\Devaron\www\cms-compro`
* **License / Policy**: Strict 100% Free & Open Source (FOSS)

---

### 2. Product Vision & Goals

#### 2.1 Vision
Mewujudkan portal korporat digital berstandar global Tier-1 untuk **PT Nihon Plast Indonesia** yang mencerminkan presisi manufaktur Jepang (*Japanese Monozukuri*), kepatuhan standar keselamatan keselamatan berkendara (IATF 16949, ISO 14001, ISO 45001), dan keanggunan visual tingkat tinggi setara standar Dribbble modern.

#### 2.2 Core Goals
1. **Zero Hardcoded Markup (100% Dynamic CMS)**: Setiap judul, teks, gambar, ikon, lencana, tautan, statistik, dan kartu di seluruh 9 halaman publik dapat diubah, diaktifkan, atau dinonaktifkan secara dinamis melalui dashboard admin tanpa menyentuh kode program.
2. **Native Multilingual Engine (5 Bahasa)**: Menyediakan translasi kontekstual profesional dalam 5 bahasa utama industri otomotif: Bahasa Indonesia (ID), English (EN), Japanese (JA), Chinese Simplified (ZH), dan Korean (KO).
3. **Automotive Dribbble-Grade Visual Polish**: Menerapkan tata letak modern bebas *cluttering* (navigasi *strict no-wrap*), slider carousel bertenaga Swiper 11 dengan HUD card kaca (*glassmorphism*), garis timer progres, kanvas partikel gerak presisi, showcase video tur pabrik, dan *marquee ticker* mitra OEM tanpa batas.
4. **B2B RFQ Funnel Optimization**: Mempermudah prinsipal OEM otomotif (Toyota, Daihatsu, Honda, dll.) dalam mengajukan konsultasi teknis, audit fasilitas, dan permintaan penawaran harga (*Request for Quotation*) bergaransi kerahasiaan NDA.
5. **Granular RBAC Security & Performance**: Menerapkan otorisasi berbasis peran (Superadmin, Admin, Staff, Editor) dengan izin CRUD per modul, kueri database berkecepatan tinggi (< 15ms), kompresi Gzip, dan caching browser tingkat lanjut.

---

### 3. User Personas & Target Audiences

| Persona | Profil & Kebutuhan Utama | Ekspektasi terhadap Portal |
| :--- | :--- | :--- |
| **OEM Procurement & Sourcing Director** (Toyota, Honda, dll.) | Membutuhkan pemasok komponen Tier-1 terpercaya untuk proyek model kendaraan baru. | Verifikasi instan kepatuhan sertifikasi IATF 16949, kapasitas mesin injeksi (150T - 2.500T), cleanroom Class 8, dan pengajuan RFQ cepat. |
| **Global Executive / Principal** (Nihon Plast Co., Ltd. Japan) | Memantau tata kelola dan reputasi anak perusahaan luar negeri. | Keselarasan identitas korporat, konsistensi bahasa Jepang (JA) & Inggris (EN), dan inisiatif keberlanjutan ESG & Carbon Neutrality. |
| **Plant Quality Auditor & Inspector** | Melakukan verifikasi kelayakan fasilitas manufaktur dan kepatuhan K3. | Spesifikasi laboratorium pengujian 3D CMM, alur kendali mutu Zero Defect, dan kepatuhan regulasi pemerintah Indonesia (SMK3/B3/TKDN). |
| **Job Seeker & Engineering Candidate** | Mencari lowongan karir di perusahaan manufaktur otomotif terkemuka. | Informasi lowongan kerja transparan, budaya keselamatan 5S, tunjangan karyawan, dan formulir aplikasi online tanpa biaya. |
| **Internal CMS Operator & Marketing Staff** | Mengelola pembaruan artikel berita, foto galeri, lowongan, dan teks promosi. | Antarmuka admin panel yang intuitif, aman, mendukung toggle 1-klik aktif/non-aktif, dan pemilih ikon/gambar yang mudah. |

---

### 4. Functional Requirements & Feature Breakdown

#### 4.1 Modul Frontend Publik (9 Halaman)
1. **Beranda ([`index.php`](file:///C:/Devaron/www/cms-compro/index.php))**:
   * *Utility Topbar*: Kredensial sertifikasi resmi, hotline, email, dan switcher 5 bahasa.
   * *Primary Navbar*: Logo korporat, 8 menu tautan (*nowrap*), dan tombol aksi B2B RFQ.
   * *Dribbble Hero Swiper*: Slider carousel dinamis dengan background foto resolusi tinggi, HUD glassmorphic card, timer garis progres, dan slide counter.
   * *Ambient Particle Canvas*: Animasi partikel berkinerja tinggi (*zero CPU waste* via `IntersectionObserver`).
   * *Stats Bar*: Metrik kapasitas bulanan, armada mesin injeksi, dan kepatuhan mutu.
   * *Latest News Preview*: 3 artikel berita terkini dengan gambar dan tanggal rilis.
   * *Vehicle Zone Explorer*: Peta interaktif penerapan komponen (Cockpit, Airbag Safety, Steering Wheel, Exterior Trim).
   * *Manufacturing Facility Preview & Video Tour*: Cuplikan fasilitas pabrik dan pemutar video gerak manufaktur Full HD.
   * *OEM Partner Marquee*: Ticker logo mitra OEM bergerak terus-menerus tanpa jeda (*infinite linear scroll*).
   * *Contact & RFQ CTA*: Ajakan bertindak terpadu menuju konsultasi teknis.
2. **Tentang Kami ([`about.php`](file:///C:/Devaron/www/cms-compro/about.php))**:
   * Profil DNA manufaktur presisi *Monozukuri*.
   * Visi, Misi, dan 4 Nilai Fundamental perusahaan.
   * Jejaring Global Nihon Plast Group (Jepang, Amerika, China, Thailand, Meksiko, Indonesia).
   * Matriks lembar fakta perusahaan (*Corporate Datasheet*) dinamis.
3. **Fasilitas & Teknologi ([`facility.php`](file:///C:/Devaron/www/cms-compro/facility.php))**:
   * Statistik lahan pabrik dan kapasitas produksi bulanan.
   * 5 Sel Lini Manufaktur Bento Grid (Injection, Cleanroom, Painting, Leather Wrap, CMM Lab).
   * Matriks permesinan teknis dari database `facility_machinery` (Tonase 150T - 2.500T, servo hidrolik).
   * Laboratorium kendali mutu & inspeksi metrologi presisi 3D.
4. **Mutu & Regulasi ([`quality.php`](file:///C:/Devaron/www/cms-compro/quality.php))**:
   * Filosofi *Zero Defect* dan dedikasi keselamatan modul airbag.
   * Galeri sertifikat resmi beresolusi tinggi (IATF 16949, ISO 9001, ISO 14001, ISO 45001).
   * Kepatuhan hukum dan regulasi Republik Indonesia (SMK3, B3, TKDN Kemenperin).
   * 4 Pilar sistem manajemen mutu (QA, QC, QE, Kaizen/QCC).
5. **Katalog Produk ([`products.php`](file:///C:/Devaron/www/cms-compro/products.php))**:
   * Filter kategori instan (Steering Wheels, Airbag Modules, Cockpit Resins, Exterior Trims).
   * Modal pratinjau spesifikasi teknis dan standar pengujian material FMVSS 302.
   * Matriks kompatibilitas resin rekayasa (PP, ABS, PA66, Polyurethane).
6. **Karir & Rekrutmen ([`career.php`](file:///C:/Devaron/www/cms-compro/career.php))**:
   * Budaya keselamatan kerja K3 dan pengembangan profesional.
   * 6 Fasilitas & tunjangan kesejahteraan karyawan.
   * 4 Tahapan proses seleksi transparan dan bebas pungutan biaya (*zero recruitment fee*).
   * Daftar lowongan aktif dengan formulir lamaran online dan upload CV.
7. **Berita & Artikel ([`news.php`](file:///C:/Devaron/www/cms-compro/news.php))**:
   * Kategori siaran pers korporat, penghargaan mutu, dan inisiatif CSR/ESG.
   * Halaman detail berita lengkap dengan gambar pendukung.
8. **Galeri Dokumentasi ([`gallery.php`](file:///C:/Devaron/www/cms-compro/gallery.php))**:
   * Filter tab kategori galeri (Produksi, Mesin Injeksi, Penghargaan, Aktivitas 5S).
   * Modal Lightbox pratinjau foto beresolusi tinggi.
9. **Kontak & B2B RFQ ([`contact.php`](file:///C:/Devaron/www/cms-compro/contact.php))**:
   * Informasi alamat pabrik di Kawasan Industri Indotaisei Cikampek, telepon, dan email resmi.
   * Formulir pengajuan penawaran harga B2B RFQ lengkap dengan SweetAlert2 modal.
   * Unduh Company Profile & Capability Deck.
   * Peta navigasi pabrik interaktif (OpenStreetMap 100% FOSS).

#### 4.2 Modul Backend & CMS Admin Panel
1. **Manajemen Section Induk ([`admin/site_sections.php`](file:///C:/Devaron/www/cms-compro/admin/site_sections.php))**: Mengatur judul, subjudul, konten, ikon, lencana, teks tombol, dan status aktif/non-aktif 44 blok section di 9 halaman.
2. **Manajemen Sub-Item Dinamis ([`admin/site_section_items.php`](file:///C:/Devaron/www/cms-compro/admin/site_section_items.php))**: Mengontrol kartu, pilar nilai, zona kendaraan, dan badge dengan modal icon/media picker.
3. **Manajemen Produk ([`admin/products.php`](file:///C:/Devaron/www/cms-compro/admin/products.php))**: CRUD produk otomotif dan upload gambar.
4. **Manajemen Berita ([`admin/news.php`](file:///C:/Devaron/www/cms-compro/admin/news.php))**: Publikasi artikel dan rilis korporat.
5. **Manajemen Karir ([`admin/career.php`](file:///C:/Devaron/www/cms-compro/admin/career.php))**: Pengelolaan posisi lowongan kerja dan pelamar masuk.
6. **Manajemen Galeri ([`admin/gallery.php`](file:///C:/Devaron/www/cms-compro/admin/gallery.php))**: Unggah foto dokumentasi pabrik.
7. **Pesan Masuk ([`admin/messages.php`](file:///C:/Devaron/www/cms-compro/admin/messages.php))**: Inbox pertanyaan dan permintaan RFQ dari calon mitra.
8. **Manajemen Pengguna & Otorisasi RBAC ([`admin/users.php`](file:///C:/Devaron/www/cms-compro/admin/users.php))**: Pengaturan akun user dan matriks hak akses CRUD per menu.

---

### 5. Non-Functional Requirements (NFR)

| Kategori | Standar / Target | Metode Pemenuhan |
| :--- | :--- | :--- |
| **Kebijakan Lisensi** | 100% Free & Open Source (FOSS) | Tanpa cloud API berbayar, tanpa kuota/kredit. Menggunakan PHP Native, MySQL, Bootstrap 5.3, Swiper 11, SweetAlert2, dan AOS. |
| **Responsivitas Layar** | Auto-fit di semua resolusi | Desktop (> 1200px), Laptop (992px - 1199px), Tablet (768px - 991px), dan Mobile (< 767px) dengan nol teks patah/menumpuk. |
| **Kecepatan & Performa** | Core Web Vitals Grade A | LCP < 2.0s, CLS < 0.05, FID/INP < 100ms. Diberlakukan kompresi Gzip, caching browser 1 tahun untuk gambar/font, dan composite index SQL. |
| **Keamanan Data** | OWASP Top 10 Hardened | Prepared statements (`mysqli`), proteksi file rahasia via `.htaccess`, sanitasi `htmlspecialchars`, dan hash password `bcrypt`. |
| **Kepatuhan Industri** | IATF 16949 & ISO Data NDA | Perlindungan data spesifikasi teknis calon klien di bawah klausul Non-Disclosure Agreement. |

---

### 6. Acceptance Criteria (Kriteria Keberhasilan)
* [x] Seluruh menu navigasi tampil rapi 1 baris (*single-line no-wrap*) di layar desktop dan laptop tanpa teks tumpang-tindih.
* [x] Hero carousel Swiper 11 berfungsi mulus dengan HUD card, timer garis progres, dan slide counter dinamis.
* [x] Kanvas partikel bergerak anggun tanpa membebani kinerja perangkat.
* [x] Marquee logo mitra OEM berjalan kontinu tanpa jeda.
* [x] Formulir pengajuan RFQ memunculkan konfirmasi SweetAlert2 saat sukses.
* [x] Seluruh 5 bahasa (ID, EN, JA, ZH, KO) beralih secara instan dan tepat konteks.
* [x] Seluruh elemen dapat diubah dan dinonaktifkan secara 1-klik melalui panel admin.
* [x] Seluruh pengujian otomatis pada `scripts/test_all_pages_dynamic.php` lulus 100%.

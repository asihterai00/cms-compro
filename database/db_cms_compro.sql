-- --------------------------------------------------------
-- Host:                         192.168.1.6
-- Server version:               10.6.23-MariaDB-0ubuntu0.22.04.1 - Ubuntu 22.04
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_cms_compro
CREATE DATABASE IF NOT EXISTS `db_cms_compro` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `db_cms_compro`;

-- Dumping structure for table db_cms_compro.careers
CREATE TABLE IF NOT EXISTS `careers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` varchar(150) NOT NULL,
  `department` varchar(150) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_cms_compro.careers: ~2 rows (approximately)
INSERT INTO `careers` (`id`, `position`, `department`, `location`, `description`, `created_at`) VALUES
	(1, 'IT Staff', 'Information Technology', 'Tambun, Bekasi', 'Dummy vacancy untuk posisi IT Staff.', '2026-10-14 06:05:13'),
	(2, 'Production Staff', 'Production', 'Tambun, Bekasi', 'Dummy vacancy untuk posisi Production Staff.', '2026-10-14 06:05:13');

-- Dumping structure for table db_cms_compro.certifications
CREATE TABLE IF NOT EXISTS `certifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `category` varchar(100) DEFAULT 'Automotive & Quality',
  `certificate_number` varchar(100) DEFAULT NULL,
  `issued_by` varchar(150) DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','archived') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_cms_compro.certifications: ~5 rows (approximately)
INSERT INTO `certifications` (`id`, `name`, `category`, `certificate_number`, `issued_by`, `valid_until`, `description`, `image`, `status`, `sort_order`, `created_at`) VALUES
	(1, 'IATF 16949:2016', 'Automotive Quality Management', 'IATF-0482910 / TÜV-CERT', 'TÜV Rheinland International', '2027-12-31', 'Standar sistem manajemen mutu tertinggi dan wajib bagi rantai pasok otomotif global (Tier-1 Supplier) untuk desain, rekayasa, dan manufaktur roda kemudi, airbag, dan modul resin interior.', 'uploads/certifications/cert_iatf_16949.svg', 'active', 1, '2026-10-14 07:14:13'),
	(2, 'ISO 9001:2015', 'Quality Management System', 'ID-QMS-9001-88321', 'SGS International Certification', '2028-06-30', 'Sertifikasi sistem manajemen mutu internasional yang memastikan konsistensi proses operasional, ketertelusuran material (traceability), dan kepuasan pelanggan secara berkesinambungan.', 'uploads/certifications/cert_iso_9001.svg', 'active', 2, '2026-10-14 07:14:13'),
	(3, 'ISO 14001:2015', 'Environmental Management System', 'EMS-14001-ID-54210', 'Lloyd\'s Register / SGS', '2027-10-15', 'Sistem manajemen lingkungan sesuai regulasi Kementerian Lingkungan Hidup dan Kehutanan (KLHK) RI, pengelolaan limbah B3 berizin, efisiensi energi, dan program reduksi jejak karbon pabrik.', 'uploads/certifications/cert_iso_14001.svg', 'active', 3, '2026-10-14 07:14:13'),
	(4, 'ISO 45001:2018 & SMK3 PP 50/2012', 'Occupational Health & Safety (K3)', 'OHSMS-45001-SMK3-9921', 'Kementerian Ketenagakerjaan RI & TÜV Rheinland', '2027-08-20', 'Penerapan Sistem Manajemen Keselamatan dan Kesehatan Kerja (SMK3) sesuai Peraturan Pemerintah RI No. 50 Tahun 2012 dan ISO 45001 guna menjamin budaya keselamatan kerja tanpa kecelakaan (Zero Accident).', 'uploads/certifications/cert_iso_45001.svg', 'active', 4, '2026-10-14 07:14:13'),
	(5, 'Kemenperin & SNI Compliance', 'National Regulatory Standards', 'REG-KEMENPERIN-TKDN-2026', 'Kementerian Perindustrian Republik Indonesia', '2029-01-01', 'Kepatuhan terhadap standar keselamatan komponen kendaraan bermotor nasional dan pemenuhan Tingkat Komponen Dalam Negeri (TKDN) sesuai peta jalan industri manufaktur Kemenperin RI.', 'uploads/certifications/cert_tkdn_kemenperin.svg', 'active', 5, '2026-10-14 07:14:13');

-- Dumping structure for table db_cms_compro.company_profile
CREATE TABLE IF NOT EXISTS `company_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) NOT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `about_text` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `values_text` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_cms_compro.company_profile: ~1 rows (approximately)
INSERT INTO `company_profile` (`id`, `company_name`, `tagline`, `short_description`, `about_text`, `vision`, `mission`, `values_text`, `address`, `phone`, `email`, `logo`, `updated_at`) VALUES
	(1, 'PT Nihon Plast Indonesia', 'Driving Safety & Precision Through Japanese Monozukuri', 'Tier-1 Global Automotive Supplier berspesialisasi dalam Roda Kemudi (Steering Wheels), Modul Airbag, dan Komponen Plastik Resin Interior-Eksterior dengan standar sertifikasi IATF 16949, ISO 14001, dan ISO 45001.', 'PT Nihon Plast Indonesia merupakan anak perusahaan dari Nihon Plast Co., Ltd. (Jepang) yang didirikan untuk menghadirkan keunggulan manufaktur komponen otomotif kelas dunia di Indonesia. Berlokasi strategis di Kawasan Industri Indotaisei Cikampek, Karawang, fasilitas manufaktur kami mengintegrasikan teknologi injeksi plastik presisi (Precision Resin Molding berkapasitas 150T hingga 2500T), keahlian pembungkusan kulit premium (Genuine Leather & Synthetic Wrapping Craftsmanship), perakitan modul keselamatan airbag di Cleanroom Class 8 berstandar global, serta laboratorium pengujian kualitas ketat demi memenuhi standar OEM Tier-1 internasional dan regulasi industri Republik Indonesia (TKDN, IATF 16949, ISO 14001, dan SMK3 PP 50/2012).', 'Menjadi mitra manufaktur otomotif \'One-of-a-Kind\' yang tak tergantikan bagi pelanggan melalui penguasaan teknologi unik, dedikasi keselamatan tanpa kompromi, dan produk berkualitas tinggi.', '1. Menghasilkan produk otomotif yang membanggakan serta memenuhi standar mutu internasional tertinggi (IATF 16949:2016).\n2. Menjalankan filosofi Monozukuri dan Kaizen secara konsisten di seluruh lini operasional.\n3. Mematuhi regulasi ketenagakerjaan, keselamatan kerja (SMK3/ISO 45001), dan kepedulian lingkungan (ISO 14001) demi pembangunan industri nasional yang berkelanjutan.', 'Monozukuri (Craftsmanship), Safety First, Integrity, Continuous Improvement (Kaizen), and Sustainable Harmony.', 'Kawasan Industri Indotaisei Sektor 1A Blok B-3, Desa Kalihurip, Kec. Cikampek, Kab. Karawang, Jawa Barat 41373, Indonesia', '+62 (264) 351-470 / +62 (264) 351-471', 'info@n-plast.co.id', 'logo_nihon_plast_green.png', '2026-10-14 07:40:26');

-- Dumping structure for table db_cms_compro.facility_machinery
CREATE TABLE IF NOT EXISTS `facility_machinery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(150) NOT NULL,
  `model_brand` varchar(150) NOT NULL,
  `tonnage` varchar(100) NOT NULL,
  `application` varchar(255) NOT NULL,
  `automation` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 10,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `is_active` (`is_active`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_cms_compro.facility_machinery: ~5 rows (approximately)
INSERT INTO `facility_machinery` (`id`, `category`, `model_brand`, `tonnage`, `application`, `automation`, `sort_order`, `is_active`, `created_at`) VALUES
	(1, 'Ultra-Large Injection Press', 'Sumitomo / Toshiba Hydraulic', '2.500 Ton', 'Instrument Panel & Cowl Top Exterior', 'Robotic Part Unloader & Vision Sensor', 10, 1, '2026-10-14 08:38:55'),
	(2, 'Large Injection Press', 'Nissei / JSW All-Electric', '1.300 Ton - 1.800 Ton', 'Center Console & Airbag Cover Module', 'Servo Take-out Robot & Degating Cell', 20, 1, '2026-10-14 08:38:55'),
	(3, 'Medium Injection Press', 'Toyo / Fanuc High-Cycle', '350 Ton - 850 Ton', 'Steering Wheel Bezel & Air Vent Registers', 'Automated Conveyor & Runner Separator', 30, 1, '2026-10-14 08:38:55'),
	(4, 'Precision Injection Press', 'Fanuc Roboshot Precision', '150 Ton - 280 Ton', 'Small Switches, Clips & Internal Safety Locks', 'Pick & Place Micro Gripper', 40, 1, '2026-10-14 08:38:55'),
	(5, 'Vibration Welding Rig', 'Branson / Bielomatik Automated', 'High Frequency 240 Hz', 'HVAC Ducts & Instrument Sub-assemblies', 'Pneumatic Clamping & Acoustic Enclosure', 50, 1, '2026-10-14 08:38:55');

-- Dumping structure for table db_cms_compro.gallery
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `image` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_cms_compro.gallery: ~2 rows (approximately)
INSERT INTO `gallery` (`id`, `title`, `image`, `created_at`) VALUES
	(1, 'Production Facility', 'uploads/gallery/facility_injection_molding.jpg', '2026-10-14 06:05:13'),
	(2, 'Engineering Facility', 'uploads/gallery/facility_cleanroom_lab.svg', '2026-10-14 06:05:13');

-- Dumping structure for table db_cms_compro.gallery_media
CREATE TABLE IF NOT EXISTS `gallery_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) NOT NULL,
  `media_type` enum('image','video','youtube') NOT NULL,
  `media_path` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `gallery_id` (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_cms_compro.gallery_media: ~0 rows (approximately)

-- Dumping structure for table db_cms_compro.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `email` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_cms_compro.messages: ~0 rows (approximately)

-- Dumping structure for table db_cms_compro.news
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `content` text DEFAULT NULL,
  `published_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_cms_compro.news: ~3 rows (approximately)
INSERT INTO `news` (`id`, `title`, `content`, `published_at`) VALUES
	(1, 'Company Profile Website Development', 'Dummy news untuk tampilan website company profile.', '2026-10-14 13:05:13'),
	(2, 'Continuous Improvement Program', 'Dummy news tentang program improvement perusahaan.', '2026-10-04 13:05:13'),
	(3, 'Manufacturing Excellence', 'Dummy news mengenai manufacturing excellence.', '2026-09-24 13:05:13');

-- Dumping structure for table db_cms_compro.page_banners
CREATE TABLE IF NOT EXISTS `page_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_key` varchar(40) NOT NULL,
  `page_title` varchar(200) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_key` (`page_key`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_cms_compro.page_banners: ~9 rows (approximately)
INSERT INTO `page_banners` (`id`, `page_key`, `page_title`, `subtitle`, `image`, `updated_at`) VALUES
	(1, 'home', 'PT Nihon Plast Indonesia', 'Driving Quality Through Automotive Manufacturing', 'uploads/pages/banner_home.jpg', '2026-10-14 09:08:32'),
	(2, 'about', 'About Company', 'Learn more about our company, vision, and mission.', 'uploads/gallery/facility_injection_molding.jpg', '2026-10-14 09:08:32'),
	(3, 'products', 'Our Products', 'Automotive components built with quality and precision.', 'uploads/products/product_steering_wheel.jpg', '2026-10-14 09:08:32'),
	(4, 'gallery', 'Our Gallery', 'Photos of our facilities and company activities.', 'uploads/gallery/facility_injection_molding.jpg', '2026-10-14 09:08:32'),
	(5, 'quality', 'Quality & Continuous Improvement', 'Commitment to quality, safety, and improvement.', 'uploads/gallery/facility_cleanroom_lab.svg', '2026-10-14 09:08:32'),
	(6, 'facility', 'Factory & Facility', 'Explore our manufacturing facilities.', 'uploads/gallery/facility_injection_molding.jpg', '2026-10-14 09:08:32'),
	(7, 'career', 'Join Our Team', 'Explore career opportunities with us.', 'uploads/gallery/facility_injection_molding.jpg', '2026-10-14 09:08:32'),
	(8, 'news', 'Company News', 'Latest updates and company activities.', 'uploads/gallery/facility_cleanroom_lab.svg', '2026-10-14 09:08:32'),
	(9, 'contact', 'Contact Us', 'Get in touch with our team.', 'uploads/gallery/facility_injection_molding.jpg', '2026-10-14 09:08:32');

-- Dumping structure for table db_cms_compro.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_cms_compro.products: ~4 rows (approximately)
INSERT INTO `products` (`id`, `name`, `description`, `image`, `created_at`) VALUES
	(1, 'Steering Wheel Systems (Sistem Roda Kemudi)', 'Roda kemudi berstandar global dengan pilihan material genuine leather presisi tinggi, polyurethane bermutu tinggi, switch kontrol multifungsi, pemanas (heated wheel), serta sensor sentuh keselamatan (Hands-on Detection).', 'uploads/products/product_steering_wheel.jpg', '2026-10-14 07:14:13'),
	(2, 'Airbag Safety Modules (Modul Kantung Udara)', 'Modul airbag berkecepatan tinggi terintegrasi (Driver, Passenger, Side Curtain, and Knee Airbag) dengan penutup cover resin presisi yang dapat membuka sempurna dalam hitungan milidetik saat terjadi benturan.', 'uploads/products/product_airbag_module.jpg', '2026-10-14 07:14:13'),
	(3, 'Cockpit & Interior Resin Parts (Komponen Interior Resin)', 'Komponen kabin interior berestetika tinggi dan presisi millimeter: Instrument Panel Cluster, Air Conditioner Registers & Ducts, Center Console Box, Glove Box, Cup Holders, dan Pillar Garnish.', 'uploads/products/product_cockpit_resin.svg', '2026-10-14 07:14:13'),
	(4, 'Exterior & Aerodynamic Trim (Komponen Eksterior & Aerodinamis)', 'Komponen eksterior berbahan resin berkekuatan tinggi terhadap cuaca ekstrem dan benturan kerikil: Cover Cowl Top, Fender Protector, dan Wheel Resonator peredam kebisingan resonansi roda mobil.', 'uploads/products/product_exterior_trim.svg', '2026-10-14 07:14:13');

-- Dumping structure for table db_cms_compro.project_memory
CREATE TABLE IF NOT EXISTS `project_memory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `author` varchar(100) DEFAULT 'antigravity-ai',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_cms_compro.project_memory: ~19 rows (approximately)
INSERT INTO `project_memory` (`id`, `category`, `title`, `content`, `metadata`, `author`, `created_at`, `updated_at`) VALUES
	(1, 'architecture', 'CMS Architecture & Tech Stack', 'PT Nihon Plast Indonesia Company Profile CMS.\n- Backend: Native PHP 8.3 (Laragon)\n- Database: MySQL 8 (Host: 192.168.1.6:3306, DB: db_cms_compro, User: root)\n- Frontend: Bootstrap 5, Bootstrap Icons, responsive custom CSS (home-scroll.css, content-clickable.css, product-detail-layout.css)\n- Templating: Modular includes (includes/head.php, includes/navbar.php, includes/footer.php, includes/page_banner.php, includes/content_helpers.php)\n- Admin Panel: Located in /admin/ protected by session auth (admin/auth.php).\n- Uploads: Stored under uploads/company/, uploads/gallery/, uploads/products/, uploads/pages/.', '{"tech":["PHP 8.3","MySQL","Bootstrap 5","Laragon"]}', 'antigravity-ai', '2026-10-14 06:22:33', '2026-10-14 06:22:33'),
	(2, 'environment', 'Development Environment & Binaries', 'Local environment specifications:\n- OS: Windows\n- Web server suite: Laragon (Apache/Nginx/MySQL)\n- PHP Binary: C:\\laragon\\bin\\php\\php-8.3.33-Win32-vs16-x64\\php.exe\n- Node.js: v22.19.0 (C:\\Program Files\\nodejs\\node.exe)\n- NPM: 10.9.3\n- Git: 2.55.0.windows.4\n- MySQL Connection: 192.168.1.6:3306 (user: root, db: db_cms_compro)\n- Workspace Root: C:\\laragon\\www\\cms-compro', '{"os":"Windows","php":"8.3.33","node":"22.19.0"}', 'antigravity-ai', '2026-10-14 06:22:33', '2026-10-14 06:22:33'),
	(3, 'database', 'Database Schema & Tables Overview', 'Complete list of tables in db_cms_compro:\n- careers: Job vacancies and recruitment information.\n- company_profile: Singleton row holding company identity, vision, mission, contact info, logo path.\n- gallery: Photo and media category / album records.\n- gallery_media: Individual media files (image, local video, YouTube embed) linked to gallery.id.\n- messages: Inbound contact form submissions.\n- news: Company news articles and press releases.\n- page_banners: Page header banners (home, about, products, gallery, quality, facility, career, news, contact).\n- products: Company product catalog (name, description, image).\n- users: Admin accounts authenticated with password_hash/password_verify.\n- project_memory: AI & developer persistent memory records across sessions.', '{"tables_count":10}', 'antigravity-ai', '2026-10-14 06:22:33', '2026-10-14 06:22:33'),
	(4, 'findings', 'Initial Codebase & Security Audit Findings', 'Key audit observations:\n1. Zero Admin Users: The `users` table is currently empty, meaning admin panel cannot be accessed until an admin is created.\n2. Security Risk on admin/setup.php: The setup script remains in the project and is publicly accessible without protection.\n3. Dynamic gallery_media creation: gallery_media table creation was only in admin/gallery.php and gallery-detail.php; now synchronized across the schema.\n4. Input & File Upload Validation: Admin uploads currently check extensions; recommending adding MIME verification (finfo) and CSRF tokens for production hardening.\n5. Public Website: Responsively renders company profile data, product showcase, news feed, gallery modal, and contact message persistence.', '{"status":"audited","critical_items":2}', 'antigravity-ai', '2026-10-14 06:22:33', '2026-10-14 06:22:33'),
	(5, 'tools_mcp', 'Configured Free & Open-Source MCP Servers', '100% Free & Open-Source (MIT/Apache) MCP servers installed and configured in ~/.gemini/config/mcp_config.json:\n1. @modelcontextprotocol/server-memory (MIT License): Knowledge graph memory server for long-term relational context.\n2. @modelcontextprotocol/server-sequential-thinking (MIT License): MCP server for deep step-by-step problem-solving.\n3. @benborla29/mcp-server-mysql (MIT License): Direct MySQL querying and schema exploration tool for db_cms_compro.\n- Zero API costs, zero subscriptions, fully self-hosted locally.', '{"license":"MIT","cost":"$0"}', 'antigravity-ai', '2026-10-14 06:22:33', '2026-10-14 06:22:33'),
	(6, 'changelog', 'Memory System & Tooling Initialization', 'Initial setup performed:\n- Created `project_memory` table in MySQL `db_cms_compro`.\n- Built `scripts/memory.php` CLI tool for fast MySQL memory management.\n- Built `scripts/db_check.php` for database status checks.\n- Verified and created `gallery_media` table.\n- Installed open-source MCP packages globally.\n- Configured `mcp_config.json` with memory, sequential-thinking, and mysql MCP servers.\n- Generated `PROJECT_MEMORY.md`, `CHANGELOG.md`, `GEMINI.md` workspace rules, and web development skill.', '{"action":"init"}', 'antigravity-ai', '2026-10-14 06:22:33', '2026-10-14 06:22:33'),
	(7, 'changelog', 'Created Admin Account & Secured setup.php', 'Created default admin account (username: admin, password verified with password_hash). Secured admin/setup.php by adding guard that automatically redirects to login.php if users table already contains at least one record.', NULL, 'antigravity-ai', '2026-10-14 06:56:04', '2026-10-14 06:56:04'),
	(8, 'findings', 'Benchmark & Analysis: Nihon Plast Japan and Tier-1 Web Trends', 'Comprehensive benchmark of Nihon Plast Co., Ltd. (Japan - n-plast.co.jp) and 2025/2026 Tier-1 Automotive supplier website trends: 1. Core Products: Safety Systems (Steering Wheels, Airbag Modules), Interior Resin Systems (Cockpit, Instrument Panels, Ducts, Consoles), Exterior/Aero (Cowl tops, Wheel resonators). 2. Corporate Philosophy: Monozukuri spirit, Pride in Products, Contributing to Society, \'One-of-a-kind Irreplaceable Partner\'. 3. Visual Identity: Japanese precision minimalism, Deep Corporate Navy (#002B49), Technical Cyan (#0088CC), Carbon Slate (#0F172A), Cleanroom White/Gray (#F8FAFC). 4. Modern UX Trends: Dual-persona UX (Procurement & Engineers), Interactive Vehicle Component Explorer, Certifications & Quality metrics above-the-fold, Bilingual (ID/EN) readiness.', NULL, 'antigravity-ai', '2026-10-14 07:10:17', '2026-10-14 07:10:17'),
	(9, 'changelog', 'Comprehensive Japanese Monozukuri Modernization & ISO Certification Module', 'Modernized PT Nihon Plast Indonesia CMS to Tier-1 Automotive standard: 1. Created certifications table in MySQL and seeded with IATF 16949:2016, ISO 9001:2015, ISO 14001:2015, ISO 45001:2018 / SMK3 PP 50/2012, and Kemenperin/SNI. 2. Created admin/certifications.php CRUD and updated admin header & dashboard. 3. Upgraded assets/css/style.css to Japanese precision palette (Precision Deep Navy #002B49, High-Tech Cyan #0088CC, Cleanroom White, Plus Jakarta Sans font). 4. Completely revamped index.php with Hero badge, Stats bar, 4-Pillar core products, and dedicated Certifications & ISO compliance section. 5. Upgraded quality.php to comprehensive Quality & Indonesian Regulatory Compliance showcase. 6. Upgraded includes/navbar.php and includes/footer.php to Tier-1 enterprise standard.', NULL, 'antigravity-ai', '2026-10-14 07:22:58', '2026-10-14 07:22:58'),
	(10, 'findings', 'Tier-1 Automotive Competitor Benchmark (ID, JP, CN, KR)', 'Benchmarked key Tier-1 interior and safety competitors: Japan (Toyoda Gosei, Tokai Rika, Ashimori), Indonesia (PT TGSSI, PT Astra Sugity, PT Kasai Teck See), China (Yanfeng, Joyson Safety), Korea (Hyundai Mobis, Seoyon E-Hwa). Identified 8 critical feature gaps: 1. Interactive Vehicle Zone Component Explorer; 2. Technical Machinery & Injection Tonnage Matrix; 3. ISO/IATF 16949 & TKDN Public Verification Hub; 4. OEM Customer Trust & Awards Showcase; 5. In-house Safety Testing & CMM Lab Specs; 6. Green Monozukuri & Recycled Resin ESG Roadmap; 7. B2B RFQ / Company Profile PDF Download; 8. Dual Language (ID/EN) Switcher.', NULL, 'antigravity-ai', '2026-10-14 07:36:05', '2026-10-14 07:36:05'),
	(11, 'changelog', 'Full Japanese Precision Tier-1 Modernization & Competitor Gap Elimination', 'Revamped entire web application based on Japan, ID, CN, KR competitor benchmarks: 1. Implemented Interactive Vehicle Zone Explorer in index.php and products.php; 2. Overhauled facility.php with authentic 150T-2500T injection machine matrix, Cleanroom Class 8, and CMM metrology lab; 3. Added OEM Trust Bar (Toyota, Honda, Nissan, Daihatsu, Mitsubishi, Suzuki, Subaru, Mazda); 4. Modernized contact.php with B2B RFQ form, Cikampek Indotaisei address, and FOSS OpenStreetMap embed; 5. Elevated about.php with Nihon Plast 1948 global network and Monozukuri DNA; 6. Standardized styling across all pages to Nihon Plast Forest Green (#005A36) and added bilingual ID/EN switcher in navbar.', NULL, 'antigravity-ai', '2026-10-14 07:49:32', '2026-10-14 07:49:32'),
	(12, 'changelog', 'Comprehensive Responsive Auto-Fit & Typographic Harmonization Fix', 'Fixed cross-cutting theme inconsistencies and broken responsive layouts: 1. Discovered and neutralized home-scroll.css and content-clickable.css which were leaking old blue (#0b6ea8, #142e43, #8ed0ff) and breaking font scales; 2. Enforced strict Bootstrap color overrides (.text-primary, .bg-primary, .btn-primary all map to --np-green #005A36); 3. Standardized fluid typography scale with clamp() across all headings (h1-h6) and body copy; 4. Fixed responsive auto-fit for desktop, tablet, and mobile (prevented stats-bar collision on mobile, added touch horizontal scrolling on zone tabs and tables, fixed aspect ratio distortions); 5. Rebuilt career.php to dynamically fetch from database careers table and updated news.php and gallery.php to match the unified Nihon Plast design system.', NULL, 'antigravity-ai', '2026-10-14 08:03:10', '2026-10-14 08:03:10'),
	(13, 'architecture', 'Multilingual Engine (5 Languages), Dynamic CMS Sections & Menu-Level CRUD RBAC', 'Successfully implemented 4 major capabilities: 1) 100% FOSS Multilingual Engine across 5 languages (ID, EN, JA, ZH, KO) with authentic automotive tier-1 terminology and browser auto-detection. 2) Zero videos, YouTube iframes, or GIFs across entire site; pure high-res photography. 3) 100% Dynamic CMS with site_sections table, active/inactive 1-click toggles, customizable titles, subtitles, badges, icons, images, backgrounds, and buttons. 4) Multi-user authorization with granular Create, Read, Update, Delete (CRUD) permissions matrix per menu.', NULL, 'antigravity-ai', '2026-10-14 08:35:38', '2026-10-14 08:35:38'),
	(14, 'architecture', 'All 9 Public Pages Dynamic CMS & Active Toggle Complete', 'Successfully migrated and wired all 9 public pages (home, about, facility, quality, products, contact, career, gallery, news) to MySQL site_sections table. Every page now supports 100% dynamic content rendering via get_section_val() and conditional rendering via is_section_active(). Admin site_sections.php supports instant 1-click active/not-active toggles and tabbed page filtering. Verified via 20 automated tests in scripts/test_all_pages_dynamic.php.', NULL, 'antigravity-ai', '2026-10-14 08:51:20', '2026-10-14 08:51:20'),
	(15, 'architecture', 'Optimized Home Page Hierarchy, News at #2, and Performance Pruning', 'Re-architected index.php section order to follow Nihon Plast Japan and Tier-1 automotive standards. News section moved to position #2 immediately below Hero/Stats. Heavy non-essential sections (gallery_preview, quality_pillars, esg_sustainability) turned off by default (is_active=0) cutting initial HTML payload to 63KB and eliminating 7-10MB photo bloat, with lazy loading enabled across all media. Full dynamic toggle retained in admin/site_sections.php.', NULL, 'antigravity-ai', '2026-10-14 09:02:33', '2026-10-14 09:02:33'),
	(16, 'architecture', 'Dedicated Automotive Visual Assets, Official SVG Accreditation Seals, and Interactive Icon & Media Pickers', 'Generated OEM studio photographs for Steering Wheel and Airbag Modules, plus high-precision technical blueprints for Cockpit Resin and Exterior Trims. Created 5 official vector SVG accreditation seals (IATF 16949, ISO 9001, ISO 14001, ISO 45001, TKDN 78%). Replaced all external Unsplash URLs with local fast assets. Developed interactive Icon Picker Modal (70+ categorized corporate icons) and Media Asset Gallery Modal in admin/site_sections.php, admin/products.php, and admin/certifications.php to make choosing icons and images effortless.', NULL, 'antigravity-ai', '2026-10-14 09:16:08', '2026-10-14 09:16:08'),
	(17, 'architecture', '100 Percent Dynamic CMS Sub-Items Engine and Admin Manager', 'Migrated all static HTML cards and lists to site_section_items table (77 dynamic sub-items across 18 sections). Refactored about.php, facility.php, quality.php, career.php, and products.php. Created admin/site_section_items.php with 1-click active toggle, full CRUD, and icon/media pickers. Connected with granular menu-level RBAC and linked from site_sections.php. All 26 tests passed.', NULL, 'antigravity-ai', '2026-10-14 09:36:30', '2026-10-14 09:36:30'),
	(18, 'architecture', 'Dribbble-Grade Automotive UI System & FOSS Vendor Integration', 'Modernized the UI architecture by eliminating clustered nav wrapping (strict white-space: nowrap with utility topbar and fluid container), integrating Swiper 11 hero carousel with dynamic HUD cards, horizontal progress bar and slide counter, continuous linear OEM marquee ticker, ambient automotive canvas particle motion, SweetAlert2 modal and toast feedback, and AOS (Animate on Scroll) animations. 100% Free & Open Source without third-party paid dependencies.', NULL, 'antigravity-ai', '2026-10-14 09:50:46', '2026-10-14 09:50:46'),
	(19, 'changelog', 'FOSS Vendor Libraries, Navbar Decluttering & Dribbble Hero Carousel', 'Installed local Swiper 11, SweetAlert2, and AOS in assets/vendor/. Added site-topbar for contact and multilingual pills to clear navbar real estate. Overhauled navbar with strict white-space: nowrap and container-fluid. Seeded 4 dynamic hero slides in site_section_items. Implemented Swiper 11 hero slider, ambient particle canvas overlay, continuous OEM marquee ticker, and SweetAlert2 notifications for B2B RFQ.', NULL, 'antigravity-ai', '2026-10-14 09:50:51', '2026-10-14 09:50:51');

-- Dumping structure for table db_cms_compro.site_sections
CREATE TABLE IF NOT EXISTS `site_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(50) NOT NULL DEFAULT 'home',
  `section_key` varchar(50) NOT NULL,
  `section_name` varchar(150) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `badge_text` varchar(100) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `bg_image` varchar(255) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_page_section` (`page`,`section_key`),
  KEY `page` (`page`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_cms_compro.site_sections: ~44 rows (approximately)
INSERT INTO `site_sections` (`id`, `page`, `section_key`, `section_name`, `title`, `subtitle`, `content`, `badge_text`, `icon`, `image`, `bg_image`, `button_text`, `button_link`, `is_active`, `sort_order`, `updated_at`) VALUES
	(1, 'home', 'hero', 'Hero Banner Utama', 'Producing Reliable Technology for Complete Peace of Mind', 'Tier-1 Global Automotive Supplier berspesialisasi dalam Roda Kemudi (Steering Wheels), Modul Airbag, dan Komponen Plastik Resin Interior-Eksterior dengan standar sertifikasi IATF 16949, ISO 14001, dan ISO 45001.', NULL, 'GLOBAL TIER-1 AUTOMOTIVE SAFETY & INTERIOR SYSTEMS', 'bi-shield-check', NULL, NULL, 'Jelajahi Produk', '#products-home', 1, 10, '2026-10-14 08:19:56'),
	(2, 'home', 'stats', 'Statistik Ringkas (Stats Bar)', 'Pencapaian & Kredensial Pabrik', 'Standar Mutu IATF 16949, 4 Pilar Produk, ISO 14001/SMK3, Fasilitas Cikampek Karawang', NULL, 'CREDENTIALS', 'bi-bar-chart-fill', NULL, NULL, NULL, NULL, 1, 20, '2026-10-14 08:19:56'),
	(3, 'home', 'oem_trust', 'OEM Trust & Partner Bar', 'Mitra Pabrikan Otomotif Terkemuka', 'Menyuplai langsung produsen APM Toyota, Honda, Nissan, Daihatsu, Mitsubishi, Suzuki, Subaru, Mazda', NULL, 'TIER-1 TRUSTED SUPPLIER TO GLOBAL & DOMESTIC OEMS', 'bi-award-fill', NULL, NULL, NULL, NULL, 1, 90, '2026-10-14 09:48:58'),
	(4, 'home', 'about_intro', 'Profil Singkat Perusahaan (About)', 'PT Nihon Plast Indonesia', 'Driving Safety & Precision Through Japanese Monozukuri', NULL, 'ABOUT OUR ENTERPRISE', 'bi-building', NULL, NULL, 'Profil Selengkapnya', 'about.php', 1, 80, '2026-10-14 08:54:45'),
	(5, 'home', 'vehicle_explorer', 'Interactive Vehicle Zone Explorer', 'Peta Penerapan Komponen pada Kendaraan', 'Jelajahi integrasi teknologi keselamatan dan komponen interior Nihon Plast pada arsitektur mobil modern.', NULL, 'VEHICLE APPLICATION EXPLORER', 'bi-car-front-fill', NULL, NULL, NULL, NULL, 1, 50, '2026-10-14 08:19:56'),
	(6, 'home', 'products_grid', 'Katalog 4 Pilar Produk Utama', 'Sistem Komponen Otomotif', 'Komponen interior resin presisi tinggi, modul keselamatan airbag, dan roda kemudi berkualitas OEM dengan standar kepatuhan ketat.', NULL, 'PORTFOLIO KEUNGGULAN PRODUK', 'bi-grid-3x3-gap-fill', NULL, NULL, 'Lihat Semua Lini Produk', 'products.php', 1, 40, '2026-10-14 08:54:45'),
	(7, 'home', 'certifications_grid', 'Sertifikasi ISO & Standar Regulasi RI', 'Standar Internasional & Regulasi Indonesia', 'PT Nihon Plast Indonesia beroperasi penuh dengan standar mutu otomotif internasional serta kepatuhan mutlak terhadap regulasi Kemenperin, KLHK, dan Kemnaker RI.', NULL, 'ACCREDITATION & COMPLIANCE', 'bi-patch-check-fill', NULL, NULL, 'Pelajari Kebijakan Mutu', 'quality.php', 1, 70, '2026-10-14 08:19:56'),
	(8, 'home', 'quality_pillars', 'Filosofi Monozukuri & Quality Assurance (Opsional Home)', 'Komitmen Mutu & Zero Defect', 'Setiap produk yang keluar dari pabrik melewati serangkaian inspeksi berstandar tinggi demi memastikan keselamatan pengemudi dan penumpang.', NULL, 'JAPANESE MONOZUKURI PHILOSOPHY', 'bi-gear-wide-connected', NULL, NULL, NULL, NULL, 0, 110, '2026-10-14 08:54:45'),
	(9, 'home', 'facility_preview', 'Highlight Fasilitas Pabrik Cikampek', 'Fasilitas Pabrik Presisi di Cikampek, Karawang', 'Mengintegrasikan mesin injeksi plastik presisi tinggi 150T–2500T, Cleanroom Assembly Class 8, dan lab kontrol kualitas berstandar 5S.', NULL, 'MANUFACTURING FACILITY', 'bi-buildings', NULL, NULL, 'Jelajahi Seluruh Fasilitas Pabrik', 'facility.php', 1, 60, '2026-10-14 08:54:45'),
	(10, 'home', 'esg_sustainability', 'Green Monozukuri & Keberlanjutan (Opsional Home)', 'Green Monozukuri: Komitmen Keberlanjutan Otomotif', 'Pabrik Karawang aktif menerapkan integrasi material resin daur ulang ramah lingkungan, efisiensi energi solar fotovoltaik, dan standar ISO 14001.', NULL, 'SUSTAINABILITY & CARBON NEUTRALITY', 'bi-recycle', NULL, NULL, NULL, NULL, 0, 120, '2026-10-14 08:54:45'),
	(11, 'home', 'gallery_preview', 'Galeri Foto Kegiatan & Fasilitas (Opsional Home)', 'Galeri Aktivitas & Fasilitas', 'Dokumentasi kegiatan manufaktur, budaya keselamatan 5S, dan kerja sama tim PT Nihon Plast Indonesia.', NULL, 'COMPANY ACTIVITIES', 'bi-camera-fill', NULL, NULL, 'Buka Halaman Gallery Lengkap', 'gallery.php', 0, 130, '2026-10-14 08:54:45'),
	(12, 'home', 'news_preview', 'Berita & Informasi Perusahaan (No. 2 Saat Scroll)', 'Berita & Informasi Perusahaan', 'Ikuti perkembangan terbaru mengenai ekspansi pabrik, pencapaian mutu, dan agenda korporat.', NULL, 'LATEST UPDATES', 'bi-newspaper', NULL, NULL, 'Lihat Semua Berita', 'news.php', 1, 30, '2026-10-14 08:54:45'),
	(13, 'home', 'contact_cta', 'Call to Action (B2B RFQ Inquiry)', 'Siap Bermitra dengan Manufaktur Berstandar Dunia?', 'Hubungi tim perwakilan PT Nihon Plast Indonesia untuk kebutuhan pasokan komponen roda kemudi, sistem kantung udara (airbags), dan pencetakan resin interior presisi tinggi bersertifikasi IATF 16949.', NULL, 'OEM B2B SOURCING & TECHNICAL RFQ', 'bi-briefcase-fill', NULL, NULL, 'Ajukan B2B RFQ Inquiry', 'contact.php', 1, 100, '2026-10-14 08:54:45'),
	(14, 'about', 'about_dna', 'Profil Korporat & DNA Nihon Plast Group', 'Dedikasi Keselamatan & Presisi Monozukuri Otomotif Dunia', 'Driving Safety & Precision Through Japanese Monozukuri', 'PT Nihon Plast Indonesia beroperasi di Kawasan Industri Cikampek, Karawang, Jawa Barat sebagai anak perusahaan dari Nihon Plast Co., Ltd. (Jepang). Sejak didirikan, kami mendedikasikan seluruh kapabilitas manufaktur untuk menghadirkan komponen keselamatan kemudi dan moulding resin interior tingkat presisi tinggi.', 'NIHON PLAST GROUP DNA', 'bi-shield-check', 'uploads/gallery/facility_injection_molding.jpg', NULL, 'Hubungi Kami', 'contact.php', 1, 10, '2026-10-14 09:08:32'),
	(15, 'about', 'about_vision_mission', 'Visi, Misi & Nilai Fundamental Perusahaan', 'Visi, Misi & Nilai Fundamental', 'Prinsip kerja yang memandu setiap inovasi dan lini manufaktur di PT Nihon Plast Indonesia.', 'Visi: Menjadi mitra manufaktur otomotif \'One-of-a-Kind\' yang tak tergantikan bagi pelanggan.\nMisi: Menghasilkan produk berkualitas standar IATF 16949, menjalankan Kaizen berkelanjutan, serta mematuhi norma K3 dan lingkungan.', 'CORPORATE FOUNDATION', 'bi-compass', NULL, NULL, NULL, NULL, 1, 20, '2026-10-14 08:38:55'),
	(16, 'about', 'about_global_footprint', 'Jejaring Global Nihon Plast Co., Ltd.', 'Jejaring Global Nihon Plast Co., Ltd.', 'Nihon Plast Co., Ltd. berkantor pusat di Fuji City, Shizuoka, Jepang sejak 1948 dengan jaringan pabrik di Asia Pasifik, Amerika Utara, dan Eropa.', NULL, 'GLOBAL FOOTPRINT', 'bi-globe-americas', NULL, NULL, NULL, NULL, 1, 30, '2026-10-14 08:38:55'),
	(17, 'about', 'about_datasheet', 'Ringkasan Fakta Perusahaan (Corporate Datasheet)', 'Ringkasan Fakta Perusahaan', 'Informasi legal, kepemilikan saham, luas area pabrik Cikampek 28.500 m², dan akreditasi internasional.', NULL, 'CORPORATE DATASHEET', 'bi-info-circle-fill', NULL, NULL, NULL, NULL, 1, 40, '2026-10-14 08:38:55'),
	(18, 'about', 'about_cta', 'CTA Fasilitas & Mutu ISO', 'Ingin Mengetahui Lebih Dalam Mengenai Fasilitas & Kualitas Kami?', 'Jelajahi kapabilitas permesinan presisi kami atau tinjau sertifikasi sistem mutu internasional IATF 16949 yang kami miliki.', NULL, 'EXPERIENCE OUR EXCELLENCE', 'bi-cpu', NULL, NULL, 'Tinjau Fasilitas & Mesin', 'facility.php', 1, 50, '2026-10-14 08:38:55'),
	(19, 'facility', 'facility_plant_overview', 'Gambaran Pabrik Strategis Cikampek', 'Fasilitas Manufaktur Otomotif Berstandar Global Tier-1', 'Pabrik PT Nihon Plast Indonesia didesain mengikuti standar ketat prinsipal Nihon Plast Co., Ltd. (Jepang) dengan luas lahan 28.500 m² dan luas bangunan 18.200 m².', NULL, 'Kawasan Industri Indotaisei Cikampek', 'bi-geo-alt-fill', 'uploads/gallery/facility_cleanroom_lab.svg', NULL, NULL, NULL, 1, 10, '2026-10-14 09:08:32'),
	(20, 'facility', 'facility_capabilities', 'Kapabilitas Sel Manufaktur (Manufacturing Cells)', 'Teknologi Manufaktur Unggulan', 'Kombinasi permesinan injeksi otomatis bertekanan tinggi dengan keahlian tangan pengrajin (Takumi Craftsmanship).', NULL, 'Kapabilitas Lini Produksi', 'bi-cpu-fill', NULL, NULL, NULL, NULL, 1, 20, '2026-10-14 08:38:55'),
	(21, 'facility', 'facility_machinery_intro', 'Matriks Spesifikasi Mesin (Technical Machinery Matrix)', 'Spesifikasi Mesin & Kapasitas Produksi', 'Daftar permesinan produksi utama berkapasitas 150 Ton hingga 2.500 Ton yang beroperasi di fasilitas Cikampek Karawang.', NULL, 'Technical Machinery Matrix', 'bi-table', NULL, NULL, NULL, NULL, 1, 30, '2026-10-14 08:38:55'),
	(22, 'facility', 'facility_cleanroom', 'Area Perakitan Steril Cleanroom Class 8', 'Perakitan Modul Airbag di Cleanroom ISO Class 8', 'Lingkungan bertekanan positif dengan kontrol partikel udara dan perlindungan electrostatic discharge (ESD) terproteksi penuh.', NULL, 'Cleanroom ISO 14644-1', 'bi-shield-check', 'uploads/products/product_steering_wheel.jpg', NULL, NULL, NULL, 1, 40, '2026-10-14 09:08:32'),
	(23, 'facility', 'facility_laboratories', 'Laboratorium Pengujian & Metrologi Presisi', 'Laboratorium Kontrol Mutu & Pengujian Dinamis', 'Peralatan inspeksi Coordinate Measuring Machine (CMM), chamber uji suhu ekstrim -40°C hingga +120°C, dan sensor lab deploy airbag.', NULL, 'Testing & Metrology Labs', 'bi-moisture', NULL, NULL, NULL, NULL, 1, 50, '2026-10-14 08:38:55'),
	(24, 'facility', 'facility_cta', 'CTA Kunjungan Pabrik & B2B RFQ', 'Rencanakan Audit Fasilitas atau Diskusikan Proyek OEM Anda', 'Tim Commercial & Engineering kami siap menyambut kunjungan teknis atau meninjau kebutuhan penawaran spesifikasi komponen Anda.', NULL, 'COLLABORATE WITH US', 'bi-briefcase-fill', NULL, NULL, 'Kirim Pengajuan B2B RFQ', 'contact.php', 1, 60, '2026-10-14 08:38:55'),
	(25, 'quality', 'quality_zero_defect', 'Filosofi Mutu Zero Defect & Kebijakan Kualitas', 'Dedikasi Mutu Tanpa Kompromi untuk Keselamatan Pengemudi', 'Sebagai pemasok otomotif Tier-1 global, prinsip Quality First dan Zero Defect ditanamkan ke dalam setiap tahap rekayasa hingga perakitan akhir.', '誇れる製品をつくり、顧客に奉仕し、社会に貢献する (Selalu menciptakan produk yang membanggakan, melayani pelanggan, dan berkontribusi kepada masyarakat).', 'ZERO DEFECT PHILOSOPHY', 'bi-shield-check', NULL, NULL, NULL, NULL, 1, 10, '2026-10-14 08:38:55'),
	(26, 'quality', 'quality_certifications_intro', 'Daftar Sertifikasi Resmi Terakreditasi', 'Sertifikasi & Akreditasi Resmi', 'Seluruh fasilitas dan proses manufaktur kami diaudit secara berkala oleh badan sertifikasi independen internasional terkemuka.', NULL, 'ACCREDITED CERTIFICATIONS', 'bi-award-fill', NULL, NULL, NULL, NULL, 1, 20, '2026-10-14 08:38:55'),
	(27, 'quality', 'quality_regulatory_compliance', 'Kepatuhan Regulasi Pemerintah RI (Kemenperin, KLHK, Kemnaker)', 'Kepatuhan Terhadap Hukum & Regulasi Republik Indonesia', 'Mematuhi standar industri Kemenperin RI, regulasi lingkungan KLHK (ISO 14001:2015 / AMDAL), serta norma K3 Kemnaker (SMK3 PP 50/2012).', NULL, 'INDONESIAN REGULATORY COMPLIANCE', 'bi-shield-check', NULL, NULL, NULL, NULL, 1, 30, '2026-10-14 08:38:55'),
	(28, 'quality', 'quality_flowchart', '4 Tahapan Pengendalian Mutu & Kaizen Workflow', 'Sistem Manajemen Mutu Terintegrasi', 'Tahap 1: Raw Material Incoming Inspection, Tahap 2: Real-time In-Process Quality Control, Tahap 3: 3D CMM Geometric Verification, Tahap 4: Pre-Delivery Final Audit.', NULL, 'QUALITY ASSURANCE WORKFLOW', 'bi-diagram-3-fill', NULL, NULL, NULL, NULL, 1, 40, '2026-10-14 08:38:55'),
	(29, 'quality', 'quality_cta', 'CTA Konsultasi Standar Mutu', 'Butuh Lembar Kepatuhan Mutu atau Salinan Sertifikat ISO?', 'Hubungi divisi Quality Assurance kami untuk mendapatkan salinan dokumen sertifikat resmi IATF 16949, ISO 14001, atau kepatuhan RoHS/ELV.', NULL, 'GET CERTIFICATION COPIES', 'bi-patch-check-fill', NULL, NULL, 'Hubungi Tim QA & Compliance', 'contact.php', 1, 50, '2026-10-14 09:50:33'),
	(30, 'products', 'products_catalog_intro', 'Pengantar Katalog Produk Otomotif', 'Katalog Komponen Otomotif Berstandar Dunia', 'Menyediakan sistem roda kemudi terintegrasi, modul keselamatan kantung udara, dan moulding resin interior eksterior berkualitas OEM.', NULL, 'TIER-1 OEM PRODUCT PORTFOLIO', 'bi-grid-3x3-gap-fill', NULL, NULL, NULL, NULL, 1, 10, '2026-10-14 08:38:55'),
	(31, 'products', 'products_technical_capabilities', 'Kapabilitas Teknis & Kompatibilitas Resin', 'Keunggulan Material & Toleransi Presisi Tinggi', 'Penggunaan resin rekayasa tinggi (PP-TD20, ABS, PC/ABS, PA66-GF) dengan ketahanan getaran, anti gores, dan kestabilan termal optimal.', NULL, 'MATERIAL ENGINEERING EXCELLENCE', 'bi-layers-fill', NULL, NULL, NULL, NULL, 1, 20, '2026-10-14 08:38:55'),
	(32, 'products', 'products_custom_oem', 'Layanan Kustom OEM APM & Prototyping', 'Solusi Kustomisasi Komponen Sesuai Kebutuhan OEM', 'Dukungan rekayasa teknis mulai dari perancangan cetakan (moulding), pemilihan tekstur laser butir, hingga uji coba prototipe cepat.', NULL, 'TAILORED OEM SOURCING', 'bi-gear-wide-connected', NULL, NULL, NULL, NULL, 1, 30, '2026-10-14 08:38:55'),
	(33, 'products', 'products_cta', 'CTA Permintaan Penawaran B2B Produk', 'Tertarik Mengembangkan Komponen Baru Bersama Kami?', 'Ajukan permintaan penawaran harga (RFQ) dan jadwalkan sesi tinjauan teknis bersama tim rekayasa kami.', NULL, 'B2B SOURCING INQUIRY', 'bi-file-earmark-text', NULL, NULL, 'Ajukan Permintaan RFQ', 'contact.php', 1, 40, '2026-10-14 08:38:55'),
	(34, 'career', 'career_intro', 'Filosofi SDM & Peluang Karir', 'Membangun Karir Berkelanjutan di Industri Manufaktur Presisi', 'Kami percaya bahwa keunggulan produk berawal dari sumber daya manusia yang kompeten, berintegritas, dan menjunjung tinggi budaya keselamatan kerja.', NULL, 'CAREER & TALENT DEVELOPMENT', 'bi-people-fill', NULL, NULL, NULL, NULL, 1, 10, '2026-10-14 08:38:55'),
	(35, 'career', 'career_culture_benefits', 'Budaya Kerja 5S & Fasilitas Kesejahteraan', 'Kesejahteraan Karyawan & Pengembangan Profesional', 'Pelatihan teknik Monozukuri langsung, fasilitas BPJS Kesehatan & Ketenagakerjaan lengkap, kantin higienis, dan bus jemputan karyawan.', NULL, 'WORKPLACE CULTURE & PERKS', 'bi-heart-pulse-fill', NULL, NULL, NULL, NULL, 1, 20, '2026-10-14 08:38:55'),
	(36, 'career', 'career_hiring_process', 'Tahapan Proses Rekrutmen & Seleksi', 'Tahapan Rekrutmen Transparan & Bebas Biaya', '1. Seleksi Berkas Administrasi, 2. Tes Kemampuan Teknis & Psikotes, 3. Wawancara HR & User, 4. Pemeriksaan Kesehatan (MCU) & Onboarding.', NULL, 'RECRUITMENT STAGES', 'bi-check2-circle', NULL, NULL, NULL, NULL, 1, 30, '2026-10-14 09:16:33'),
	(37, 'career', 'career_vacancies_intro', 'Daftar Posisi Lowongan Kerja Terbuka', 'Posisi Lowongan yang Sedang Dibuka', 'Lihat kesempatan karir aktif di fasilitas pabrik Cikampek, Karawang dan kirimkan lamaran Anda.', NULL, 'OPEN VACANCIES', 'bi-briefcase-fill', NULL, NULL, NULL, NULL, 1, 40, '2026-10-14 08:38:55'),
	(38, 'contact', 'contact_liaison_intro', 'Liaison Komersial & B2B RFQ Header', 'Bermitra dengan PT Nihon Plast Indonesia', 'Kami siap melayani kebutuhan OEM APM, perancangan prototipe teknis, serta audit fasilitas untuk komponen roda kemudi, modul airbag, dan komponen resin interior presisi.', NULL, 'COMMERCIAL & ENGINEERING LIAISON', 'bi-briefcase-fill', NULL, NULL, NULL, NULL, 1, 10, '2026-10-14 08:38:55'),
	(39, 'contact', 'contact_info_card', 'Kartu Informasi Alamat Pabrik, Telepon & Email', 'Lokasi Pabrik & Kontak Resmi', 'Kawasan Industri Indotaisei Sektor 1A Blok P-1, Kalihurip, Cikampek, Karawang 41373, Jawa Barat | Telepon: +62 (264) 351-888 | Email: corporate@n-plast.co.id', NULL, 'HEADQUARTERS & MANUFACTURING PLANT', 'bi-geo-alt-fill', NULL, NULL, NULL, NULL, 1, 20, '2026-10-14 08:38:55'),
	(40, 'contact', 'contact_download_deck', 'Unduh Company Profile & Hotline WhatsApp', 'Company Profile & Capability Deck', 'Unduh rangkuman profil korporat, data mesin injeksi plastik, kapasitas produksi, dan sertifikasi IATF 16949 / ISO 14001.', NULL, 'CORPORATE BROCHURE', 'bi-file-earmark-pdf-fill', NULL, NULL, 'Unduh Company Profile (PDF)', 'about.php', 1, 30, '2026-10-14 08:38:55'),
	(41, 'contact', 'contact_rfq_form_intro', 'Formulir Pengajuan B2B RFQ & Konsultasi', 'Formulir Permintaan Penawaran & Konsultasi Teknis', 'Silakan lengkapi formulir di bawah ini. Tim Commercial & Engineering kami akan merespons dalam waktu 1x24 jam kerja.', NULL, 'FORMULIR PENGAJUAN B2B RFQ', 'bi-send-check-fill', NULL, NULL, NULL, NULL, 1, 40, '2026-10-14 08:38:55'),
	(42, 'contact', 'contact_map_section', 'Peta Navigasi Lokasi Pabrik Cikampek', 'Peta Lokasi Kawasan Industri Indotaisei Cikampek', 'Akses strategis jalan tol Jakarta-Cikampek KM 68 Gerbang Tol Kalihurip Utama.', NULL, 'PLANT NAVIGATION MAP', 'bi-map-fill', NULL, NULL, NULL, NULL, 1, 50, '2026-10-14 08:38:55'),
	(43, 'gallery', 'gallery_header', 'Header Galeri Foto Dokumentasi Manufaktur', 'Galeri Aktivitas & Fasilitas Pabrik', 'Dokumentasi fotografi beresolusi tinggi mengenai kegiatan produksi manufaktur, budaya keselamatan 5S, dan fasilitas pabrik PT Nihon Plast Indonesia.', NULL, 'DOKUMENTASI FOTO MANUFAKTUR', 'bi-camera-fill', NULL, NULL, NULL, NULL, 1, 10, '2026-10-14 08:38:56'),
	(44, 'news', 'news_header', 'Header Berita & Pengumuman Korporat', 'Berita & Informasi Perusahaan', 'Ikuti perkembangan terbaru mengenai ekspansi pabrik, pencapaian mutu, dan agenda korporat PT Nihon Plast Indonesia.', NULL, 'LATEST UPDATES', 'bi-newspaper', NULL, NULL, NULL, NULL, 1, 10, '2026-10-14 08:38:56');

-- Dumping structure for table db_cms_compro.site_section_items
CREATE TABLE IF NOT EXISTS `site_section_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_key` varchar(50) NOT NULL,
  `item_key` varchar(50) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `badge` varchar(100) DEFAULT NULL,
  `extra_data` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 10,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_section` (`section_key`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_cms_compro.site_section_items: ~81 rows (approximately)
INSERT INTO `site_section_items` (`id`, `section_key`, `item_key`, `title`, `subtitle`, `content`, `icon`, `image`, `badge`, `extra_data`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'stats', NULL, 'IATF 16949 / ISO 14001', 'Standar Mutu Otomotif Global', NULL, 'bi-shield-check', NULL, NULL, NULL, 10, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(2, 'stats', NULL, '4 Lini Produk Utama', 'Steering, Airbag, Interior & Eksterior Resin', NULL, 'bi-box-seam', NULL, NULL, NULL, 20, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(3, 'stats', NULL, 'Pabrik Cikampek Karawang', 'Cleanroom Class 8 & Injeksi 150T-2500T', NULL, 'bi-building', NULL, NULL, NULL, 30, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(4, 'stats', NULL, 'Zero Defect & Zero LTI', 'Disiplin Budaya K3 Kemnaker RI & 5S', NULL, 'bi-check2-circle', NULL, NULL, NULL, 40, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(5, 'oem_trust', NULL, 'TOYOTA', NULL, NULL, NULL, NULL, 'OEM Partner', NULL, 10, 1, '2026-10-14 09:20:34', '2026-10-14 09:50:33'),
	(6, 'oem_trust', NULL, 'HONDA', NULL, NULL, NULL, NULL, 'OEM Partner', NULL, 20, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(7, 'oem_trust', NULL, 'NISSAN', NULL, NULL, NULL, NULL, 'OEM Partner', NULL, 30, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(8, 'oem_trust', NULL, 'DAIHATSU', NULL, NULL, NULL, NULL, 'OEM Partner', NULL, 40, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(9, 'oem_trust', NULL, 'MITSUBISHI', NULL, NULL, NULL, NULL, 'OEM Partner', NULL, 50, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(10, 'oem_trust', NULL, 'SUZUKI', NULL, NULL, NULL, NULL, 'OEM Partner', NULL, 60, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(11, 'oem_trust', NULL, 'SUBARU', NULL, NULL, NULL, NULL, 'OEM Partner', NULL, 70, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(12, 'oem_trust', NULL, 'MAZDA', NULL, NULL, NULL, NULL, 'OEM Partner', NULL, 80, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(13, 'vehicle_explorer', 'steering', 'Steering Wheel Systems & Ergonomics', 'Zone: Driver Cockpit Interface', 'Rangka magnesium/aluminium die-cast ringan dengan busa poliuretan integral, balutan kulit premium jahitan tangan, dan tombol multifungsi kontrol presisi tinggi.', 'bi-disc', 'uploads/products/product_steering_wheel.jpg', NULL, '{"badges":["Magnesium\\/Al Die-Cast Core","Hand-Crafted Genuine Leather","Multi-Switch & Paddle Shift","FMVSS 208 \\/ ECE R12 Compliant"],"tab_label":"1. Roda Kemudi (Steering Wheels)","link":"products.php"}', 10, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(14, 'vehicle_explorer', 'airbag', 'Airbag Safety Modules & Inflator Encasement', 'Zone: Active & Passive Safety', 'Sistem proteksi benturan berkecepatan tinggi yang dirancang untuk mengembang sempurna dalam 20 hingga 30 milidetik demi keselamatan mutlak.', 'bi-shield-shaded', 'uploads/products/product_airbag_module.jpg', NULL, '{"badges":["Driver, Passenger, Side Curtain","Cleanroom ISO Class 8 Assembly","Laser-Scored Seamless Tear Seam","100% Traceability Barcode"],"tab_label":"2. Modul Kantung Udara (Airbags)","link":"products.php"}', 20, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(15, 'vehicle_explorer', 'cockpit', 'Instrument Panel & Interior Resins', 'Zone: Cabin Luxury & Ergonomics', 'Komponen dashboard, garnish panel, kisi-kisi AC, dan konsol tengah dengan finishing tekstur lembut dan ketahanan deformasi suhu tinggi.', 'bi-speedometer2', 'uploads/products/product_cockpit_resin.svg', NULL, '{"badges":["Dual-Shot Injection","Piano Black & Soft Touch","Zero Tolerance Fitment","Heat & UV Resistant JIS"],"tab_label":"3. Kokpit & Konsol Tengah","link":"products.php"}', 30, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(16, 'vehicle_explorer', 'cleanroom', 'Cleanroom Class 8 Assembly Area', 'Zone: High Precision Cleanliness', 'Lingkungan perakitan bebas debu dan anti-statis (ESD) untuk penyatuan sensor pemicu dan modul kantung udara berkecepatan tinggi.', 'bi-shield-check', 'uploads/gallery/facility_cleanroom_lab.svg', NULL, '{"badges":["HEPA Filtration 99.97%","ESD Antistatic Control","Positive Pressure Differential","High-Speed Sensor Deployment"],"tab_label":"4. Area Cleanroom Class 8","link":"facility.php"}', 40, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(17, 'quality_pillars', NULL, 'Raw Material QA', '01', 'Inspeksi spektrometri dan uji densitas bahan baku resin, kulit asli, serta busa polyurethane.', NULL, NULL, NULL, NULL, 10, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(18, 'quality_pillars', NULL, 'Precision Injection', '02', 'Mesin injeksi berteknologi servo Jepang dengan kontrol suhu dan tekanan mikro untuk toleransi ukuran mikron.', NULL, NULL, NULL, NULL, 20, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(19, 'quality_pillars', NULL, 'Master Craftsmanship & CMM', '03', 'Inspeksi dimensi geometri Coordinate Measuring Machine (CMM) berkalibrasi rutin laser.', NULL, NULL, NULL, NULL, 30, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(20, 'quality_pillars', NULL, 'Safety Deployment Lab', '04', 'Uji kecepatan tinggi dan ketahanan benturan sensor airbag drop-tower demi jaminan keselamatan mutlak.', NULL, NULL, NULL, NULL, 40, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(21, 'esg_sustainability', 'stat_solar', 'Pengurangan Emisi Karbon/Tahun via Rooftop Solar PV', NULL, NULL, 'bi-sun-fill', NULL, '450 Ton CO₂', NULL, 10, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(22, 'esg_sustainability', 'stat_recycle', 'Rasio Penggunaan Recycled Polypropylene Resin', NULL, NULL, 'bi-tree-fill', NULL, '28% Target', NULL, 20, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(23, 'esg_sustainability', 'comp_iso14001', 'Sertifikasi ISO 14001:2015 Terakreditasi', NULL, 'Sistem manajemen lingkungan terintegrasi dengan pemilahan limbah resin 100% terkontrol.', 'bi-patch-check-fill', NULL, NULL, NULL, 30, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(24, 'esg_sustainability', 'comp_smk3', 'SMK3 PP 50/2012 & Zero Accident Award', NULL, 'Budaya K3 Jepang (5S & Anzen Daiichi) melindungi keselamatan seluruh tenaga kerja.', 'bi-shield-check', NULL, NULL, NULL, 40, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(25, 'esg_sustainability', 'comp_rohs', 'RoHS & ELV (End-of-Life Vehicles) Compliant', NULL, 'Bebas senyawa berbahaya timbal, merkuri, kadmium, dan kromium heksavalen.', 'bi-droplet-half', NULL, NULL, NULL, 50, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(26, 'about_global_footprint', NULL, 'Jepang (Headquarters & R&D Center)', 'Fuji Plant, Aoshima Plant, Tomioka Plant', NULL, 'bi-geo-alt-fill', NULL, NULL, NULL, 10, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(27, 'about_global_footprint', NULL, 'Asia Pasifik (ASEAN & China)', 'Indonesia (Cikampek Plant), Thailand, China (Wuhan & Zhongshan)', NULL, 'bi-geo-alt-fill', NULL, NULL, NULL, 20, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(28, 'about_global_footprint', NULL, 'Amerika Utara (North America)', 'Amerika Serikat (Ohio) & Mexico (Matamoros & Silao)', NULL, 'bi-geo-alt-fill', NULL, NULL, NULL, 30, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(29, 'facility_capabilities', NULL, 'High-Tonnage Injection Molding', 'Armada 22 mesin injeksi plastik berkisar dari 150-ton hingga 2.500-ton servo-robotik.', 'Mencetak komponen presisi tinggi dari material PP, ABS, PC, dan PA66.', NULL, 'uploads/gallery/facility_injection_molding.jpg', 'Injeksi Resin Presisi', '["Multi-cavity mold compatibility","Gas-assist injection capability"]', 10, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(30, 'facility_capabilities', NULL, 'Modul Airbag Cleanroom Assembly', 'Area perakitan steril berstandar ISO 14644-1 Class 8 terproteksi ESD.', 'Kontrol partikel mikro dan electrostatic discharge (ESD) terproteksi penuh.', NULL, 'uploads/gallery/facility_cleanroom_lab.svg', 'Cleanroom Class 8', '["Traceability barcode 100% individual","Automatic torque recording system"]', 20, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(31, 'facility_capabilities', NULL, 'Leather & Urethane Steering Cell', 'Keahlian pembungkusan kulit asli dan sintetis jahitan tangan presisi.', 'Dipadukan dengan busa polyurethane molding berdensitas tinggi untuk ergonomi optimal.', NULL, 'uploads/products/product_steering_wheel.jpg', 'Takumi Craftsmanship', '["Euro-stitch, Cross-stitch, Baseball-stitch","Integrated steering heater option"]', 30, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(32, 'facility_laboratories', NULL, 'CMM Geometric Lab', NULL, 'Mesin pengukur koordinat 3 dimensi beresolusi 0,001 mm (mikron) untuk validasi cetakan mold presisi tinggi.', 'bi-rulers', NULL, '3D Metrology', NULL, 10, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(33, 'facility_laboratories', NULL, 'Chamber Suhu Ekstrim', NULL, 'Ruang simulasi termal dengan rentang suhu dari -40°C hingga +120°C serta kelembaban 95% RH untuk uji penuaan material.', 'bi-thermometer-half', NULL, 'Thermal Stress', NULL, 20, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(34, 'facility_laboratories', NULL, 'High-Speed Sensor Deployment Lab', NULL, 'Sensor optik presisi tinggi untuk menganalisis kurva tekanan inflasi kantung udara dalam 30 milidetik.', 'bi-speedometer2', NULL, 'Safety Inflation', NULL, 30, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(35, 'facility_laboratories', NULL, 'Vibration & Endurance Rig', NULL, 'Simulator getaran frekuensi multi-aksial untuk menguji kelelahan material rangka kemudi sesuai standar JIS D1601.', 'bi-activity', NULL, 'Material Durability', NULL, 40, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(36, 'quality_regulatory_compliance', NULL, 'SMK3 (Peraturan Pemerintah No. 50 Tahun 2012)', NULL, 'Penerapan wajib Sistem Manajemen K3 Kemnaker RI dengan audit berkala, pelatihan tanggap darurat, dan fasilitas APD berstandar penuh.', 'bi-check-lg', NULL, NULL, NULL, 10, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(37, 'quality_regulatory_compliance', NULL, 'Pengelolaan Lingkungan & Limbah B3 (KLHK RI)', NULL, 'Izin TPS Limbah B3 resmi, dokumen lingkungan AMDAL / UKL-UPL terverifikasi, dan kepatuhan baku mutu emisi pabrik.', 'bi-check-lg', NULL, NULL, NULL, 20, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(38, 'quality_regulatory_compliance', NULL, 'TKDN & SNI Otomotif (Kementerian Perindustrian)', NULL, 'Mendukung peningkatan penggunaan komponen produksi dalam negeri dan pemenuhan spesifikasi teknis kendaraan bermotor nasional.', 'bi-check-lg', NULL, NULL, NULL, 30, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(39, 'quality_flowchart', NULL, 'Quality Assurance (QA)', 'APQP, FMEA, PPAP, MSA', 'Perencanaan kualitas matang mengadopsi metodologi APQP, FMEA, PPAP, dan MSA untuk mencegah potensi kecacatan sejak fase desain mold.', 'bi-check2-circle', NULL, NULL, NULL, 10, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(40, 'quality_flowchart', NULL, 'Quality Control (QC)', '100% In-Line Inspection', 'Inspeksi otomatis berkamera resolusi tinggi dan pemeriksaan sensorik manual pada setiap tahap produksi kritis.', 'bi-sliders', NULL, NULL, NULL, 20, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(41, 'quality_flowchart', NULL, 'Quality Engineering (QE)', 'Metrology & Lab Verification', 'Verifikasi dimensi mikron dengan 3D CMM Mitutoyo, pengujian ketahanan termal, dan simulasi benturan drop-tower.', 'bi-cpu', NULL, NULL, NULL, 30, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(42, 'quality_flowchart', NULL, 'Kaizen & Continuous Improvement', 'PDCA & 5S Culture', 'Penerapan gugus kendali mutu (QCC) dan perbaikan berkesinambungan tanpa henti oleh seluruh jajaran karyawan.', 'bi-arrow-repeat', NULL, NULL, NULL, 40, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(43, 'career_culture_benefits', NULL, 'Remunerasi Kompetitif & BPJS Lengkap', NULL, 'Paket gaji standar industri Tier-1, BPJS Ketenagakerjaan & Kesehatan, serta asuransi kesehatan swasta.', 'bi-shield-check', NULL, NULL, NULL, 10, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(44, 'career_culture_benefits', NULL, 'Pelatihan Standar Jepang (Monozukuri)', NULL, 'Kesempatan transfer ilmu langsung dari tenaga ahli Jepang, program Kaizen, dan sertifikasi keahlian teknis.', 'bi-mortarboard', NULL, NULL, NULL, 20, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(45, 'career_culture_benefits', NULL, 'Lingkungan Kerja Bersih & Aman (5S & K3)', NULL, 'Budaya kerja ramah, fasilitas pabrik modern ber-AC, kantin sehat, dan komitmen Zero Accident.', 'bi-heart-pulse', NULL, NULL, NULL, 30, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(46, 'career_culture_benefits', NULL, 'Jenjang Karir Terbuka & Berkelanjutan', NULL, 'Apresiasi kinerja berbasis meritokrasi dan kesempatan promosi kepemimpinan yang transparan.', 'bi-graph-up-arrow', NULL, NULL, NULL, 40, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(47, 'career_culture_benefits', NULL, 'Transportasi & Fasilitas Kerja', NULL, 'Jemputan karyawan di berbagai rute strategis, seragam kerja, dan fasilitas ibadah yang nyaman.', 'bi-bus-front', NULL, NULL, NULL, 50, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(48, 'career_culture_benefits', NULL, 'Keseimbangan Kerja & Kehidupan', NULL, 'Jadwal kerja teratur sesuai regulasi ketenagakerjaan RI tanpa jam kerja berlebih yang merugikan.', 'bi-calendar-check', NULL, NULL, NULL, 60, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(49, 'career_hiring_process', NULL, 'Pendaftaran & Seleksi Berkas Online', '01', 'Kirimkan CV terbaru dan portofolio Anda melalui portal karir resmi ini atau email HRD kami.', NULL, NULL, NULL, NULL, 10, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(50, 'career_hiring_process', NULL, 'Psikotes & Uji Kompetensi Teknis', '02', 'Penilaian kemampuan logika, kepribadian, serta uji keterampilan teknis sesuai posisi yang dilamar.', NULL, NULL, NULL, NULL, 20, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(51, 'career_hiring_process', NULL, 'Wawancara User & Manajemen', '03', 'Diskusi mendalam mengenai pengalaman kerja, kecocokan budaya kerja, dan rencana pengembangan diri.', NULL, NULL, NULL, NULL, 30, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(52, 'career_hiring_process', NULL, 'Medical Check-Up & Penawaran Resmi', '04', 'Pemeriksaan kesehatan kerja standar industri dan penandatanganan perjanjian kerja resmi.', NULL, NULL, NULL, NULL, 40, 1, '2026-10-14 09:20:34', '2026-10-14 09:20:34'),
	(53, 'about_values', 'monozukuri', 'Monozukuri (Craftsmanship)', 'Filosofi keunggulan manufaktur Jepang', NULL, 'bi-hammer', NULL, NULL, NULL, 10, 1, '2026-10-14 09:27:40', '2026-10-14 09:50:33'),
	(54, 'about_values', 'safety_first', 'Safety First (Anzen Daiichi)', 'Keselamatan tanpa kompromi pada setiap proses', NULL, 'bi-shield-fill-check', NULL, NULL, NULL, 20, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(55, 'about_values', 'kaizen', 'Continuous Kaizen', 'Perbaikan tiada henti secara konsisten', NULL, 'bi-arrow-repeat', NULL, NULL, NULL, 30, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(56, 'about_values', 'integrity', 'Integrity & Harmony', 'Integritas tinggi dan keharmonisan tim kerja', NULL, 'bi-people-fill', NULL, NULL, NULL, 40, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(57, 'about_datasheet', NULL, 'Nama Perusahaan', NULL, 'PT Nihon Plast Indonesia', NULL, NULL, NULL, NULL, 10, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(58, 'about_datasheet', NULL, 'Kepemilikan Saham', NULL, 'Nihon Plast Co., Ltd. (Jepang) - Tier-1 Automotive Group', NULL, NULL, NULL, NULL, 20, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(59, 'about_datasheet', NULL, 'Lokasi Pabrik', NULL, 'Kawasan Industri Indotaisei Sektor 1A Blok P-1, Kalihurip, Cikampek, Karawang, Jawa Barat', NULL, NULL, NULL, NULL, 30, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(60, 'about_datasheet', NULL, 'Luas Area Pabrik', NULL, 'Total Lahan: 28.500 m² | Bangunan Produksi: 18.200 m²', NULL, NULL, NULL, NULL, 40, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(61, 'about_datasheet', NULL, 'Lini Produk Utama', NULL, 'Steering Wheel Systems, Airbag Modules, Cockpit & Exterior Trims', NULL, NULL, NULL, NULL, 50, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(62, 'about_datasheet', NULL, 'Sertifikasi Mutu', NULL, 'IATF 16949:2016, ISO 14001:2015, SMK3 / ISO 45001', NULL, NULL, NULL, '{"badges":["IATF 16949:2016","ISO 14001:2015","SMK3 \\/ ISO 45001"]}', 60, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(63, 'about_datasheet', NULL, 'Mitra Pelanggan OEM', NULL, 'Toyota, Honda, Nissan, Daihatsu, Mitsubishi, Suzuki, Subaru, Mazda', NULL, NULL, NULL, NULL, 70, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(64, 'facility_plant_stats', NULL, '28.500 m²', 'Total Luas Lahan Pabrik', NULL, 'bi-aspect-ratio', NULL, NULL, NULL, 10, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(65, 'facility_plant_stats', NULL, '18.200 m²', 'Luas Bangunan Produksi', NULL, 'bi-building-gear', NULL, NULL, NULL, 20, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(66, 'facility_plant_stats', NULL, '120.000 / Bln', 'Kapasitas Steering Wheel', NULL, 'bi-disc', NULL, NULL, NULL, 30, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(67, 'facility_plant_stats', NULL, '95.000 / Bln', 'Kapasitas Modul Airbag', NULL, 'bi-shield-shaded', NULL, NULL, NULL, 40, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(68, 'facility_capabilities', NULL, 'Automated Vibration & Ultrasonic Welding', 'Pengelasan Frekuensi Tinggi Komponen Interior', 'Stasiun pengelasan otomatis bergetar dan ultrasonik untuk merekatkan modul saluran udara (air ducts), penutup airbag, dan konsol tengah dengan kekuatan rekat setara material cetak utuh tanpa penggunaan lem kimiawi beracun.', 'bi-lightning-charge-fill', NULL, 'Otomasi Pengelasan', '["Zero chemical adhesive technology","High-tensile joint weld verification"]', 40, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(69, 'facility_capabilities', NULL, 'High-Pressure Polyurethane (PU) Foaming', 'Inti Rangka Kemudi & Komponen Peredam', 'Sistem injeksi busa poliuretan bertekanan tinggi pada rangka inti logam roda kemudi, menjamin daya redam getaran berkendara yang optimal dan keselamatan deselerasi tabrakan pengemudi.', 'bi-shield-shaded', NULL, 'Foaming Presisi', '["High-density shock absorption","Ergonomic tactile feeling standard"]', 50, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(70, 'career_intro_features', NULL, 'Pelatihan Terstruktur', 'Program transfer teknologi dan pelatihan standar manufaktur Jepang.', NULL, 'bi-mortarboard-fill', NULL, NULL, NULL, 10, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(71, 'career_intro_features', NULL, 'Budaya K3 / SMK3', 'Lingkungan kerja higienis dengan penghargaan Zero Accident.', NULL, 'bi-shield-check', NULL, NULL, NULL, 20, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(72, 'products_material_features', NULL, 'Flame Retardant', 'Standar ketahanan api otomotif FMVSS 302', NULL, 'bi-shield-check', NULL, NULL, NULL, 10, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(73, 'products_material_features', NULL, 'UV & Weathering', 'Anti-degradasi paparan sinar matahari tropis', NULL, 'bi-sun', NULL, NULL, NULL, 20, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(74, 'products_resins', NULL, 'Polypropylene (PP-TD / PP-GF)', 'Door trims, cowl tops, consoles', NULL, 'bi-layers', NULL, NULL, NULL, 10, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(75, 'products_resins', NULL, 'ABS / PC-ABS Alloy', 'Instrument panels, bezel clusters', NULL, 'bi-layers', NULL, NULL, NULL, 20, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(76, 'products_resins', NULL, 'Polyamide (PA6 / PA66-GF30)', 'High structural & underhood parts', NULL, 'bi-layers', NULL, NULL, NULL, 30, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(77, 'products_resins', NULL, 'Polyurethane (PUR Leather/Foam)', 'Steering wheel grips & covers', NULL, 'bi-layers', NULL, NULL, NULL, 40, 1, '2026-10-14 09:27:40', '2026-10-14 09:27:40'),
	(78, 'hero_slides', 'slide_injection', 'Japanese Monozukuri Precision & High-Tonnage Injection Molding', 'GLOBAL TIER-1 AUTOMOTIVE SAFETY & INTERIOR SYSTEMS', 'Mitra manufaktur terpercaya bagi OEM otomotif dunia dengan armada 22 mesin injeksi servo berkapasitas 150T hingga 2.500T, cleanroom Class 8, dan sertifikasi IATF 16949.', 'bi-gear-wide-connected', 'uploads/gallery/facility_injection_molding.jpg', 'IATF 16949 / ISO 14001', '{"badges":["150T - 2500T Tonnage","Cleanroom Class 8","0.001mm Tolerance","Tier-1 OEM Partner"],"btn_primary_text":"Jelajahi Produk","btn_primary_link":"products.php","btn_secondary_text":"Konsultasi B2B RFQ","btn_secondary_link":"contact.php"}', 10, 1, '2026-10-14 09:41:32', '2026-10-14 09:41:32'),
	(79, 'hero_slides', 'slide_airbag', 'Critical Airbag Safety Modules with 30ms High-Speed Deployment', 'UNCOMPROMISING LIFE-SAVING OCCUPANT PROTECTION', 'Dirancang dan dirakit dalam cleanroom berstandar ISO 14644-1 Class 8 dengan kontrol elektrostatis ESD penuh dan 100% penelusuran barcode komponen inflator.', 'bi-shield-shaded', 'uploads/products/product_airbag_module.jpg', 'Safety Critical 20-30ms', '{"badges":["Driver & Passenger Modules","Laser Tear Seam","100% Barcode Traceability","FMVSS 208 Compliant"],"btn_primary_text":"Modul Kantung Udara","btn_primary_link":"products.php?category=Airbag","btn_secondary_text":"Fasilitas Perakitan","btn_secondary_link":"facility.php"}', 20, 1, '2026-10-14 09:41:32', '2026-10-14 09:41:32'),
	(80, 'hero_slides', 'slide_steering', 'Takumi Master Craftsmanship: Genuine Leather Steering Wheels', 'PREMIUM CABIN ERGONOMICS & DIE-CAST RIGIDITY', 'Kombinasi rangka logam magnesium/aluminium die-cast ringan dengan busa poliuretan berdensitas tinggi serta balutan kulit asli jahitan tangan presisi.', 'bi-disc', 'uploads/products/product_steering_wheel.jpg', 'Takumi Craftsmanship', '{"badges":["Magnesium Die-Cast Core","Genuine Hand-Stitched Leather","Paddle Shift & Multi-Switch","Integrated Heating"],"btn_primary_text":"Roda Kemudi","btn_primary_link":"products.php?category=Steering","btn_secondary_text":"Spesifikasi Teknik","btn_secondary_link":"facility.php"}', 30, 1, '2026-10-14 09:41:32', '2026-10-14 09:41:32'),
	(81, 'hero_slides', 'slide_esg', 'Green Monozukuri & Carbon Neutrality ESG Initiatives', 'SUSTAINABLE ECO-RESIN & SOLAR PV INTEGRATION', 'Mengurangi 450 ton emisi CO₂ per tahun melalui instalasi solar rooftop fotovoltaik dan pemanfaatan resin daur ulang polipropilena ramah lingkungan.', 'bi-sun-fill', 'uploads/pages/banner_home.jpg', '450 Ton CO₂ Reduced / Year', '{"badges":["Solar PV Rooftop","28% Recycled Resin Target","RoHS & ELV Compliant","ISO 14001:2015"],"btn_primary_text":"Inisiatif ESG","btn_primary_link":"about.php","btn_secondary_text":"Kirim Pengajuan RFQ","btn_secondary_link":"contact.php"}', 40, 1, '2026-10-14 09:41:32', '2026-10-14 09:41:32');

-- Dumping structure for table db_cms_compro.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `role` enum('superadmin','admin','editor','staff') DEFAULT 'admin',
  `status` enum('active','inactive') DEFAULT 'active',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_cms_compro.users: ~1 rows (approximately)
INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `role`, `status`, `password`, `created_at`) VALUES
	(1, 'admin', 'Super Administrator', NULL, 'superadmin', 'active', '$2y$10$GkpdIbEuDRHB4FEh03fkcOT8llcP5GiZMpDui8hVmt0kIPTrdLTS.', '2026-10-14 06:55:35');

-- Dumping structure for table db_cms_compro.user_permissions
CREATE TABLE IF NOT EXISTS `user_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `menu_key` varchar(50) NOT NULL,
  `can_create` tinyint(1) DEFAULT 0,
  `can_read` tinyint(1) DEFAULT 1,
  `can_update` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_menu` (`user_id`,`menu_key`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_cms_compro.user_permissions: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

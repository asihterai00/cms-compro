CREATE DATABASE IF NOT EXISTS db_cms_compro;

USE db_cms_compro;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    image TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT,
    published_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE careers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position VARCHAR(150) NOT NULL,
    department VARCHAR(150),
    location VARCHAR(150),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    image TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products
(name, description, image)
VALUES
(
    'Automotive Steering Wheel',
    'Dummy product description for steering wheel components.',
    'https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=1000&q=80'
),
(
    'Interior Components',
    'Dummy product description for automotive interior components.',
    'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1000&q=80'
),
(
    'Exterior Components',
    'Dummy product description for automotive exterior components.',
    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1000&q=80'
);

INSERT INTO news
(title, content, published_at)
VALUES
(
    'Company Profile Website Development',
    'Dummy news untuk tampilan website company profile.',
    NOW()
),
(
    'Continuous Improvement Program',
    'Dummy news tentang program improvement perusahaan.',
    DATE_SUB(NOW(), INTERVAL 10 DAY)
),
(
    'Manufacturing Excellence',
    'Dummy news mengenai manufacturing excellence.',
    DATE_SUB(NOW(), INTERVAL 20 DAY)
);

INSERT INTO careers
(position, department, location, description)
VALUES
(
    'IT Staff',
    'Information Technology',
    'Tambun, Bekasi',
    'Dummy vacancy untuk posisi IT Staff.'
),
(
    'Production Staff',
    'Production',
    'Tambun, Bekasi',
    'Dummy vacancy untuk posisi Production Staff.'
);

INSERT INTO gallery
(title, image)
VALUES
(
    'Production Facility',
    'https://images.unsplash.com/photo-1565610222536-ef125c59da2e?auto=format&fit=crop&w=1000&q=80'
),
(
    'Engineering Facility',
    'https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=1000&q=80'
);


CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE company_profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    tagline VARCHAR(255),
    short_description TEXT,
    about_text TEXT,
    vision TEXT,
    mission TEXT,
    values_text TEXT,
    address TEXT,
    phone VARCHAR(100),
    email VARCHAR(150),
    logo VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO company_profile
(
    company_name,
    tagline,
    short_description,
    about_text,
    vision,
    mission,
    values_text,
    address,
    phone,
    email
)
VALUES
(
    'PT Nihon Plast Indonesia',
    'Driving Quality Through Automotive Manufacturing',
    'Dummy company profile data for website development.',
    'PT Nihon Plast Indonesia is a dummy company profile prepared for website development and testing. Replace this text with official company information before production.',
    'To become a trusted and competitive automotive manufacturing company.',
    'Deliver quality products through reliable processes, technology, and continuous improvement.',
    'Quality, Safety, Integrity, Teamwork, and Continuous Improvement.',
    'Jalan Raya Tambun KM.38.2, Bekasi, Jawa Barat, Indonesia',
    '(021) 000-0000',
    'info@example.com'
);


CREATE TABLE IF NOT EXISTS page_banners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_key VARCHAR(40) NOT NULL UNIQUE,
    page_title VARCHAR(200) NOT NULL,
    subtitle TEXT,
    image VARCHAR(255) DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);

INSERT IGNORE INTO page_banners (page_key, page_title, subtitle) VALUES
('home', 'PT Nihon Plast Indonesia', 'Driving Quality Through Automotive Manufacturing'),
('about', 'About Company', 'Learn more about our company, vision, and mission.'),
('products', 'Our Products', 'Automotive components built with quality and precision.'),
('gallery', 'Our Gallery', 'Photos of our facilities and company activities.'),
('quality', 'Quality & Continuous Improvement', 'Commitment to quality, safety, and improvement.'),
('facility', 'Factory & Facility', 'Explore our manufacturing facilities.'),
('career', 'Join Our Team', 'Explore career opportunities with us.'),
('news', 'Company News', 'Latest updates and company activities.'),
('contact', 'Contact Us', 'Get in touch with our team.');


CREATE TABLE IF NOT EXISTS gallery_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gallery_id INT NOT NULL,
    media_type ENUM('image', 'video', 'youtube') NOT NULL,
    media_path VARCHAR(500) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (gallery_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS project_memory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    metadata JSON NULL,
    author VARCHAR(100) DEFAULT 'antigravity-ai',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


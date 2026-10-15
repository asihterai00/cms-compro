<?php
require_once __DIR__ . '/../config/database.php';

$entries = [
    [
        'category' => 'architecture',
        'title' => 'CMS Architecture & Tech Stack',
        'content' => "PT Nihon Plast Indonesia Company Profile CMS.\n- Backend: Native PHP 8.3 (Devaron)\n- Database: MySQL 8 (Host: 192.168.1.6:3306, DB: db_cms_compro, User: root)\n- Frontend: Bootstrap 5, Bootstrap Icons, responsive custom CSS (home-scroll.css, content-clickable.css, product-detail-layout.css)\n- Templating: Modular includes (includes/head.php, includes/navbar.php, includes/footer.php, includes/page_banner.php, includes/content_helpers.php)\n- Admin Panel: Located in /admin/ protected by session auth (admin/auth.php).\n- Uploads: Stored under uploads/company/, uploads/gallery/, uploads/products/, uploads/pages/.",
        'metadata' => json_encode(['tech' => ['PHP 8.3', 'MySQL', 'Bootstrap 5', 'Devaron']])
    ],
    [
        'category' => 'environment',
        'title' => 'Development Environment & Binaries',
        'content' => "Local environment specifications:\n- OS: Windows\n- Web server suite: Devaron (Apache/Nginx/MySQL)\n- PHP Binary: C:\\Devaron\\bin\\php\\php-8.3.30\\php.exe\n- Node.js: v22.19.0 (C:\\Program Files\\nodejs\\node.exe)\n- NPM: 10.9.3\n- Git: 2.55.0.windows.4\n- MySQL Connection: 192.168.1.6:3306 (user: root, db: db_cms_compro)\n- Workspace Root: C:\\Devaron\\www\\cms-compro",
        'metadata' => json_encode(['os' => 'Windows', 'php' => '8.3.30', 'node' => '22.19.0'])
    ],
    [
        'category' => 'database',
        'title' => 'Database Schema & Tables Overview',
        'content' => "Complete list of tables in db_cms_compro:\n- careers: Job vacancies and recruitment information.\n- company_profile: Singleton row holding company identity, vision, mission, contact info, logo path.\n- gallery: Photo and media category / album records.\n- gallery_media: Individual media files (image, local video, YouTube embed) linked to gallery.id.\n- messages: Inbound contact form submissions.\n- news: Company news articles and press releases.\n- page_banners: Page header banners (home, about, products, gallery, quality, facility, career, news, contact).\n- products: Company product catalog (name, description, image).\n- users: Admin accounts authenticated with password_hash/password_verify.\n- project_memory: AI & developer persistent memory records across sessions.",
        'metadata' => json_encode(['tables_count' => 10])
    ],
    [
        'category' => 'findings',
        'title' => 'Initial Codebase & Security Audit Findings',
        'content' => "Key audit observations:\n1. Zero Admin Users: The `users` table is currently empty, meaning admin panel cannot be accessed until an admin is created.\n2. Security Risk on admin/setup.php: The setup script remains in the project and is publicly accessible without protection.\n3. Dynamic gallery_media creation: gallery_media table creation was only in admin/gallery.php and gallery-detail.php; now synchronized across the schema.\n4. Input & File Upload Validation: Admin uploads currently check extensions; recommending adding MIME verification (finfo) and CSRF tokens for production hardening.\n5. Public Website: Responsively renders company profile data, product showcase, news feed, gallery modal, and contact message persistence.",
        'metadata' => json_encode(['status' => 'audited', 'critical_items' => 2])
    ],
    [
        'category' => 'tools_mcp',
        'title' => 'Configured Free & Open-Source MCP Servers',
        'content' => "100% Free & Open-Source (MIT/Apache) MCP servers installed and configured in ~/.gemini/config/mcp_config.json:\n1. @modelcontextprotocol/server-memory (MIT License): Knowledge graph memory server for long-term relational context.\n2. @modelcontextprotocol/server-sequential-thinking (MIT License): MCP server for deep step-by-step problem-solving.\n3. @benborla29/mcp-server-mysql (MIT License): Direct MySQL querying and schema exploration tool for db_cms_compro.\n- Zero API costs, zero subscriptions, fully self-hosted locally.",
        'metadata' => json_encode(['license' => 'MIT', 'cost' => '$0'])
    ],
    [
        'category' => 'changelog',
        'title' => 'Memory System & Tooling Initialization',
        'content' => "Initial setup performed:\n- Created `project_memory` table in MySQL `db_cms_compro`.\n- Built `scripts/memory.php` CLI tool for fast MySQL memory management.\n- Built `scripts/db_check.php` for database status checks.\n- Verified and created `gallery_media` table.\n- Installed open-source MCP packages globally.\n- Configured `mcp_config.json` with memory, sequential-thinking, and mysql MCP servers.\n- Generated `PROJECT_MEMORY.md`, `CHANGELOG.md`, `GEMINI.md` workspace rules, and web development skill.",
        'metadata' => json_encode(['action' => 'init'])
    ]
];

$stmt = $conn->prepare("INSERT INTO project_memory (category, title, content, metadata, author) VALUES (?, ?, ?, ?, 'antigravity-ai')");

foreach ($entries as $e) {
    $stmt->bind_param('ssss', $e['category'], $e['title'], $e['content'], $e['metadata']);
    $stmt->execute();
    echo "Inserted memory: [" . $e['category'] . "] " . $e['title'] . " (ID: " . $conn->insert_id . ")\n";
}

echo "All initial memories seeded successfully into MySQL!\n";

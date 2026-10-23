export const APP = {
  name: 'CMS Company Profile',
  company: 'PT Nihon Plast Indonesia',
  storageKey: 'npi-cms-v14-demo-1',
  publicUrl: (typeof window !== 'undefined' && window.__PUBLIC_URL__) ? window.__PUBLIC_URL__ : (import.meta.env?.VITE_PUBLIC_SITE_URL || '../index.php')
};

export const currentUser = (typeof window !== 'undefined' && window.__CURRENT_USER__) ? window.__CURRENT_USER__ : {
  id: 1,
  username: 'administrator',
  full_name: 'Company Administrator',
  email: 'admin@nihonplast.co.id',
  role: 'superadmin',
  status: 'active'
};

export const roles = ['superadmin', 'admin', 'editor', 'staff'];
export const languages = [
  { id: 'id', name: 'Bahasa Indonesia', short: 'ID', flag: '../assets/img/flags/id.svg', default: true },
  { id: 'en', name: 'English', short: 'EN', flag: '../assets/img/flags/en.svg' },
  { id: 'ja', name: '日本語', short: 'JA', flag: '../assets/img/flags/ja.svg' },
  { id: 'zh', name: '简体中文', short: 'ZH', flag: '../assets/img/flags/zh.svg' },
  { id: 'ko', name: '한국어', short: 'KO', flag: '../assets/img/flags/ko.svg' }
];

export function getActiveLang() {
  if (typeof window !== 'undefined') {
    if (window.__ACTIVE_LANG__) return window.__ACTIVE_LANG__;
    const q = new URLSearchParams(window.location.search).get('lang');
    if (q) return q;
    const m = document.cookie.match(/cms_lang=([^;]+)/);
    if (m) return m[1];
  }
  return 'id';
}

export const navTranslations = {
  id: {
    dashboard: { label: 'Dashboard', context: 'Ringkasan Utama & Status CMS' },
    home: { label: 'Beranda', context: 'Banner & Seksi Halaman Beranda' },
    about: { label: 'Tentang Kami', context: 'Profil Perusahaan, Visi Misi & Seksi Tentang Kami' },
    products: { label: 'Produk', context: 'Katalog Komponen Otomotif' },
    quality: { label: 'Mutu & ISO', context: 'Sertifikasi Mutu IATF 16949 & ISO' },
    facilities: { label: 'Fasilitas', context: 'Mesin Injeksi 150T-2500T & Pabrik' },
    gallery: { label: 'Galeri', context: 'Galeri Foto Manufaktur & Media' },
    news: { label: 'Berita', context: 'Berita & Publikasi' },
    career: { label: 'Karir', context: 'Lowongan Karir & Kualifikasi' },
    messages: { label: 'B2B RFQ', context: 'Pesan Masuk Permintaan Penawaran' },
    settings: { label: 'Pengaturan', context: 'Pusat Bahasa, Terjemahan & RBAC' }
  },
  en: {
    dashboard: { label: 'Dashboard', context: 'Executive Overview & System Status' },
    home: { label: 'Home Page', context: 'Home Sections, Hero Slides & Banners' },
    about: { label: 'About Us', context: 'Company Profile, Vision Mission & About Sections' },
    products: { label: 'Products', context: 'Automotive Component Catalog' },
    quality: { label: 'Quality & ISO', context: 'Quality Certifications IATF 16949 & ISO' },
    facilities: { label: 'Facilities', context: '150T-2500T Injection Presses & Plant' },
    gallery: { label: 'Gallery', context: 'Manufacturing Photo Gallery & Media' },
    news: { label: 'News', context: 'Press Releases & Publications' },
    career: { label: 'Careers', context: 'Job Vacancies & Qualifications' },
    messages: { label: 'B2B RFQ', context: 'Inbound Quotation Inquiries' },
    settings: { label: 'Settings', context: 'Multilingual & User RBAC Management' }
  },
  ja: {
    dashboard: { label: 'ダッシュボード', context: 'エグゼクティブ概要とシステム状況' },
    home: { label: 'ホームページ', context: 'トップページセクションとバナー' },
    about: { label: '会社案内', context: '企業概要・企業理念・ビジョン' },
    products: { label: '製品情報', context: '自動車部品カタログ' },
    quality: { label: '品質とISO', context: 'IATF 16949およびISO認証' },
    facilities: { label: '生産設備', context: '150T-2500T 射出成形機' },
    gallery: { label: 'ギャラリー', context: '工場写真ギャラリーとメディア' },
    news: { label: 'ニュース', context: '企業ニュースと広報' },
    career: { label: '採用情報', context: '求人募集と要件' },
    messages: { label: 'B2B見積', context: 'RFQお問い合わせ受信トレイ' },
    settings: { label: '設定', context: '多言語設定とユーザー権限' }
  },
  zh: {
    dashboard: { label: '仪表盘', context: '管理概览与系统状态' },
    home: { label: '首页管理', context: '主页区块与横幅管理' },
    about: { label: '关于我们', context: '公司概况、愿景使命与简介' },
    products: { label: '产品中心', context: '汽车零部件产品目录' },
    quality: { label: '质量与ISO', context: 'IATF 16949与ISO品质认证' },
    facilities: { label: '制造设施', context: '150T-2500T注塑成型机矩阵' },
    gallery: { label: '图片画廊', context: '生产图库与媒体资料' },
    news: { label: '新闻动态', context: '企业新闻与官方发布' },
    career: { label: '招贤纳士', context: '招聘职位与岗位要求' },
    messages: { label: 'B2B询价', context: '客户询价收件箱' },
    settings: { label: '系统设置', context: '多语言与用户权限管理' }
  },
  ko: {
    dashboard: { label: '대시보드', context: '경영 개요 및 시스템 상태' },
    home: { label: '홈페이지', context: '홈 섹션 및 배너 관리' },
    about: { label: '회사 소개', context: '기업 프로필 및 비전 미션' },
    products: { label: '제품 카탈로그', context: '자동차 부품 카탈로그' },
    quality: { label: '품질 & ISO', context: 'IATF 16949 및 ISO 품질 인증' },
    facilities: { label: '생산 설비', context: '150T-2500T 사출 성형기' },
    gallery: { label: '갤러리', context: '제조 현장 갤러리 및 미디어' },
    news: { label: '뉴스 & 공지', context: '기업 뉴스 및 공식 공지' },
    career: { label: '채용 정보', context: '채용 공고 및 자격 요건' },
    messages: { label: 'B2B 견적', context: '견적 요청 수신함' },
    settings: { label: '설정', context: '다국어 및 사용자 권한 관리' }
  }
};

const rawNavigation = [
  { key: 'dashboard',  icon: 'House',             route: 'admin/dashboard.php',          modules: [] },
  { key: 'home',       icon: 'Home',              route: 'admin/site_sections.php?page=home', modules: ['site_sections', 'site_section_items', 'banners'] },
  { key: 'about',      icon: 'Building2',         route: 'admin/company.php',            modules: ['company', 'site_sections'] },
  { key: 'products',   icon: 'Package',           route: 'admin/products.php',           modules: ['products'] },
  { key: 'quality',    icon: 'BadgeCheck',        route: 'admin/certifications.php',     modules: ['certifications'] },
  { key: 'facilities', icon: 'Factory',           route: 'admin/machinery.php',          modules: ['facility_machinery'] },
  { key: 'gallery',    icon: 'Images',            route: 'admin/gallery.php',            modules: ['gallery', 'media'] },
  { key: 'news',       icon: 'Newspaper',         route: 'admin/news.php',               modules: ['news'] },
  { key: 'career',     icon: 'BriefcaseBusiness', route: 'admin/career.php',             modules: ['careers'] },
  { key: 'messages',   icon: 'MessageCircle',     route: 'admin/messages.php',           modules: ['messages'] },
  { key: 'settings',   icon: 'Settings',          route: 'admin/translations.php',       modules: ['translations', 'users'] }
];

export function getNavItems(langCode = null) {
  const code = langCode || getActiveLang();
  const dict = navTranslations[code] || navTranslations.id;
  return rawNavigation.map(item => ({
    ...item,
    label: dict[item.key]?.label || item.key,
    context: dict[item.key]?.context || ''
  }));
}

export const navigation = getNavItems();

export const moduleLabels = {
  id: {
    products: 'Katalog Produk',
    news: 'Berita & Publikasi',
    careers: 'Karir & Rekrutmen',
    gallery: 'Galeri Manufaktur',
    facility_machinery: 'Mesin Injeksi Presisi',
    certifications: 'Sertifikasi Mutu ISO',
    messages: 'Pesan B2B RFQ',
    users: 'Pengguna & RBAC',
    site_sections: 'Seksi Halaman',
    site_section_items: 'Sub-Item / Kartu Seksi',
    banners: 'Header Banner',
    company: 'Profil Perusahaan',
    translations: 'Pusat Multibahasa',
    media: 'Media Asset Library'
  },
  en: {
    products: 'Product Catalog',
    news: 'News & Publications',
    careers: 'Careers & Recruitment',
    gallery: 'Manufacturing Gallery',
    facility_machinery: 'Injection Machinery',
    certifications: 'Quality Certifications',
    messages: 'RFQ Messages',
    users: 'Users & Permissions',
    site_sections: 'Page Sections',
    site_section_items: 'Section Items',
    banners: 'Header Banners',
    company: 'Company Profile',
    translations: 'Translations',
    media: 'Media Library'
  },
  ja: {
    products: '製品カタログ',
    news: 'ニュースと広報',
    careers: '採用・求人情報',
    gallery: '工場ギャラリー',
    facility_machinery: '射出成形機設備',
    certifications: '品質認証とISO',
    messages: 'B2B見積メッセージ',
    users: 'ユーザーと権限',
    site_sections: 'ページセクション',
    site_section_items: 'セクション項目',
    banners: 'ヘッダーバナー',
    company: '企業プロフィール',
    translations: '多言語翻訳',
    media: 'メディアライブラリ'
  },
  zh: {
    products: '产品目录',
    news: '新闻与发布',
    careers: '招聘与求职',
    gallery: '工厂图库',
    facility_machinery: '注塑成型机',
    certifications: '品质与ISO认证',
    messages: 'B2B询价消息',
    users: '用户与权限',
    site_sections: '页面区块',
    site_section_items: '子项目卡片',
    banners: '页头横幅',
    company: '公司档案',
    translations: '多语言翻译',
    media: '媒体资源库'
  },
  ko: {
    products: '제품 카탈로그',
    news: '뉴스 및 공지',
    careers: '채용 및 구인',
    gallery: '제조 갤러리',
    facility_machinery: '사출 성형 설비',
    certifications: '품질 및 ISO 인증',
    messages: 'B2B 견적 메시지',
    users: '사용자 및 권한',
    site_sections: '페이지 섹션',
    site_section_items: '섹션 항목 카드',
    banners: '헤더 배너',
    company: '회사 프로필',
    translations: '다국어 번역',
    media: '미디어 라이브러리'
  }
};

const curLang = getActiveLang();
const curModuleDict = moduleLabels[curLang] || moduleLabels.id;

export const translatableFields = {
  products: ['name', 'description'],
  site_sections: ['title', 'subtitle', 'content', 'badge_text', 'button_text'],
  site_section_items: ['title', 'subtitle', 'content', 'badge'],
  company: ['tagline', 'short_description', 'about_text', 'vision', 'mission', 'values_text'],
  certifications: ['name', 'category'],
  facility_machinery: ['model_brand', 'application'],
  gallery: ['title'],
  news: ['title', 'content'],
  careers: ['position', 'department', 'description'],
  banners: ['page_title', 'subtitle']
};

export const modules = {
  products: { label: curModuleDict.products || 'Product catalog', route: 'admin/products.php', icon: 'Package', fields: ['name', 'description', 'image', 'sort_order'] },
  news: { label: curModuleDict.news || 'News & publications', route: 'admin/news.php', icon: 'Newspaper', fields: ['title', 'content', 'image', 'published_at', 'sort_order'] },
  careers: { label: curModuleDict.careers || 'Careers', route: 'admin/career.php', icon: 'BriefcaseBusiness', fields: ['position', 'department', 'location', 'description', 'sort_order'] },
  gallery: { label: curModuleDict.gallery || 'Manufacturing gallery', route: 'admin/gallery.php', icon: 'Images', fields: ['title', 'image', 'sort_order'] },
  facility_machinery: { label: curModuleDict.facility_machinery || 'Injection machinery', route: 'admin/machinery.php', icon: 'Factory', fields: ['category', 'model_brand', 'tonnage', 'application', 'automation', 'is_active', 'sort_order'] },
  certifications: { label: curModuleDict.certifications || 'Quality certifications', route: 'admin/certifications.php', icon: 'BadgeCheck', fields: ['name', 'category', 'certificate_number', 'issued_by', 'valid_until', 'status', 'sort_order'] },
  messages: { label: curModuleDict.messages || 'RFQ messages', route: 'admin/messages.php', icon: 'MessageCircle', fields: ['name', 'email', 'message', 'status', 'created_at'] },
  users: { label: curModuleDict.users || 'Users & permissions', route: 'admin/users.php', icon: 'Users', fields: ['username', 'full_name', 'email', 'role', 'status'] },
  site_sections: { label: curModuleDict.site_sections || 'Page sections', route: 'admin/site_sections.php', icon: 'PanelsTopLeft', fields: ['page', 'section_key', 'title', 'subtitle', 'badge_text', 'content', 'button_text', 'button_link', 'icon', 'is_active', 'sort_order'] },
  site_section_items: { label: curModuleDict.site_section_items || 'Section items', route: 'admin/site_section_items.php', icon: 'LayoutGrid', fields: ['section_key', 'item_key', 'title', 'subtitle', 'badge', 'content', 'icon', 'is_active', 'sort_order'] },
  banners: { label: curModuleDict.banners || 'Header banners', route: 'admin/banners.php', icon: 'PanelTop', fields: ['page_key', 'page_title', 'subtitle', 'image'] },
  company: { label: curModuleDict.company || 'Company profile', route: 'admin/company.php', icon: 'Building2', fields: ['name', 'tagline', 'short_description', 'about_text', 'vision', 'mission', 'values_text', 'email'] },
  translations: { label: curModuleDict.translations || 'Translations', route: 'admin/translations.php', icon: 'Languages', fields: ['entity_type', 'entity_id', 'lang_code', 'field_name', 'translation_value'] },
  media: { label: curModuleDict.media || 'Media library', route: 'admin/media.php', icon: 'FolderOpen', fields: ['name', 'path', 'category'] }
};

export const moduleEntityMap = {
  products: 'product',
  site_sections: 'site_section',
  site_section_items: 'site_section_item',
  certifications: 'certification',
  facility_machinery: 'facility_machinery',
  gallery: 'gallery',
  news: 'news',
  careers: 'career',
  banners: 'page_banner',
  company: 'company_profile',
  messages: 'message'
};

const all = ['create', 'read', 'update', 'delete'];
const read = ['read'];
const edit = ['create', 'read', 'update'];
const menuKeys = navigation.map(n => n.key);
const moduleKeys = Object.keys(modules);

export const permissionMatrix = {
  superadmin: Object.fromEntries([...menuKeys, ...moduleKeys].map(k => [k, all])),
  admin: Object.fromEntries([...menuKeys, ...moduleKeys].map(k => [k, k === 'users' ? read : all])),
  editor: {
    dashboard: read,
    home: edit,
    about: edit,
    products: edit,
    quality: edit,
    facilities: read,
    gallery: edit,
    news: edit,
    career: edit,
    messages: read,
    settings: read,
    site_sections: edit,
    site_section_items: edit,
    banners: edit,
    certifications: edit,
    facility_machinery: read,
    gallery_items: edit,
    translations: edit,
    media: edit,
    company: read,
    users: []
  },
  staff: {
    dashboard: read,
    home: read,
    about: read,
    products: read,
    quality: read,
    facilities: read,
    gallery: read,
    news: read,
    career: read,
    messages: ['read', 'update'],
    certifications: read,
    facility_machinery: read
  }
};

export function can(role, key, action = 'read') {
  return permissionMatrix[role]?.[key]?.includes(action) || false;
}

export const kpis = [
  { label: 'Total Revenue', value: '$24,580', growth: '12.5%', icon: 'BarChart3', color: '#005A36', light: '#EBF7EE', points: [18, 28, 24, 38, 32, 46, 40, 55, 50, 60] },
  { label: 'Total Orders', value: '1,245', growth: '8.2%', icon: 'ShoppingBag', color: '#007A4A', light: '#ECFDF5', points: [20, 28, 25, 34, 31, 40, 38, 47, 44, 53] },
  { label: 'Customers', value: '892', growth: '10.4%', icon: 'Users', color: '#059669', light: '#F0FDF4', points: [18, 22, 20, 30, 27, 34, 32, 42, 40, 52] }
];

export const revenue = [
  { name: 'Jan', value: 12000 },
  { name: 'Feb', value: 16000 },
  { name: 'Mar', value: 14000 },
  { name: 'Apr', value: 24000 },
  { name: 'May', value: 18000 },
  { name: 'Jun', value: 27000 }
];

export const weeklyRevenue = [
  { name: 'Mon', value: 2800 },
  { name: 'Tue', value: 3200 },
  { name: 'Wed', value: 2980 },
  { name: 'Thu', value: 4100 },
  { name: 'Fri', value: 3500 },
  { name: 'Sat', value: 3800 },
  { name: 'Sun', value: 4200 }
];

export const periods = ['This Week', 'This Month', 'This Quarter', 'This Year'];

export function chartData(period) {
  if (period === 'This Week') return weeklyRevenue;
  if (period === 'This Quarter') return [{ name: 'Q1', value: 42000 }, { name: 'Q2', value: 69000 }];
  if (period === 'This Year') return [{ name: '2025', value: 111000 }];
  return revenue;
}

export const productStats = [
  { id: 1, sold: 320, price: '$12,540', icon: 'Disc3' },
  { id: 2, sold: 280, price: '$9,420', icon: 'ShieldCheck' },
  { id: 3, sold: 210, price: '$8,750', icon: 'Armchair' },
  { id: 4, sold: 160, price: '$7,980', icon: 'Layers' }
];

export const defaultInitialData = {
  products: [
    { id: 1, name: 'Steering Wheel', description: 'Driver interface components for automotive OEM programs.', image: '', sort_order: 10 },
    { id: 2, name: 'Airbag Module', description: 'Occupant safety module assemblies for vehicle platforms.', image: '', sort_order: 20 },
    { id: 3, name: 'Interior Parts', description: 'Precision interior trim and functional cabin components.', image: '', sort_order: 30 },
    { id: 4, name: 'Resin Parts', description: 'Injection-molded resin components for automotive applications.', image: '', sort_order: 40 }
  ],
  news: [
    { id: 1, title: 'Manufacturing capability overview', content: 'Explore our automotive component production capabilities and quality processes.', image: '', sort_order: 10, published_at: '2025-01-20' }
  ],
  careers: [
    { id: 1, position: 'Quality Engineer', department: 'Quality Assurance', location: 'Indonesia', description: 'Support process quality, root-cause analysis, and continuous improvement.', sort_order: 10 }
  ],
  certifications: ['IATF 16949', 'ISO 14001', 'ISO 45001', 'SMK3'].map((name, i) => ({
    id: i + 1,
    name,
    category: 'Automotive & Quality',
    certificate_number: 'DEMO-' + String(i + 1).padStart(4, '0'),
    issued_by: 'Demo certification body',
    valid_until: i === 0 ? '2025-02-15' : '2026-12-31',
    status: 'active',
    sort_order: (i + 1) * 10
  })),
  facility_machinery: [
    { id: 1, category: 'Injection molding', model_brand: 'Demo precision injection press', tonnage: '150T', application: 'Small resin components', automation: 'Robotic take-out', is_active: true, sort_order: 10 },
    { id: 2, category: 'Injection molding', model_brand: 'Demo large-format press', tonnage: '2500T', application: 'Large interior components', automation: 'Integrated handling', is_active: true, sort_order: 20 }
  ],
  messages: [
    { id: 1, name: 'Procurement Team', email: 'procurement@example.com', message: 'Please share your capability statement and quotation process for resin parts.', status: 'unread', created_at: '2025-01-28T09:30:00' },
    { id: 2, name: 'OEM Sourcing', email: 'sourcing@example.com', message: 'We would like to discuss steering wheel development requirements.', status: 'unread', created_at: '2025-01-25T14:00:00' }
  ],
  users: [
    { id: 1, username: 'administrator', full_name: 'Company Administrator', email: 'admin@nihonplast.co.id', role: 'superadmin', status: 'active' },
    { id: 2, username: 'content.editor', full_name: 'Content Editor', email: 'editor@nihonplast.co.id', role: 'editor', status: 'active' }
  ],
  gallery: [{ id: 1, title: 'Injection molding facility', image: '', sort_order: 10 }],
  site_sections: [{ id: 1, page: 'home', section_key: 'home_intro', title: 'Precision in every component', content: 'Automotive components engineered for quality and consistency.', is_active: true, sort_order: 10 }],
  site_section_items: [{ id: 1, section_key: 'home_intro', item_key: 'quality', title: 'Quality manufacturing', content: 'Process control across every stage of production.', sort_order: 10 }],
  banners: [{ id: 1, page_key: 'home', page_title: 'PT Nihon Plast Indonesia', subtitle: 'Automotive component manufacturing', image: '' }],
  company: [{ id: 1, name: 'PT Nihon Plast Indonesia', description: 'Company profile and automotive manufacturing capabilities.', email: 'contact@nihonplast.co.id' }],
  translations: [{ id: 1, entity_type: 'product', entity_id: 1, lang_code: 'en', field_name: 'name', translation_value: 'Steering Wheel' }],
  media: [{ id: 1, name: 'Company profile placeholder', path: '/uploads/company/profile-placeholder.pdf', category: 'company' }]
};

export const initialData = (typeof window !== 'undefined' && window.__CMS_DATA__) ? {
  ...defaultInitialData,
  ...window.__CMS_DATA__
} : defaultInitialData;

export const notificationEvents = [
  { id: 'cert', title: 'Certification renewal approaching', detail: 'Demo IATF 16949 expires February 15, 2025.', module: 'certifications', record: 1, icon: 'BadgeCheck' },
  { id: 'career', title: 'Vacancy updated', detail: 'Quality Engineer vacancy is ready for review.', module: 'careers', record: 1, icon: 'BriefcaseBusiness' },
  { id: 'section', title: 'Page section updated', detail: 'An editor updated the home introduction.', module: 'site_sections', record: 1, icon: 'PanelsTopLeft' }
];

export const labelOf = row => row.name || row.title || row.position || row.full_name || row.page_title || row.model_brand || row.translation_value || 'Untitled';

export function routeFor(menu, module, id) {
  const route = modules[module]?.route || navigation.find(n => n.key === menu)?.route || navigation[0].route;
  return '#/' + route + (id ? '?edit=' + encodeURIComponent(id) : '');
}

export function parseRoute(hash) {
  const [path, query] = hash.replace(/^#\/?/, '').split('?');
  const module = Object.keys(modules).find(k => modules[k].route === path);
  const menu = module ? navigation.find(n => n.modules.includes(module))?.key : navigation.find(n => n.route === path)?.key;
  return { menu: menu || 'dashboard', module: module || null, id: new URLSearchParams(query).get('edit') };
}

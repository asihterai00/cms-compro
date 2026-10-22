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
  { id: 'id', name: 'Indonesian', default: true },
  { id: 'en', name: 'English' },
  { id: 'ja', name: 'Japanese' },
  { id: 'zh', name: 'Simplified Chinese' },
  { id: 'ko', name: 'Korean' }
];

/**
 * Navigation strictly aligned with the Public Site structure and positions:
 * 1. Home / Sections -> Page Builder
 * 2. Produk -> Products
 * 3. Mutu & ISO -> Quality & ISO
 * 4. Fasilitas -> Facilities
 * 5. Galeri -> Gallery
 * 6. Berita -> News
 * 7. Karir -> Career
 * 8. B2B RFQ -> B2B RFQ Inbox
 * 9. Pengaturan -> Settings (Company, Translations, Users)
 */
export const navigation = [
  { key: 'dashboard',  label: 'Dashboard',    icon: 'House',             route: 'admin/dashboard.php',     modules: [],                                        context: 'Executive overview' },
  { key: 'pages',      label: 'Page Builder', icon: 'PanelsTopLeft',     route: 'admin/site_sections.php', modules: ['site_sections', 'site_section_items', 'banners'], context: 'Halaman Web & Banners' },
  { key: 'products',   label: 'Products',     icon: 'Package',           route: 'admin/products.php',      modules: ['products'],                              context: 'Katalog Produk Otomotif' },
  { key: 'quality',    label: 'Quality & ISO',icon: 'BadgeCheck',        route: 'admin/certifications.php',modules: ['certifications'],                        context: 'Sertifikasi Mutu IATF & ISO' },
  { key: 'facilities', label: 'Facilities',   icon: 'Factory',           route: 'admin/machinery.php',     modules: ['facility_machinery'],                    context: 'Mesin Injeksi 150T-2500T' },
  { key: 'gallery',    label: 'Gallery',      icon: 'Images',            route: 'admin/gallery.php',       modules: ['gallery', 'media'],                      context: 'Galeri Manufaktur & Media' },
  { key: 'news',       label: 'News',         icon: 'Newspaper',         route: 'admin/news.php',          modules: ['news'],                                  context: 'Berita & Publikasi' },
  { key: 'career',     label: 'Career',       icon: 'BriefcaseBusiness', route: 'admin/career.php',        modules: ['careers'],                               context: 'Lowongan Karir' },
  { key: 'messages',   label: 'B2B RFQ',      icon: 'MessageCircle',     route: 'admin/messages.php',      modules: ['messages'],                              context: 'Pesan Masuk Permintaan Penawaran' },
  { key: 'settings',   label: 'Settings',     icon: 'Settings',          route: 'admin/company.php',       modules: ['company', 'translations', 'users'],       context: 'Profil Perusahaan, Bahasa & RBAC' }
];

export const modules = {
  products: { label: 'Product catalog', route: 'admin/products.php', icon: 'Package', fields: ['name', 'description', 'image'] },
  news: { label: 'News & publications', route: 'admin/news.php', icon: 'Newspaper', fields: ['title', 'content', 'image', 'published_at'] },
  careers: { label: 'Careers', route: 'admin/career.php', icon: 'BriefcaseBusiness', fields: ['position', 'department', 'location', 'description'] },
  gallery: { label: 'Manufacturing gallery', route: 'admin/gallery.php', icon: 'Images', fields: ['title', 'image'] },
  facility_machinery: { label: 'Injection machinery', route: 'admin/machinery.php', icon: 'Factory', fields: ['category', 'model_brand', 'tonnage', 'application', 'automation', 'is_active'] },
  certifications: { label: 'Quality certifications', route: 'admin/certifications.php', icon: 'BadgeCheck', fields: ['name', 'category', 'certificate_number', 'issued_by', 'valid_until', 'status'] },
  messages: { label: 'RFQ messages', route: 'admin/messages.php', icon: 'MessageCircle', fields: ['name', 'email', 'message', 'status', 'created_at'] },
  users: { label: 'Users & permissions', route: 'admin/users.php', icon: 'Users', fields: ['username', 'full_name', 'email', 'role', 'status'] },
  site_sections: { label: 'Page sections', route: 'admin/site_sections.php', icon: 'PanelsTopLeft', fields: ['page', 'section_key', 'title', 'content', 'is_active'] },
  site_section_items: { label: 'Section items', route: 'admin/site_section_items.php', icon: 'LayoutGrid', fields: ['section_key', 'item_key', 'title', 'content'] },
  banners: { label: 'Header banners', route: 'admin/banners.php', icon: 'PanelTop', fields: ['page_key', 'page_title', 'subtitle', 'image'] },
  company: { label: 'Company profile', route: 'admin/company.php', icon: 'Building2', fields: ['name', 'description', 'email'] },
  translations: { label: 'Translations', route: 'admin/translations.php', icon: 'Languages', fields: ['entity_type', 'entity_id', 'lang_code', 'field_name', 'translation_value'] },
  media: { label: 'Media library', route: 'admin/media.php', icon: 'FolderOpen', fields: ['name', 'path', 'category'] }
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
    pages: edit,
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
    pages: read,
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
    { id: 1, name: 'Steering Wheel', description: 'Driver interface components for automotive OEM programs.', image: '' },
    { id: 2, name: 'Airbag Module', description: 'Occupant safety module assemblies for vehicle platforms.', image: '' },
    { id: 3, name: 'Interior Parts', description: 'Precision interior trim and functional cabin components.', image: '' },
    { id: 4, name: 'Resin Parts', description: 'Injection-molded resin components for automotive applications.', image: '' }
  ],
  news: [
    { id: 1, title: 'Manufacturing capability overview', content: 'Explore our automotive component production capabilities and quality processes.', image: '', published_at: '2025-01-20' }
  ],
  careers: [
    { id: 1, position: 'Quality Engineer', department: 'Quality Assurance', location: 'Indonesia', description: 'Support process quality, root-cause analysis, and continuous improvement.' }
  ],
  certifications: ['IATF 16949', 'ISO 14001', 'ISO 45001', 'SMK3'].map((name, i) => ({
    id: i + 1,
    name,
    category: 'Automotive & Quality',
    certificate_number: 'DEMO-' + String(i + 1).padStart(4, '0'),
    issued_by: 'Demo certification body',
    valid_until: i === 0 ? '2025-02-15' : '2026-12-31',
    status: 'active'
  })),
  facility_machinery: [
    { id: 1, category: 'Injection molding', model_brand: 'Demo precision injection press', tonnage: '150T', application: 'Small resin components', automation: 'Robotic take-out', is_active: true },
    { id: 2, category: 'Injection molding', model_brand: 'Demo large-format press', tonnage: '2500T', application: 'Large interior components', automation: 'Integrated handling', is_active: true }
  ],
  messages: [
    { id: 1, name: 'Procurement Team', email: 'procurement@example.com', message: 'Please share your capability statement and quotation process for resin parts.', status: 'unread', created_at: '2025-01-28T09:30:00' },
    { id: 2, name: 'OEM Sourcing', email: 'sourcing@example.com', message: 'We would like to discuss steering wheel development requirements.', status: 'unread', created_at: '2025-01-25T14:00:00' }
  ],
  users: [
    { id: 1, username: 'administrator', full_name: 'Company Administrator', email: 'admin@nihonplast.co.id', role: 'superadmin', status: 'active' },
    { id: 2, username: 'content.editor', full_name: 'Content Editor', email: 'editor@nihonplast.co.id', role: 'editor', status: 'active' }
  ],
  gallery: [{ id: 1, title: 'Injection molding facility', image: '' }],
  site_sections: [{ id: 1, page: 'home', section_key: 'home_intro', title: 'Precision in every component', content: 'Automotive components engineered for quality and consistency.', is_active: true }],
  site_section_items: [{ id: 1, section_key: 'home_intro', item_key: 'quality', title: 'Quality manufacturing', content: 'Process control across every stage of production.' }],
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

# PT Nihon Plast Indonesia Company Profile - V4

V4 adalah lanjutan dari V3 Admin Professional.

## Fitur baru

1. Company Profile Editor
2. Upload Logo
3. Gallery Upload / Edit / Delete
4. Career Add / Edit / Delete
5. Halaman About mengambil data Company Profile dari database
6. Dummy data PT Nihon Plast Indonesia

## Instalasi

1. Extract folder ke:

C:\xampp\htdocs\

2. Jalankan Apache dan MySQL.

3. Buka phpMyAdmin.

4. Import:

database/schema.sql

Schema V4 menambahkan tabel `company_profile`.

5. Buat / gunakan database:

nihon_plast_profile

6. Website:

http://localhost/nihon-plast-company-profile-v4/

7. Admin:

http://localhost/nihon-plast-company-profile-v4/admin/

## Upload

Logo disimpan di:

uploads/company/

Gallery disimpan di:

uploads/gallery/

Pastikan folder tersebut dapat ditulis oleh Apache.

## Catatan

Data masih dummy. Sebelum production, tambahkan CSRF protection, validasi MIME file yang lebih ketat, permission upload, HTTPS, backup database, dan audit log.


## V4.1 Layout Fix

Admin pages Company Profile, Career, and Gallery now use the same
Admin Panel sidebar/topbar layout as Dashboard, Products, News,
Messages, and Users.


## V4.2 Upload Fix

Folder upload logo dan gallery sekarang dibuat otomatis oleh PHP
jika folder belum tersedia.

Folder:
- uploads/company/
- uploads/gallery/

Jika Windows/XAMPP menolak upload karena permission, pastikan folder
project dapat ditulis oleh Apache/PHP.


## V4.3 Products Image Fix

Products now:
- automatically creates `uploads/products/`
- checks whether the upload folder is writable
- correctly displays local uploaded images
- correctly displays existing external HTTPS image URLs

## V4.4 Public Website CMS Binding Fix

- Homepage now reads Company Profile fields from MySQL.
- Navbar now reads company name and uploaded logo from MySQL.
- Public Gallery page added and connected to Gallery records in Admin Panel.
- Homepage featured products are still loaded from the `products` table.
- Gallery menu added to the public navbar.

## V4.6 Page Banner Management

Admin Panel includes Page Banners. Each public page has its own banner image,
title, and subtitle: Home, About, Products, Gallery, Quality, Facility, Career,
News, and Contact.

Open `admin/banners.php` after logging into Admin. The `page_banners` table is
created automatically for existing installations. Images are stored in
`uploads/pages/`. JPG, JPEG, PNG, WEBP up to 5 MB. No database re-import needed.

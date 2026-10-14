<?php
require_once __DIR__ . '/../config/database.php';

$address = "Kawasan Industri Indotaisei Sektor 1A Blok B-3, Desa Kalihurip, Kec. Cikampek, Kab. Karawang, Jawa Barat 41373, Indonesia";
$phone = "+62 (264) 351-470 / +62 (264) 351-471";
$email = "info@n-plast.co.id";
$about = "PT Nihon Plast Indonesia merupakan anak perusahaan dari Nihon Plast Co., Ltd. (Jepang) yang didirikan untuk menghadirkan keunggulan manufaktur komponen otomotif kelas dunia di Indonesia. Berlokasi strategis di Kawasan Industri Indotaisei Cikampek, Karawang, fasilitas manufaktur kami mengintegrasikan teknologi injeksi plastik presisi (Precision Resin Molding berkapasitas 150T hingga 2500T), keahlian pembungkusan kulit premium (Genuine Leather & Synthetic Wrapping Craftsmanship), perakitan modul keselamatan airbag di Cleanroom Class 8 berstandar global, serta laboratorium pengujian kualitas ketat demi memenuhi standar OEM Tier-1 internasional dan regulasi industri Republik Indonesia (TKDN, IATF 16949, ISO 14001, dan SMK3 PP 50/2012).";

$stmt = $conn->prepare("UPDATE company_profile SET address = ?, phone = ?, email = ?, about_text = ? WHERE id = 1");
$stmt->bind_param("ssss", $address, $phone, $email, $about);
if ($stmt->execute()) {
    echo "company_profile successfully updated with authentic Karawang address and facility profile.\n";
} else {
    echo "Error updating company_profile: " . $conn->error . "\n";
}

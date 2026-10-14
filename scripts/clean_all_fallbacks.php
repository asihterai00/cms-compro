<?php
// Replace unsplash URLs with local high-quality assets in public PHP files

$replacements = [
    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=900&q=80' => 'uploads/products/product_steering_wheel.jpg',
    'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800&q=80' => 'uploads/products/product_steering_wheel.jpg',
    'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?auto=format&fit=crop&w=900&q=80' => 'uploads/products/product_airbag_module.jpg',
    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=900&q=80' => 'uploads/products/product_cockpit_resin.svg',
    'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=900&q=80' => 'uploads/gallery/facility_cleanroom_lab.svg',
    'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80' => 'uploads/gallery/facility_cleanroom_lab.svg',
    'https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=800&q=80' => 'uploads/gallery/facility_cleanroom_lab.svg',
    'https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=900&q=80' => 'uploads/gallery/facility_cleanroom_lab.svg',
    'https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=1000&q=80' => 'uploads/gallery/facility_cleanroom_lab.svg',
    'https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&w=1200&q=80' => 'uploads/gallery/facility_injection_molding.jpg',
    'https://images.unsplash.com/photo-1565610222536-ef125c59da2e?auto=format&fit=crop&w=800&q=80' => 'uploads/gallery/facility_injection_molding.jpg',
    'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=1200&q=80' => 'uploads/gallery/facility_injection_molding.jpg',
];

$files = ['index.php', 'about.php', 'career.php', 'facility.php', 'quality.php'];

foreach ($files as $file) {
    $path = __DIR__ . '/../' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $count = 0;
        foreach ($replacements as $search => $replace) {
            $content = str_replace($search, $replace, $content, $c);
            $count += $c;
        }
        file_put_contents($path, $content);
        echo "Updated $file with $count replacements.\n";
    }
}

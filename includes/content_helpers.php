<?php
/*
 * Helper ringan untuk gambar konten.
 * File path relatif dari root project, misalnya uploads/products/nama.jpg.
 */
function contentImageUrl($path, $folder = "") {
    $path = trim((string) $path);
    if ($path === "") {
        return "assets/img/placeholder.svg";
    }
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        return $path;
    }
    $path = ltrim(str_replace("\\", "/", $path), "/");
    if (strpos($path, "uploads/") === 0) {
        return safe_image_url($path);
    }
    if (strpos($path, "../uploads/") === 0) {
        return safe_image_url(substr($path, 3));
    }
    $resolved = $folder !== "" ? rtrim($folder, "/") . "/" . basename($path) : $path;
    return safe_image_url($resolved);
}
?>

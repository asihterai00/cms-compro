<?php
/*
 * Helper ringan untuk gambar konten.
 * File path relatif dari root project, misalnya uploads/products/nama.jpg.
 */
function contentImageUrl($path, $folder = "") {
    $path = trim((string) $path);
    if ($path === "") {
        return "";
    }
    if (filter_var($path, FILTER_VALIDATE_URL)) {
        return $path;
    }
    $path = ltrim(str_replace("\\", "/", $path), "/");
    if (strpos($path, "uploads/") === 0) {
        return $path;
    }
    if (strpos($path, "../uploads/") === 0) {
        return substr($path, 3);
    }
    return $folder !== "" ? rtrim($folder, "/") . "/" . basename($path) : $path;
}
?>

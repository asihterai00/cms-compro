<?php

$host = "192.168.1.6";
$user = "root";
$password = "Emina.1525";
$database = "db_cms_compro";

$conn = new mysqli(
    $host,
    $user,
    $password,
    $database
);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
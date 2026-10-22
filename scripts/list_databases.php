<?php
$conn = @new mysqli('127.0.0.1', 'root', 'Emina.1525', '', 3306);
if ($conn->connect_error) {
    die("Connect error: " . $conn->connect_error . "\n");
}
$res = $conn->query("SHOW DATABASES");
echo "Databases found:\n";
while ($row = $res->fetch_row()) {
    echo " - " . $row[0] . "\n";
}

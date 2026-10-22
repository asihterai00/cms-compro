<?php
$conn = @new mysqli('192.168.1.6', 'root', 'Emina.1525', 'db_cms_compro', 3306);
if ($conn->connect_error) {
    echo "FAILED: " . $conn->connect_error . "\n";
} else {
    echo "SUCCESS: Connected to db_cms_compro on 192.168.1.6!\n";
    $res = $conn->query("SHOW TABLES");
    while ($r = $res->fetch_row()) {
        echo " - " . $r[0] . "\n";
    }
}

<?php
session_start();
$_SESSION['admin_id'] = 1;
$_SESSION['admin_username'] = 'Admin';
$_SESSION['admin_role'] = 'superadmin';
$_SESSION['admin_full_name'] = 'Administrator';
include __DIR__ . '/dashboard.php';

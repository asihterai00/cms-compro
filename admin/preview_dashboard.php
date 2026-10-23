<?php
/**
 * Admin Preview Dashboard Route
 * PT Nihon Plast Indonesia
 */
require_once __DIR__ . '/auth.php';
require_permission('dashboard', 'read');
header("Location: dashboard.php");
exit;

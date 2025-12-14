<?php
/**
 * ĐĂNG XUẤT
 * Xóa session và redirect về trang login
 */
session_start();

// Detect project folder name for XAMPP
$script_name = $_SERVER['SCRIPT_NAME'];
$project_folder = dirname($script_name);
if ($project_folder === '/' || $project_folder === '\\') {
    $project_folder = '';
}

// Xóa toàn bộ session
session_unset();
session_destroy();

// Redirect về trang instructor login
header('Location: ' . $project_folder . '/instructor/login');
exit;
?>

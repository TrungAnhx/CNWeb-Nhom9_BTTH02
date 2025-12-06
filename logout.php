<?php
/**
 * ĐĂNG XUẤT
 * Xóa session và redirect về trang login
 */
session_start();

// Xóa toàn bộ session
session_unset();
session_destroy();

// Redirect về trang instructor login
header('Location: ?controller=auth&action=instructorLogin');
exit;
?>

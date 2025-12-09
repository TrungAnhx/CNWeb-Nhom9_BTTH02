<?php
require_once 'config/Database.php';

echo "<h1>Công cụ Reset Mật khẩu Admin</h1>";

try {
    $database = new Database();
    $db = $database->getConnection();

    $passwordRaw = "123456";
    $passwordHash = password_hash($passwordRaw, PASSWORD_BCRYPT);
    $email = "admin@example.com";

    // Cập nhật mật khẩu
    $query = "UPDATE users SET password = :password WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":password", $passwordHash);
    $stmt->bindParam(":email", $email);

    if ($stmt->execute()) {
        echo "<p style='color: green; font-weight: bold;'>Thành công! Mật khẩu cho tài khoản '$email' đã được đổi thành: $passwordRaw</p>";
        echo "<a href='index.php?controller=auth&action=login'>Quay lại trang Đăng nhập</a>";
    } else {
        echo "<p style='color: red;'>Lỗi truy vấn CSDL.</p>";
    }

} catch (Exception $e) {
    echo "<p style='color: red;'>Lỗi: " . $e->getMessage() . "</p>";
    echo "<p>Hãy kiểm tra file config/Database.php xem username/password kết nối MySQL đúng chưa.</p>";
}
?>

<?php
// Kiểm tra xem session đã được start chưa, nếu chưa thì start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Course - Học trực tuyến</title>
    <!-- Link CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome (Icon) nếu cần -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header>
    <div class="container">
        <nav>
            <!-- Logo -->
            <div class="logo">
                <a href="index.php?controller=home&action=index">
                    <i class="fas fa-graduation-cap"></i> OnlineCourse
                </a>
            </div>

            <!-- Menu Chính -->
            <div class="nav-links">
                <a href="index.php?controller=course&action=index">Khóa học</a>
                
                <!-- Logic hiển thị theo trạng thái đăng nhập -->
                <?php if (isset($_SESSION['user'])): ?>
                    <!-- ĐÃ ĐĂNG NHẬP -->
                    <span style="color: #a435f0; font-weight: bold;">
                        Xin chào, <?php echo htmlspecialchars($_SESSION['user']['fullname']); ?>
                    </span>

                    <!-- Link Dashboard tuỳ theo Role -->
                    <?php if ($_SESSION['user']['role'] == 2): // Admin ?>
                        <a href="index.php?controller=admin&action=dashboard">Quản trị</a>
                    <?php elseif ($_SESSION['user']['role'] == 1): // Giảng viên ?>
                        <a href="index.php?controller=instructor&action=dashboard">Kênh giảng viên</a>
                    <?php else: // Học viên ?>
                        <a href="index.php?controller=student&action=dashboard">Học tập</a>
                    <?php endif; ?>

                    <a href="index.php?controller=auth&action=logout" class="btn btn-login" style="border: none; color: red;">Đăng xuất</a>

                <?php else: ?>
                    <!-- CHƯA ĐĂNG NHẬP -->
                    <a href="index.php?controller=auth&action=login" class="btn btn-login">Đăng nhập</a>
                    <a href="index.php?controller=auth&action=register" class="btn btn-signup">Đăng ký</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>

<!-- Bắt đầu phần nội dung chính (Main Content) -->
<main>
    <!-- Bỏ class container ở đây để trang con tự quyết định dùng container hay full-width -->
    <div>

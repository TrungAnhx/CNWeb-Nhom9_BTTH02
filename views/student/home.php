<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Learning Platform</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav>
                <a href="index.php" class="logo">📚 EduLearn</a>
                <div class="nav-links">
                    <a href="index.php?controller=course&action=index">Khóa học</a>
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="index.php?controller=enrollment&action=mycourses">Khóa học của tôi</a>
                        <span>👋 <?php echo htmlspecialchars($_SESSION['user']['fullname'] ?? $_SESSION['user']['username']); ?></span>
                        <a href="index.php?controller=auth&action=logout" class="btn-login">Đăng xuất</a>
                    <?php else: ?>
                        <a href="index.php?controller=auth&action=login" class="btn-login">Đăng nhập</a>
                        <a href="index.php?controller=auth&action=register" class="btn-signup">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="container">
            <!-- Hero Section -->
            <div style="margin-bottom: 48px; text-align: center;">
                <h1>Khóa học trực tuyến chất lượng cao</h1>
                <p style="font-size: 16px; color: #6a6f73; margin-top: 12px;">
                    Học từ các giảng viên hàng đầu, phát triển kỹ năng của bạn, tiến xa trong sự nghiệp
                </p>
            </div>

            <?php if (empty($courses)): ?>
                <!-- Empty State -->
                <div class="empty">
                    <div class="empty-icon">📚</div>
                    <div class="empty-title">Chưa có khóa học nào</div>
                    <div class="empty-text">Vui lòng quay lại sau để xem các khóa học mới</div>
                </div>
            <?php else: ?>
                <!-- Courses Grid -->
                <div class="course-grid">
                    <?php foreach ($courses as $course): ?>
                        <div class="course-card">
                            <!-- Course Image -->
                            <?php $imgSrc = !empty($course['image']) ? 'uploads/courses/' . htmlspecialchars($course['image']) : 'https://via.placeholder.com/240x135?text=Course'; ?>
                            <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
                            
                            <!-- Course Info -->
                            <div class="course-info">
                                <div class="course-title"><?php echo htmlspecialchars($course['title']); ?></div>
                                <div class="course-instructor"><?php echo htmlspecialchars($course['instructor_name'] ?? 'N/A'); ?></div>
                                <div class="course-price"><?php echo number_format($course['price'] ?? 0, 0, ',', '.'); ?>đ</div>
                                <a href="index.php?controller=course&action=detail&id=<?php echo $course['id']; ?>" class="btn-detail">Xem chi tiết</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Online Learning Platform. All rights reserved.</p>
            <div style="margin-top: 12px;">
                <a href="#">About Us</a>
                <a href="#">Contact</a>
                <a href="#">Privacy Policy</a>
            </div>
        </div>
    </footer>
</body>
</html>

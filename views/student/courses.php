<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khóa học</title>
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

    <main>
        <div class="container">
            <h1>Danh sách khóa học</h1>

            <!-- Search Form -->
            <form method="get" action="index.php" class="search-form">
                <input type="hidden" name="controller" value="course">
                <input type="hidden" name="action" value="index">
                <input type="text" name="q" placeholder="Tìm kiếm khóa học theo tên..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                <button type="submit">🔍 Tìm kiếm</button>
            </form>

            <?php
            require_once 'config/Database.php';

            $db = (new Database())->getConnection();
            $q = trim($_GET['q'] ?? '');
            $params = [];
            $sql = "SELECT c.*, u.fullname as instructor_name FROM courses c LEFT JOIN users u ON c.instructor_id = u.id WHERE c.status = 'approved'";
            if ($q !== '') {
                $sql .= " AND (c.title LIKE ? OR c.description LIKE ?)";
                $params[] = "%$q%";
                $params[] = "%$q%";
            }
            $sql .= " ORDER BY c.created_at DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if (empty($courses)): ?>
                <!-- Empty State -->
                <div class="empty">
                    <div class="empty-icon">🔍</div>
                    <div class="empty-title">Không tìm thấy khóa học</div>
                    <div class="empty-text">
                        <?php if ($q !== ''): ?>
                            Không có khóa học nào khớp với tìm kiếm của bạn. <a href="index.php?controller=course&action=index" style="color: var(--primary);">Xem tất cả</a>
                        <?php else: ?>
                            Hiện chưa có khóa học nào khả dụng.
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Courses Grid -->
                <div class="course-grid">
                    <?php foreach ($courses as $course): ?>
                        <div class="course-card">
                            <!-- Course Image -->
                            <?php $img = !empty($course['image']) ? 'uploads/courses/' . $course['image'] : 'https://via.placeholder.com/240x135?text=Course'; ?>
                            <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
                            
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

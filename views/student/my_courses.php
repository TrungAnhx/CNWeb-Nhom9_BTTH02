<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khóa học của tôi</title>
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
            <h1>Khóa học của tôi</h1>

            <?php if (empty($enrollments)): ?>
                <!-- Empty State -->
                <div class="empty">
                    <div class="empty-icon">📚</div>
                    <div class="empty-title">Chưa đăng ký khóa học nào</div>
                    <div class="empty-text">
                        Bạn chưa đăng ký bất kỳ khóa học nào. <a href="index.php?controller=course&action=index" style="color: var(--primary);">Khám phá các khóa học</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Courses Table -->
                <table class="courses-table">
                    <thead>
                        <tr>
                            <th>Khóa học</th>
                            <th>Giảng viên</th>
                            <th>Giá</th>
                            <th>Tiến độ</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($enrollments as $en): ?>
                            <tr>
                                <td>
                                    <a href="index.php?controller=course&action=detail&id=<?php echo $en['course_id']; ?>" 
                                       style="color: var(--primary); font-weight: 600; text-decoration: none;">
                                        <?php echo htmlspecialchars($en['title']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($en['instructor_name']); ?></td>
                                <td><?php echo number_format($en['price'] ?? 0, 0, ',', '.'); ?>đ</td>
                                <td>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo intval($en['progress'] ?? 0); ?>%;"></div>
                                    </div>
                                    <span style="font-size: 12px; color: #6a6f73;"><?php echo intval($en['progress'] ?? 0); ?>%</span>
                                </td>
                                <td>
                                    <form method="post" action="index.php?controller=enrollment&action=cancel" 
                                          onsubmit="return confirm('Bạn có chắc muốn hủy đăng ký khóa học này?');" style="display: inline;">
                                        <input type="hidden" name="course_id" value="<?php echo $en['course_id']; ?>">
                                        <button type="submit" class="btn btn-danger">Hủy</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
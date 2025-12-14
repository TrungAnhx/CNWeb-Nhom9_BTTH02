<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết khóa học</title>
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
            <?php
            require_once 'config/Database.php';
            require_once 'models/Enrollment.php';

            $db = (new Database())->getConnection();
            $id = intval($_GET['id'] ?? 0);
            if ($id <= 0) {
                echo '<div class="empty"><div class="empty-title">Khóa học không hợp lệ</div></div>';
                require 'views/layouts/footer.php';
                exit;
            }

            $stmt = $db->prepare("SELECT c.*, u.fullname as instructor_name FROM courses c LEFT JOIN users u ON c.instructor_id = u.id WHERE c.id = ? LIMIT 1");
            $stmt->execute([$id]);
            $course = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$course) {
                echo '<div class="empty"><div class="empty-title">Không tìm thấy khóa học</div></div>';
                require 'views/layouts/footer.php';
                exit;
            }

            $enrollModel = new Enrollment();
            $isEnrolled = false;
            if (isset($_SESSION['user'])) {
                $isEnrolled = (bool)$enrollModel->isEnrolled($id, $_SESSION['user']['id']);
            }
            ?>

            <!-- Course Detail -->
            <div class="course-detail">
                <!-- Main Content -->
                <div>
                    <h1 class="course-detail-title"><?php echo htmlspecialchars($course['title']); ?></h1>
                    
                    <!-- Course Image -->
                    <?php $img = !empty($course['image']) ? 'uploads/courses/' . $course['image'] : 'https://via.placeholder.com/800x450?text=No+Image'; ?>
                    <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="course-detail-image">
                    
                    <!-- Description -->
                    <div style="margin-top: 32px;">
                        <h2>Mô tả khóa học</h2>
                        <div class="course-description">
                            <?php echo nl2br(htmlspecialchars($course['description'])); ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="course-sidebar">
                    <div style="margin-bottom: 24px;">
                        <div style="font-size: 28px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">
                            <?php echo number_format($course['price'] ?? 0, 0, ',', '.'); ?>đ
                        </div>
                    </div>

                    <!-- Course Info -->
                    <div class="course-detail-info">
                        <p><strong>👨‍🏫 Giảng viên:</strong><br><?php echo htmlspecialchars($course['instructor_name'] ?? 'N/A'); ?></p>
                        <p><strong>📊 Cấp độ:</strong><br><?php echo htmlspecialchars($course['level'] ?? 'N/A'); ?></p>
                        <p><strong>📚 Danh mục:</strong><br><?php echo htmlspecialchars($course['category_id'] ?? 'N/A'); ?></p>
                    </div>

                    <!-- Enrollment Action -->
                    <div style="margin-top: 24px;">
                        <?php if (!isset($_SESSION['user'])): ?>
                            <div style="background: #f0f0f0; padding: 12px; border-radius: 4px; text-align: center; font-size: 14px; color: #666; margin-bottom: 12px;">
                                Bạn cần đăng nhập để đăng ký
                            </div>
                            <a href="index.php?controller=auth&action=login" class="btn btn-primary" style="width: 100%; text-align: center; display: block;">Đăng nhập</a>
                        <?php else: ?>
                            <?php if ($isEnrolled): ?>
                                <form method="post" action="index.php?controller=enrollment&action=cancel" onsubmit="return confirm('Bạn có chắc muốn hủy đăng ký khóa học này?');">
                                    <input type="hidden" name="course_id" value="<?php echo $id; ?>">
                                    <button type="submit" class="btn btn-danger" style="width: 100%;">Hủy đăng ký</button>
                                </form>
                            <?php else: ?>
                                <form method="post" action="index.php?controller=enrollment&action=enroll">
                                    <input type="hidden" name="course_id" value="<?php echo $id; ?>">
                                    <button type="submit" class="btn btn-primary" style="width: 100%;">Đăng ký khóa học</button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
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
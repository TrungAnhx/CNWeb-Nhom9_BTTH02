<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Giảng viên - Khóa học của tôi</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/instructor.css">
</head>
<body>
    <!-- Header với nút đăng xuất -->
    <header class="instructor-header">
        <div class="header-container">
            <div class="header-left">
                <h1 class="logo">📚 Instructor Dashboard</h1>
            </div>
            <div class="header-right">
                <span class="user-info">
                    👤 <?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']); ?>
                </span>
                <a href="<?php echo BASE_URL; ?>/logout.php" class="btn-logout">Đăng xuất</a>
            </div>
        </div>
    </header>
    
    <div class="instructor-container">
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                ✅ <?php echo htmlspecialchars($_SESSION['success']); ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                ❌ <?php echo htmlspecialchars($_SESSION['error']); ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <!-- Stats Overview -->
        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total_courses'] ?? 0; ?></div>
                <div class="stat-label">Tổng khóa học</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['total_students'] ?? 0; ?></div>
                <div class="stat-label">Tổng học viên</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['approved_courses'] ?? 0; ?></div>
                <div class="stat-label">Đã duyệt</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending_courses'] ?? 0; ?></div>
                <div class="stat-label">Chờ duyệt</div>
            </div>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <h1>Khóa học của tôi</h1>
            <a href="<?php echo BASE_URL; ?>/?controller=course&action=create" class="btn-primary">
                <span class="icon">+</span> Tạo khóa học mới
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="toolbar">
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm khóa học..." id="searchInput">
            </div>
            <div class="filters">
                <select id="statusFilter" class="filter-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="draft">Nháp</option>
                    <option value="pending">Chờ duyệt</option>
                    <option value="approved">Đã duyệt</option>
                </select>
                <select id="sortBy" class="filter-select">
                    <option value="newest">Mới nhất</option>
                    <option value="oldest">Cũ nhất</option>
                    <option value="students">Nhiều học viên</option>
                </select>
            </div>
        </div>

        <?php if (empty($courses)): ?>
            <!-- Empty State - Chưa có khóa học nào -->
            <div class="empty-state">
                <div class="empty-icon">📚</div>
                <h2>Bạn chưa có khóa học nào</h2>
                <p>Bắt đầu tạo khóa học đầu tiên của bạn và chia sẻ kiến thức với mọi người!</p>
                <a href="?controller=course&action=create" class="btn-primary-large">
                    Tạo khóa học đầu tiên
                </a>
                <div class="empty-tips">
                    <h3>💡 Mẹo bắt đầu:</h3>
                    <ul>
                        <li>Chọn chủ đề bạn am hiểu nhất</li>
                        <li>Chuẩn bị nội dung và video bài giảng</li>
                        <li>Tạo các bài học có cấu trúc rõ ràng</li>
                        <li>Upload tài liệu hỗ trợ cho học viên</li>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <!-- Course Grid - Có khóa học -->
            <div class="courses-grid">
                <?php foreach ($courses as $course): ?>
                    <div class="course-card" data-status="<?php echo htmlspecialchars($course['status']); ?>">
                        <!-- Course Image -->
                        <div class="course-image">
                            <?php if (!empty($course['image'])): ?>
                                <img src="<?php echo BASE_URL; ?>/uploads/courses/<?php echo htmlspecialchars($course['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($course['title']); ?>">
                            <?php else: ?>
                                <div class="no-image">📖</div>
                            <?php endif; ?>
                            
                            <!-- Status Badge -->
                            <span class="status-badge status-<?php echo htmlspecialchars($course['status']); ?>">
                                <?php 
                                    $statusText = [
                                        'draft' => 'Nháp',
                                        'pending' => 'Chờ duyệt',
                                        'approved' => 'Đã duyệt'
                                    ];
                                    echo $statusText[$course['status']] ?? $course['status'];
                                ?>
                            </span>
                        </div>

                        <!-- Course Info -->
                        <div class="course-info">
                            <h3 class="course-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                            
                            <div class="course-meta">
                                <span class="meta-item">
                                    📂 <?php echo htmlspecialchars($course['category_name'] ?? 'Chưa phân loại'); ?>
                                </span>
                                <span class="meta-item">
                                    👥 <?php echo $course['student_count']; ?> học viên
                                </span>
                                <span class="meta-item">
                                    📚 <?php echo $course['lesson_count']; ?> bài học
                                </span>
                            </div>

                            <div class="course-details">
                                <span class="price">
                                    <?php echo $course['price'] > 0 ? number_format($course['price']) . ' VNĐ' : 'Miễn phí'; ?>
                                </span>
                                <span class="level"><?php echo htmlspecialchars($course['level'] ?? 'N/A'); ?></span>
                            </div>

                            <div class="course-date">
                                Tạo: <?php echo date('d/m/Y', strtotime($course['created_at'])); ?>
                            </div>
                        </div>

                        <!-- Course Actions -->
                        <div class="course-actions">
                            <a href="<?php echo BASE_URL; ?>/?controller=course&action=edit&id=<?php echo $course['id']; ?>" 
                               class="btn-action btn-edit" title="Chỉnh sửa">
                                ✏️ Sửa
                            </a>
                            <a href="<?php echo BASE_URL; ?>/?controller=lesson&action=manage&course_id=<?php echo $course['id']; ?>" 
                               class="btn-action btn-lessons" title="Quản lý bài học">
                                📚 Bài học
                            </a>
                            <a href="<?php echo BASE_URL; ?>/?controller=course&action=students&id=<?php echo $course['id']; ?>" 
                               class="btn-action btn-students" title="Xem học viên">
                                👥 Học viên
                            </a>
                            <button onclick="confirmDelete(<?php echo $course['id']; ?>, '<?php echo htmlspecialchars($course['title']); ?>')" 
                                    class="btn-action btn-delete" title="Xóa">
                                🗑️ Xóa
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Xác nhận xóa khóa học
        function confirmDelete(courseId, courseTitle) {
            if (confirm(`⚠️ Bạn có chắc muốn xóa khóa học "${courseTitle}"?\n\nThao tác này sẽ xóa toàn bộ bài học và dữ liệu học viên!`)) {
                window.location.href = `<?php echo BASE_URL; ?>/?controller=course&action=delete&id=${courseId}`;
            }
        }

        // Live search
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.course-card');
            
            cards.forEach(card => {
                const title = card.querySelector('.course-title').textContent.toLowerCase();
                card.style.display = title.includes(searchTerm) ? 'block' : 'none';
            });
        });

        // Filter by status
        document.getElementById('statusFilter')?.addEventListener('change', function(e) {
            const status = e.target.value;
            const cards = document.querySelectorAll('.course-card');
            
            cards.forEach(card => {
                if (status === '' || card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
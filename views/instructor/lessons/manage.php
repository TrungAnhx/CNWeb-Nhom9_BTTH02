<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bài học - <?php echo htmlspecialchars($course['title']); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/instructor.css">
</head>
<body>
    <!-- Header -->
    <header class="instructor-header">
        <div class="header-container">
            <div class="header-left">
                <h1 class="logo">📚 Quản lý bài học</h1>
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
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?php echo BASE_URL; ?>/?controller=course&action=dashboard">Dashboard</a>
            <span> / </span>
            <span><?php echo htmlspecialchars($course['title']); ?></span>
            <span> / </span>
            <span>Bài học</span>
        </div>

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

        <!-- Course Info Card -->
        <div class="course-info-card">
            <div class="course-info-header">
                <h2><?php echo htmlspecialchars($course['title']); ?></h2>
                <span class="course-badge"><?php echo htmlspecialchars($course['category_name'] ?? 'N/A'); ?></span>
            </div>
            <p class="course-meta">
                📚 <?php echo count($lessons); ?> bài học
            </p>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <h1>Danh sách bài học</h1>
            <a href="<?php echo BASE_URL; ?>/?controller=lesson&action=create&course_id=<?php echo $course['id']; ?>" class="btn-primary">
                <span class="icon">+</span> Thêm bài học mới
            </a>
        </div>

        <?php if (empty($lessons)): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">📝</div>
                <h2>Chưa có bài học nào</h2>
                <p>Bắt đầu thêm bài học đầu tiên cho khóa học của bạn!</p>
                <a href="<?php echo BASE_URL; ?>/?controller=lesson&action=create&course_id=<?php echo $course['id']; ?>" class="btn-primary-large">
                    Thêm bài học đầu tiên
                </a>
            </div>
        <?php else: ?>
            <!-- Lessons Table -->
            <div class="table-container">
                <table class="lessons-table">
                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th>Tiêu đề bài học</th>
                            <th width="120">Video</th>
                            <th width="100">Tài liệu</th>
                            <th width="200">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lessons as $index => $lesson): ?>
                            <tr>
                                <td class="text-center">
                                    <span class="lesson-order"><?php echo $lesson['order_num']; ?></span>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($lesson['title']); ?></strong>
                                    <?php if (!empty($lesson['content'])): ?>
                                        <br><small class="text-muted">
                                            <?php echo mb_substr(strip_tags($lesson['content']), 0, 80) . '...'; ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($lesson['video_url'])): ?>
                                        <span class="badge badge-success">✓ Có</span>
                                    <?php else: ?>
                                        <span class="badge badge-gray">−</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info"><?php echo $lesson['material_count']; ?> files</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo BASE_URL; ?>/?controller=lesson&action=edit&id=<?php echo $lesson['id']; ?>" 
                                           class="btn-action btn-edit" title="Sửa">
                                            ✏️
                                        </a>
                                        <button onclick="confirmDelete(<?php echo $lesson['id']; ?>, '<?php echo htmlspecialchars($lesson['title']); ?>')" 
                                                class="btn-action btn-delete" title="Xóa">
                                            🗑️
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function confirmDelete(lessonId, lessonTitle) {
            if (confirm(`⚠️ Bạn có chắc muốn xóa bài học "${lessonTitle}"?\n\nThao tác này sẽ xóa tất cả tài liệu liên quan!`)) {
                window.location.href = `<?php echo BASE_URL; ?>/?controller=lesson&action=delete&id=${lessonId}`;
            }
        }
    </script>

    <style>
        .course-info-card {
            background: white;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 24px;
        }

        .course-info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .course-info-header h2 {
            color: #2d2f31;
            font-size: 20px;
            margin: 0;
        }

        .course-badge {
            background: #5624d0;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
        }

        .course-meta {
            color: #6a6f73;
            font-size: 14px;
        }

        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .lessons-table {
            width: 100%;
            border-collapse: collapse;
        }

        .lessons-table thead {
            background: #f7f9fa;
        }

        .lessons-table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: #1c1d1f;
            font-size: 14px;
            border-bottom: 2px solid #e0e0e0;
        }

        .lessons-table td {
            padding: 16px;
            border-bottom: 1px solid #e0e0e0;
        }

        .lessons-table tbody tr:hover {
            background: #f7f9fa;
        }

        .lesson-order {
            background: #e8e8e8;
            color: #1c1d1f;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 600;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #6a6f73;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-gray {
            background: #e8e8e8;
            color: #6a6f73;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            background: white;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .btn-edit {
            border: 1px solid #5624d0;
        }

        .btn-edit:hover {
            background: #5624d0;
        }

        .btn-delete {
            border: 1px solid #dc3545;
        }

        .btn-delete:hover {
            background: #dc3545;
        }
    </style>
</body>
</html>
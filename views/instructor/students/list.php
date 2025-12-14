<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách học viên - <?php echo htmlspecialchars($course['title']); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/instructor.css">
</head>
<body>
    <!-- Header -->
    <header class="instructor-header">
        <div class="header-container">
            <div class="header-left">
                <h1 class="logo">👥 Danh sách học viên</h1>
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
            <a href="<?php echo BASE_URL; ?>/?controller=lesson&action=manage&course_id=<?php echo $course['id']; ?>">
                <?php echo htmlspecialchars($course['title']); ?>
            </a>
            <span> / </span>
            <span>Học viên</span>
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
        
        <?php if (isset($_SESSION['info'])): ?>
            <div class="alert alert-info">
                ℹ️ <?php echo htmlspecialchars($_SESSION['info']); ?>
            </div>
            <?php unset($_SESSION['info']); ?>
        <?php endif; ?>

        <!-- Course Info Card -->
        <div class="course-info-card">
            <div class="course-info-header">
                <h2><?php echo htmlspecialchars($course['title']); ?></h2>
                <span class="course-badge"><?php echo htmlspecialchars($course['category_name'] ?? 'N/A'); ?></span>
            </div>
            <p class="course-meta">
                👥 <?php echo $totalStudents; ?> học viên đã đăng ký
            </p>
        </div>

        <!-- Page Header with Search -->
        <div class="page-header">
            <h1>Danh sách học viên</h1>
            <div class="search-box">
                <form method="GET" action="">
                    <input type="hidden" name="controller" value="enrollment">
                    <input type="hidden" name="action" value="listStudents">
                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                    <input type="text" name="search" placeholder="Tìm theo tên, email..." 
                           value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
                    <button type="submit" class="btn-search">🔍 Tìm kiếm</button>
                    <?php if (!empty($keyword)): ?>
                        <a href="<?php echo BASE_URL; ?>/?controller=enrollment&action=listStudents&course_id=<?php echo $course['id']; ?>" 
                           class="btn-clear">✕ Xóa</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php if (empty($students)): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <?php if (!empty($keyword)): ?>
                    <h2>Không tìm thấy học viên</h2>
                    <p>Không có học viên nào phù hợp với từ khóa "<?php echo htmlspecialchars($keyword); ?>"</p>
                <?php else: ?>
                    <h2>Chưa có học viên nào đăng ký</h2>
                    <p>Khi có học viên đăng ký khóa học, họ sẽ xuất hiện ở đây.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Students Table -->
            <div class="table-container">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Học viên</th>
                            <th>Email</th>
                            <th width="140">Ngày đăng ký</th>
                            <th width="200">Tiến độ</th>
                            <th width="120">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $index => $student): ?>
                            <tr>
                                <td class="text-center"><?php echo $index + 1; ?></td>
                                <td>
                                    <div class="student-info">
                                        <div class="student-avatar">
                                            <?php if (!empty($student['avatar'])): ?>
                                                <img src="<?php echo BASE_URL . '/' . htmlspecialchars($student['avatar']); ?>" 
                                                     alt="Avatar">
                                            <?php else: ?>
                                                <span class="avatar-placeholder">
                                                    <?php echo strtoupper(substr($student['fullname'] ?? $student['username'], 0, 1)); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <strong><?php echo htmlspecialchars($student['fullname'] ?? $student['username']); ?></strong>
                                            <br>
                                            <small class="text-muted">@<?php echo htmlspecialchars($student['username']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td class="text-center">
                                    <?php echo date('d/m/Y', strtotime($student['enrolled_date'])); ?>
                                </td>
                                <td>
                                    <div class="progress-container">
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: <?php echo $student['progress']; ?>%"></div>
                                        </div>
                                        <span class="progress-text"><?php echo $student['progress']; ?>%</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    $statusClass = '';
                                    $statusText = '';
                                    switch($student['status']) {
                                        case 'active':
                                            $statusClass = 'badge-success';
                                            $statusText = 'Đang học';
                                            break;
                                        case 'completed':
                                            $statusClass = 'badge-info';
                                            $statusText = 'Hoàn thành';
                                            break;
                                        case 'dropped':
                                            $statusClass = 'badge-danger';
                                            $statusText = 'Đã bỏ học';
                                            break;
                                        default:
                                            $statusClass = 'badge-gray';
                                            $statusText = ucfirst($student['status']);
                                    }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Summary Stats -->
            <div class="stats-summary">
                <div class="stat-card">
                    <span class="stat-label">Tổng học viên</span>
                    <span class="stat-value"><?php echo count($students); ?></span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Tiến độ trung bình</span>
                    <span class="stat-value">
                        <?php 
                        $avgProgress = count($students) > 0 
                            ? round(array_sum(array_column($students, 'progress')) / count($students)) 
                            : 0;
                        echo $avgProgress . '%';
                        ?>
                    </span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Đang học</span>
                    <span class="stat-value">
                        <?php echo count(array_filter($students, fn($s) => $s['status'] === 'active')); ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <style>
        .search-box {
            display: flex;
            gap: 8px;
        }

        .search-box form {
            display: flex;
            gap: 8px;
        }

        .search-box input[type="text"] {
            padding: 10px 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            width: 300px;
        }

        .btn-search {
            background: #5624d0;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-search:hover {
            background: #3d1a9f;
        }

        .btn-clear {
            background: #6c757d;
            color: white;
            padding: 10px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-clear:hover {
            background: #5a6268;
        }

        .students-table {
            width: 100%;
            border-collapse: collapse;
        }

        .students-table thead {
            background: #f7f9fa;
        }

        .students-table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: #1c1d1f;
            font-size: 14px;
            border-bottom: 2px solid #e0e0e0;
        }

        .students-table td {
            padding: 16px;
            border-bottom: 1px solid #e0e0e0;
        }

        .students-table tbody tr:hover {
            background: #f7f9fa;
        }

        .student-info {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
        }

        .student-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #5624d0;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 18px;
        }

        .progress-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .progress-bar {
            flex: 1;
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #5624d0, #7c4dff);
            transition: width 0.3s;
        }

        .progress-text {
            font-weight: 600;
            color: #1c1d1f;
            min-width: 45px;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .stats-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 24px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-label {
            display: block;
            color: #6a6f73;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .stat-value {
            display: block;
            color: #1c1d1f;
            font-size: 28px;
            font-weight: 700;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #0c5460;
        }
    </style>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

<div id="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-graduation-cap"></i> ADMIN PANEL
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="index.php?controller=admin&action=dashboard" class="active">
                    <i class="fa-solid fa-gauge-high"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="index.php?controller=admin&action=users">
                    <i class="fa-solid fa-users"></i> Quản lý Người dùng
                </a>
            </li>
            <li>
                <a href="index.php?controller=admin&action=categories">
                    <i class="fa-solid fa-layer-group"></i> Danh mục Khóa học
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa-solid fa-book-open"></i> Phê duyệt Khóa học
                </a>
            </li>
            <li>
                <a href="index.php?controller=auth&action=logout" class="text-danger">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content-wrapper">
        <div id="header">
            <h3>Tổng quan hệ thống</h3>
            <div class="user-info">
                Xin chào, <strong><?php echo $_SESSION['fullname'] ?? 'Admin'; ?></strong>
            </div>
        </div>

        <div id="main-content">
            <div class="row">
                <!-- Card 1 -->
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4><?php echo $stats['users'] ?? 0; ?></h4>
                            <p>Thành viên</p>
                        </div>
                        <div class="stat-icon text-purple">
                            <i class="fa-solid fa-user-group"></i>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-md-4">
                    <div class="stat-card border-start border-4 border-success">
                        <div class="stat-info">
                            <h4><?php echo $stats['courses'] ?? 0; ?></h4>
                            <p>Khóa học</p>
                        </div>
                        <div class="stat-icon text-success">
                            <i class="fa-solid fa-video"></i>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-md-4">
                    <div class="stat-card border-start border-4 border-warning">
                        <div class="stat-info">
                            <h4><?php echo number_format($stats['revenue'] ?? 0); ?>đ</h4>
                            <p>Doanh thu</p>
                        </div>
                        <div class="stat-icon text-warning">
                            <i class="fa-solid fa-sack-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Khu vực nội dung chính sẽ thêm sau -->
            <div class="mt-4 p-4 bg-white rounded shadow-sm">
                <h5>Chào mừng trở lại trang quản trị!</h5>
                <p class="text-muted">Chọn các chức năng bên menu trái để bắt đầu quản lý hệ thống.</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>

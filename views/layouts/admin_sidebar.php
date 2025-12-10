<style>
    .admin-layout { display: flex; min-height: 80vh; }
    .sidebar { width: 250px; background: #2d2f31; color: #fff; padding: 20px; }
    .sidebar h3 { color: #a435f0; margin-bottom: 20px; text-align: center; }
    .sidebar ul { list-style: none; padding: 0; }
    .sidebar ul li { margin-bottom: 10px; }
    .sidebar ul li a { color: #ccc; display: block; padding: 10px; text-decoration: none; transition: 0.3s; }
    .sidebar ul li a:hover, .sidebar ul li a.active { background: #3e4143; color: #fff; border-left: 3px solid #a435f0; }
    .admin-content { flex: 1; padding: 30px; background: #f7f9fa; }
</style>

<aside class="sidebar">
    <h3>ADMIN PANEL</h3>
    <ul>
        <li><a href="index.php?controller=admin&action=dashboard">📊 Tổng quan</a></li>
        <li><a href="index.php?controller=admin&action=categories">📂 Quản lý Danh mục</a></li>
        <li><a href="index.php?controller=admin&action=users">👥 Quản lý Người dùng</a></li>
        <li><a href="index.php?controller=admin&action=courses">🎓 Duyệt Khóa học</a></li>
        <li><a href="index.php?controller=home&action=index">🏠 Về Trang chủ</a></li>
        <li><a href="index.php?controller=auth&action=logout" style="color: #ff6b6b;">🚪 Đăng xuất</a></li>
    </ul>
</aside>

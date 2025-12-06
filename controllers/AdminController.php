<?php
require_once 'config/Database.php';

class AdminController {
    private $db;

    public function __construct() {
        // Kết nối Database
        $database = new Database();
        $this->db = $database->getConnection();

        // Kiểm tra quyền Admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 2) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }

    public function dashboard() {
        // 1. Đếm tổng thành viên
        $queryUser = "SELECT COUNT(*) as total FROM users";
        $stmtUser = $this->db->prepare($queryUser);
        $stmtUser->execute();
        $totalUsers = $stmtUser->fetch(PDO::FETCH_ASSOC)['total'];

        // 2. Đếm tổng khóa học
        $queryCourse = "SELECT COUNT(*) as total FROM courses";
        $stmtCourse = $this->db->prepare($queryCourse);
        $stmtCourse->execute();
        $totalCourses = $stmtCourse->fetch(PDO::FETCH_ASSOC)['total'];

        // 3. Tính tổng doanh thu (Join bảng Enrollments và Courses)
        // (Lấy giá tiền của khóa học tương ứng với mỗi lượt đăng ký)
        $queryRevenue = "SELECT SUM(c.price) as total_revenue 
                         FROM enrollments e
                         JOIN courses c ON e.course_id = c.id";
        $stmtRevenue = $this->db->prepare($queryRevenue);
        $stmtRevenue->execute();
        $rowRevenue = $stmtRevenue->fetch(PDO::FETCH_ASSOC);
        $totalRevenue = $rowRevenue['total_revenue'] ? $rowRevenue['total_revenue'] : 0;

        // Đóng gói dữ liệu để gửi sang View
        $stats = [
            'users' => $totalUsers,
            'courses' => $totalCourses,
            'revenue' => $totalRevenue
        ];
        
        require 'views/admin/dashboard.php';
    }
    
    // Placeholder cho các action khác
    public function users() {
        echo "Trang quản lý user (Đang phát triển)";
    }

    public function categories() {
        echo "Trang quản lý danh mục (Đang phát triển)";
    }
}
?>
<?php
require_once 'config/Database.php';

class Course {
    private $conn;
    private $table_name = "courses";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy danh sách khóa học đã được duyệt để hiện trang chủ
    public function getAllApproved() {
        // Query có JOIN để lấy thêm tên giảng viên (fullname) từ bảng users
        $query = "SELECT 
                    c.id, c.title, c.price, c.image, c.level, c.duration_weeks,
                    u.fullname as instructor_name 
                  FROM " . $this->table_name . " c
                  LEFT JOIN users u ON c.instructor_id = u.id
                  WHERE c.status = 'approved'
                  ORDER BY c.created_at DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo "Lỗi truy vấn: " . $e->getMessage();
            return [];
        }
    }
}
?>

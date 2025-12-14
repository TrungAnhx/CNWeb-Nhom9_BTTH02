<?php
require_once 'config/Database.php';

class Course {
    private $conn;
    private $table_name = "courses";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy danh sách khóa học đã được duyệt (Cho trang chủ)
    public function getAllApproved() {
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

    // --- CÁC HÀM CHO ADMIN (Duyệt bài) ---

    // 1. Lấy danh sách khóa học CHỜ DUYỆT (Pending)
    public function getPendingCourses() {
        $query = "SELECT 
                    c.*, 
                    u.fullname as instructor_name,
                    cat.name as category_name
                  FROM " . $this->table_name . " c
                  LEFT JOIN users u ON c.instructor_id = u.id
                  LEFT JOIN categories cat ON c.category_id = cat.id
                  WHERE c.status = 'pending'
                  ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Duyệt khóa học (Chuyển thành approved)
    public function approve($id) {
        $query = "UPDATE " . $this->table_name . " SET status = 'approved' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // 3. Xóa khóa học (Từ chối)
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
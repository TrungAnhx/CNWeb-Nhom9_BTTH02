<?php
require_once 'config/Database.php';

class Course {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Lấy tất cả khóa học của 1 giảng viên
     * @param int $instructorId - ID của giảng viên
     * @return array - Danh sách khóa học
     */
    public function getCoursesByInstructor($instructorId) {
        $sql = "SELECT c.*, 
                       cat.name as category_name,
                       (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as student_count,
                       (SELECT COUNT(*) FROM lessons WHERE course_id = c.id) as lesson_count
                FROM courses c
                LEFT JOIN categories cat ON c.category_id = cat.id
                WHERE c.instructor_id = ?
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$instructorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy chi tiết 1 khóa học theo ID
     * @param int $id - ID khóa học
     * @return array|false
     */
    public function getCourseById($id) {
        $sql = "SELECT c.*, 
                       cat.name as category_name,
                       u.fullname as instructor_name
                FROM courses c
                LEFT JOIN categories cat ON c.category_id = cat.id
                LEFT JOIN users u ON c.instructor_id = u.id
                WHERE c.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Tạo khóa học mới
     * @param array $data - Dữ liệu khóa học
     * @return int - ID khóa học vừa tạo
     */
    public function createCourse($data) {
        $sql = "INSERT INTO courses (title, description, instructor_id, category_id, price, 
                                    duration_weeks, level, image, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['title'],
            $data['description'],
            $data['instructor_id'],
            $data['category_id'],
            $data['price'],
            $data['duration_weeks'],
            $data['level'],
            $data['image'],
            $data['status'] ?? 'draft'
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Cập nhật khóa học
     * @param int $id - ID khóa học
     * @param array $data - Dữ liệu cập nhật
     * @return bool
     */
    public function updateCourse($id, $data) {
        $sql = "UPDATE courses 
                SET title = ?, description = ?, category_id = ?, price = ?, 
                    duration_weeks = ?, level = ?, image = ?, updated_at = NOW()
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['category_id'],
            $data['price'],
            $data['duration_weeks'],
            $data['level'],
            $data['image'],
            $id
        ]);
    }
    
    /**
     * Xóa khóa học (chỉ nếu là của giảng viên này)
     * @param int $id - ID khóa học
     * @param int $instructorId - ID giảng viên
     * @return bool
     */
    public function deleteCourse($id, $instructorId) {
        $sql = "DELETE FROM courses WHERE id = ? AND instructor_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id, $instructorId]);
    }
    
    /**
     * Lấy thống kê của giảng viên
     * @param int $instructorId
     * @return array
     */
    public function getInstructorStatistics($instructorId) {
        $sql = "SELECT 
                    COUNT(*) as total_courses,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_courses,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_courses,
                    SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft_courses,
                    (SELECT COUNT(DISTINCT student_id) 
                     FROM enrollments e 
                     JOIN courses c ON e.course_id = c.id 
                     WHERE c.instructor_id = ?) as total_students
                FROM courses 
                WHERE instructor_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$instructorId, $instructorId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

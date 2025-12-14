<?php
class Enrollment {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    /**
     * Lấy danh sách học viên đã đăng ký 1 khóa học
     */
    public function getEnrollmentsByCourse($courseId) {
        $sql = "SELECT 
                    e.id as enrollment_id,
                    e.enrolled_date,
                    e.status,
                    e.progress,
                    u.id as student_id,
                    u.username,
                    u.email,
                    u.fullname,
                    u.avatar
                FROM enrollments e
                JOIN users u ON e.student_id = u.id
                WHERE e.course_id = :course_id
                ORDER BY e.enrolled_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Đếm số học viên của 1 khóa học
     */
    public function countStudentsByCourse($courseId) {
        $sql = "SELECT COUNT(*) as total FROM enrollments WHERE course_id = :course_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    /**
     * Lấy tiến độ học chi tiết của 1 học viên trong 1 khóa học
     * (Giả định: progress đã được tính sẵn, hoặc có thể tính từ lesson_progress nếu có bảng đó)
     */
    public function getStudentProgress($enrollmentId) {
        $sql = "SELECT 
                    e.*,
                    u.fullname,
                    u.email,
                    c.title as course_title,
                    (SELECT COUNT(*) FROM lessons WHERE course_id = e.course_id) as total_lessons
                FROM enrollments e
                JOIN users u ON e.student_id = u.id
                JOIN courses c ON e.course_id = c.id
                WHERE e.id = :enrollment_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':enrollment_id', $enrollmentId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Tìm kiếm học viên theo tên hoặc email trong 1 khóa học
     */
    public function searchStudentsInCourse($courseId, $keyword) {
        $sql = "SELECT 
                    e.id as enrollment_id,
                    e.enrolled_date,
                    e.status,
                    e.progress,
                    u.id as student_id,
                    u.username,
                    u.email,
                    u.fullname
                FROM enrollments e
                JOIN users u ON e.student_id = u.id
                WHERE e.course_id = :course_id
                AND (u.fullname LIKE :keyword OR u.email LIKE :keyword OR u.username LIKE :keyword)
                ORDER BY e.enrolled_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':course_id', $courseId);
        $searchTerm = '%' . $keyword . '%';
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy tổng thống kê enrollments của giảng viên
     */
    public function getInstructorStats($instructorId) {
        $sql = "SELECT 
                    COUNT(DISTINCT e.student_id) as total_students,
                    COUNT(DISTINCT e.course_id) as total_courses,
                    AVG(e.progress) as avg_progress
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                WHERE c.instructor_id = :instructor_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':instructor_id', $instructorId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
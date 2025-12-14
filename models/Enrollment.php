<?php
require_once 'config/Database.php';

class Enrollment {
    private $conn;
    private $table = 'enrollments';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function isEnrolled($courseId, $studentId) {
        $sql = "SELECT * FROM {$this->table} WHERE course_id = ? AND student_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$courseId, $studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function enroll($courseId, $studentId) {
        if ($this->isEnrolled($courseId, $studentId)) {
            return false; // Already enrolled
        }

        $sql = "INSERT INTO {$this->table} (course_id, student_id, status, progress, enrolled_date) VALUES (?, ?, 'active', 0, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$courseId, $studentId]);
    }

    public function cancel($courseId, $studentId) {
        $sql = "DELETE FROM {$this->table} WHERE course_id = ? AND student_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$courseId, $studentId]);
    }

    public function getByStudent($studentId) {
        $sql = "SELECT e.*, c.title, c.image, c.price, c.level, c.id as course_id, u.fullname as instructor_name
                FROM {$this->table} e
                JOIN courses c ON e.course_id = c.id
                LEFT JOIN users u ON c.instructor_id = u.id
                WHERE e.student_id = ?
                ORDER BY e.enrolled_date DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCourseAndStudent($courseId, $studentId) {
        $sql = "SELECT * FROM {$this->table} WHERE course_id = ? AND student_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$courseId, $studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
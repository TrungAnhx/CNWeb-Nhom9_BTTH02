<?php
class Lesson {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Lấy tất cả bài học của 1 khóa học (sắp xếp theo thứ tự)
     * @param int $courseId - ID khóa học
     * @return array - Danh sách bài học
     */
    public function getLessonsByCourse($courseId) {
        $sql = "SELECT l.*,
                       (SELECT COUNT(*) FROM materials WHERE lesson_id = l.id) as material_count
                FROM lessons l
                WHERE l.course_id = ?
                ORDER BY l.order_num ASC, l.id ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy chi tiết 1 bài học
     * @param int $id - ID bài học
     * @return array|false
     */
    public function getLessonById($id) {
        $sql = "SELECT l.*, c.title as course_title, c.instructor_id
                FROM lessons l
                JOIN courses c ON l.course_id = c.id
                WHERE l.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Tạo bài học mới
     * @param array $data - Dữ liệu bài học
     * @return int - ID bài học vừa tạo
     */
    public function createLesson($data) {
        // Tự động tính order_num nếu không có
        if (empty($data['order_num'])) {
            $sql = "SELECT COALESCE(MAX(order_num), 0) + 1 as next_order 
                    FROM lessons WHERE course_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$data['course_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $data['order_num'] = $result['next_order'];
        }
        
        $sql = "INSERT INTO lessons (course_id, title, content, video_url, order_num)
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['course_id'],
            $data['title'],
            $data['content'] ?? '',
            $data['video_url'] ?? '',
            $data['order_num']
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Cập nhật bài học
     * @param int $id - ID bài học
     * @param array $data - Dữ liệu cập nhật
     * @return bool
     */
    public function updateLesson($id, $data) {
        $sql = "UPDATE lessons 
                SET title = ?, content = ?, video_url = ?, order_num = ?
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['content'] ?? '',
            $data['video_url'] ?? '',
            $data['order_num'],
            $id
        ]);
    }
    
    /**
     * Xóa bài học
     * @param int $id - ID bài học
     * @return bool
     */
    public function deleteLesson($id) {
        $sql = "DELETE FROM lessons WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Cập nhật thứ tự bài học (drag & drop)
     * @param int $id - ID bài học
     * @param int $newOrder - Thứ tự mới
     * @return bool
     */
    public function updateLessonOrder($id, $newOrder) {
        $sql = "UPDATE lessons SET order_num = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$newOrder, $id]);
    }
    
    /**
     * Đếm số bài học của khóa
     * @param int $courseId
     * @return int
     */
    public function countLessonsByCourse($courseId) {
        $sql = "SELECT COUNT(*) as total FROM lessons WHERE course_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$courseId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
?>
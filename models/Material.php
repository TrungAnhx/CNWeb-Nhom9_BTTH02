<?php
class Material {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    
    /**
     * Lấy tất cả materials của một lesson
     */
    public function getMaterialsByLesson($lessonId) {
        $sql = "SELECT * FROM materials WHERE lesson_id = :lesson_id ORDER BY uploaded_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':lesson_id', $lessonId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy thông tin 1 material theo ID
     */
    public function getMaterialById($id) {
        $sql = "SELECT m.*, l.course_id 
                FROM materials m 
                JOIN lessons l ON m.lesson_id = l.id 
                WHERE m.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Thêm material mới (chỉ lưu link)
     */
    public function createMaterial($data) {
        $sql = "INSERT INTO materials (lesson_id, filename, file_path, file_type) 
                VALUES (:lesson_id, :filename, :file_path, :file_type)";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':lesson_id', $data['lesson_id']);
        $stmt->bindParam(':filename', $data['filename']);
        $stmt->bindParam(':file_path', $data['file_path']); // URL link
        $stmt->bindParam(':file_type', $data['file_type']);
        
        return $stmt->execute();
    }
    
    /**
     * Xóa material
     */
    public function deleteMaterial($id) {
        $sql = "DELETE FROM materials WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    /**
     * Đếm số materials của một lesson
     */
    public function countMaterialsByLesson($lessonId) {
        $sql = "SELECT COUNT(*) as total FROM materials WHERE lesson_id = :lesson_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':lesson_id', $lessonId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>
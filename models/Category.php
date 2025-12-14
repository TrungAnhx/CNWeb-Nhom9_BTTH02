<?php
class Category {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Lấy tất cả danh mục khóa học
     * @return array - Danh sách danh mục
     */
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Lấy chi tiết 1 danh mục
     * @param int $id
     * @return array|false
     */
    public function getCategoryById($id) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Tạo danh mục mới (dành cho Admin)
     * @param array $data
     * @return int - ID danh mục vừa tạo
     */
    public function createCategory($data) {
        $sql = "INSERT INTO categories (name, description) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$data['name'], $data['description']]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Cập nhật danh mục (dành cho Admin)
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateCategory($id, $data) {
        $sql = "UPDATE categories SET name = ?, description = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['name'], $data['description'], $id]);
    }
    
    /**
     * Xóa danh mục (dành cho Admin)
     * @param int $id
     * @return bool
     */
    public function deleteCategory($id) {
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
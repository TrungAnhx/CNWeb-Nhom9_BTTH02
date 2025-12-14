<?php
require_once 'config/Database.php';

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
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lấy 1 danh mục theo ID (dùng cho trang Sửa)
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Thêm mới danh mục
    public function create($name, $description) {
        $query = "INSERT INTO " . $this->table_name . " (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($query);
        
        // Làm sạch dữ liệu
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 4. Cập nhật danh mục
    public function update($id, $name, $description) {
        $query = "UPDATE " . $this->table_name . " SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 5. Xóa danh mục
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>

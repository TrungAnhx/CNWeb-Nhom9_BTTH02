<?php
require_once 'config/Database.php';

class User {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // --- CÁC HÀM AUTH (Giữ nguyên) ---
    public function register($username, $email, $password, $fullname, $role = 0) {
        $query = "INSERT INTO " . $this->table_name . " (username, email, password, fullname, role) VALUES (:username, :email, :password, :fullname, :role)";
        $stmt = $this->conn->prepare($query);
        // Lưu ý: Đang tắt Hash để test theo yêu cầu của bạn
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':role', $role);
        return $stmt->execute();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($password === $row['password']) {
                if (isset($row['status']) && $row['status'] == 0) {
                    return "BANNED";
                }
                return $row;
            }
        }
        return false;
    }
    
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // --- CÁC HÀM QUẢN TRỊ (Nâng cấp) ---

    // 1. Lấy danh sách user (Có tìm kiếm & Lọc)
    public function getAll($keyword = "", $role = "") {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";

        // Tìm kiếm theo tên hoặc email
        if (!empty($keyword)) {
            $query .= " AND (fullname LIKE :keyword OR email LIKE :keyword)";
        }

        // Lọc theo vai trò
        if ($role !== "") {
            $query .= " AND role = :role";
        }

        $query .= " ORDER BY id ASC";

        $stmt = $this->conn->prepare($query);

        if (!empty($keyword)) {
            $searchTerm = "%{$keyword}%";
            $stmt->bindParam(':keyword', $searchTerm);
        }

        if ($role !== "") {
            $stmt->bindParam(':role', $role);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Các hàm khác giữ nguyên
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>

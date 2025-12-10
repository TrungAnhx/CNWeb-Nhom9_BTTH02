<?php
require_once 'config/Database.php';

class User {
    private $conn;
    private $table_name = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Đăng ký tài khoản mới (KHÔNG HASH PASS)
    public function register($username, $email, $password, $fullname, $role = 0) {
        $query = "INSERT INTO " . $this->table_name . " (username, email, password, fullname, role) VALUES (:username, :email, :password, :fullname, :role)";
        $stmt = $this->conn->prepare($query);

        // BỎ HASH: Lưu mật khẩu thô
        // $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password); // Lưu pass thô
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Kiểm tra đăng nhập (SO SÁNH THÔ)
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // BỎ password_verify -> So sánh chuỗi trực tiếp
            if ($password === $row['password']) {
                // Kiểm tra xem tài khoản có bị khóa không
                if (isset($row['status']) && $row['status'] == 0) {
                    return "BANNED";
                }
                return $row; // Trả về thông tin user
            }
        }
        return false;
    }
    
    // Kiểm tra email tồn tại
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>

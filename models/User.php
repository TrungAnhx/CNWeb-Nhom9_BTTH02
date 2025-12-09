<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password;
    public $fullname;
    public $role;
    public $status;
    public $avatar;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Kiểm tra đăng nhập
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        
        $email = htmlspecialchars(strip_tags($email));
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verify mật khẩu (Tạm thời bỏ Hash theo yêu cầu)
            if ($password === $row['password']) {
                if ($row['status'] == 0) {
                    return "BANNED";
                }
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->fullname = $row['fullname'];
                $this->role = $row['role'];
                $this->avatar = $row['avatar'];
                return true;
            }
        }
        return false;
    }
}
?>

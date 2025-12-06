<?php
require_once 'config/Database.php';
require_once 'models/User.php';

class AuthController {
    private $db;
    private $userModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
    }

    public function login() {
        $error = '';
        
        // Nếu user đã login rồi thì đá về Dashboard tương ứng
        if (isset($_SESSION['user_id'])) {
            $this->redirectUser($_SESSION['role']);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $result = $this->userModel->login($email, $password);

            if ($result === true) {
                // Login thành công -> Lưu session
                $_SESSION['user_id'] = $this->userModel->id;
                $_SESSION['username'] = $this->userModel->username;
                $_SESSION['fullname'] = $this->userModel->fullname;
                $_SESSION['role'] = $this->userModel->role;
                
                $this->redirectUser($this->userModel->role);
            } elseif ($result === "BANNED") {
                $error = "Tài khoản của bạn đã bị vô hiệu hóa.";
            } else {
                $error = "Email hoặc mật khẩu không chính xác.";
            }
        }

        require 'views/auth/login.php';
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    private function redirectUser($role) {
        switch ($role) {
            case 2: // Admin
                header("Location: index.php?controller=admin&action=dashboard");
                break;
            case 1: // Instructor
                header("Location: index.php?controller=instructor&action=dashboard");
                break;
            default: // Student
                header("Location: index.php"); // Về trang chủ
                break;
        }
        exit();
    }
}
?>

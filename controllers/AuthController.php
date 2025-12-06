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
    
    /**
     * Login chung (auth/login.php)
     */
    public function login() {
        require 'views/auth/login.php';
    }
    
    public function register() {
        require 'views/auth/register.php';
    }
    
    /**
     * Login riêng cho Instructor
     */
    public function instructorLogin() {
        // Nếu đã đăng nhập và là instructor → Redirect về dashboard
        if (isset($_SESSION['user_id']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 2)) {
            header('Location: ?controller=course&action=dashboard');
            exit;
        }
        
        // Nếu là POST request → Xử lý đăng nhập
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processInstructorLogin();
            return;
        }
        
        // Hiển thị form login
        require 'views/instructor/login.php';
    }
    
    /**
     * Xử lý đăng nhập instructor
     */
    private function processInstructorLogin() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validate input
        if (empty($username) || empty($password)) {
            $_SESSION['login_error'] = '⚠️ Vui lòng nhập đầy đủ thông tin!';
            header('Location: ?controller=auth&action=instructorLogin');
            exit;
        }
        
        // Tìm user trong database
        $user = $this->userModel->findByUsernameOrEmail($username);
        
        if (!$user) {
            $_SESSION['login_error'] = '❌ Tên đăng nhập hoặc mật khẩu không đúng!';
            header('Location: ?controller=auth&action=instructorLogin');
            exit;
        }
        
        // Kiểm tra password (dùng password_verify nếu đã hash)
        $passwordMatch = false;
        
        // Thử verify với hash trước
        if (password_verify($password, $user['password'])) {
            $passwordMatch = true;
        } 
        // Nếu không match, thử so sánh trực tiếp (cho database test không hash)
        elseif ($password === $user['password']) {
            $passwordMatch = true;
        }
        
        if (!$passwordMatch) {
            $_SESSION['login_error'] = '❌ Tên đăng nhập hoặc mật khẩu không đúng!';
            header('Location: ?controller=auth&action=instructorLogin');
            exit;
        }
        
        // Kiểm tra role (phải là Instructor=1 hoặc Admin=2)
        if ($user['role'] != 1 && $user['role'] != 2) {
            $_SESSION['login_error'] = '🚫 Bạn không có quyền truy cập trang Giảng viên! Chỉ Instructor/Admin mới được vào.';
            header('Location: ?controller=auth&action=instructorLogin');
            exit;
        }
        
        // ✅ Đăng nhập thành công
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['fullname'] ?? $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        
        // Redirect về dashboard
        header('Location: ?controller=course&action=dashboard');
        exit;
    }
    
    /**
     * Đăng xuất
     */
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ?controller=auth&action=instructorLogin');
        exit;
    }
}
?>
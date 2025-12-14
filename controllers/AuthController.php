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
require_once 'models/User.php';

class AuthController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login() {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new User();
            $user = $userModel->login($email, $password);

            if ($user === "BANNED") {
                $error = "Tài khoản của bạn đã bị khóa!";
            } elseif ($user) {
                // Đăng nhập thành công -> Lưu session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'fullname' => $user['fullname'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'avatar' => $user['avatar']
                ];

                // Chuyển hướng theo quyền
                if ($user['role'] == 2) { // Admin
                    header("Location: index.php?controller=admin&action=dashboard");
                } elseif ($user['role'] == 1) { // Giảng viên
                    header("Location: index.php?controller=instructor&action=dashboard");
                } else { // Học viên
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Email hoặc mật khẩu không đúng!";
            }
        }
        require 'views/auth/login.php';
    }
    
    public function register() {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $_POST['fullname'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $role = 0; // Mặc định là học viên

            if ($password !== $confirm_password) {
                $error = "Mật khẩu nhập lại không khớp!";
            } else {
                $userModel = new User();
                if ($userModel->emailExists($email)) {
                    $error = "Email này đã được đăng ký!";
                } else {
                    if ($userModel->register($username, $email, $password, $fullname, $role)) {
                        $success = "Đăng ký thành công! Vui lòng đăng nhập.";
                    } else {
                        $error = "Có lỗi xảy ra, vui lòng thử lại.";
                    }
                }
            }
        }
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
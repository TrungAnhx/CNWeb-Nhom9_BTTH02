<?php
require_once 'config/Database.php';
require_once 'models/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = new Database();
        $this->userModel = new User($database->getConnection());
    }

    public function login() {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->login($email, $password);

            if ($user === "BANNED") {
                $error = "Tài khoản của bạn đã bị khóa!";
            } elseif ($user) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'fullname' => $user['fullname'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'avatar' => $user['avatar'] ?? ''
                ];

                if ($user['role'] == 2) {
                    header("Location: index.php?controller=admin&action=dashboard");
                } elseif ($user['role'] == 1) {
                    header("Location: index.php?controller=instructor&action=dashboard");
                } else {
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
            $fullname = $_POST['fullname'] ?? '';
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $role = 0;

            if (empty($fullname) || empty($username) || empty($email) || empty($password)) {
                $error = "Vui lòng điền đầy đủ thông tin!";
            } elseif ($password !== $confirm_password) {
                $error = "Mật khẩu nhập lại không khớp!";
            } else {
                if ($this->userModel->emailExists($email)) {
                    $error = "Email này đã được đăng ký!";
                } elseif ($this->userModel->usernameExists($username)) {
                    $error = "Tên đăng nhập này đã có người sử dụng, vui lòng chọn tên khác.";
                } else {
                    if ($this->userModel->register($username, $email, $password, $fullname, $role)) {
                        $success = "Đăng ký thành công! Vui lòng đăng nhập.";
                    } else {
                        $error = "Có lỗi xảy ra, vui lòng thử lại.";
                    }
                }
            }
        }
        require 'views/auth/register.php';
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }
}
?>

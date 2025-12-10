<?php
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

    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit();
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
}
?>
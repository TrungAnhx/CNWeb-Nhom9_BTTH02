<?php
require_once 'models/Category.php';
require_once 'models/User.php';
require_once 'models/Course.php'; // Gọi thêm Model Course

class AdminController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }

    public function dashboard() {
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM courses");
        $stmt->execute();
        $countCourses = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE role = 1");
        $stmt->execute();
        $countInstructors = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE role = 0");
        $stmt->execute();
        $countStudents = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        require 'views/admin/dashboard.php';
    }

    // --- QUẢN LÝ DANH MỤC ---
    public function categories() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        require 'views/admin/categories/list.php';
    }

    public function createCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $categoryModel = new Category();
            if ($categoryModel->create($name, $description)) {
                header("Location: index.php?controller=admin&action=categories");
            } else {
                echo "Có lỗi xảy ra!";
            }
        } else {
            require 'views/admin/categories/create.php';
        }
    }

    public function editCategory() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $categoryModel = new Category();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            if ($categoryModel->update($id, $name, $description)) {
                header("Location: index.php?controller=admin&action=categories");
            } else { echo "Lỗi update!"; }
        } else {
            $category = $categoryModel->getById($id);
            if (!$category) { echo "Danh mục không tồn tại!"; exit(); }
            require 'views/admin/categories/edit.php';
        }
    }

    public function deleteCategory() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            $categoryModel = new Category();
            $categoryModel->delete($id);
        }
        header("Location: index.php?controller=admin&action=categories");
    }

    // --- QUẢN LÝ NGƯỜI DÙNG ---
    public function users() {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";
        $role = isset($_GET['role']) ? $_GET['role'] : "";
        $userModel = new User();
        $users = $userModel->getAll($keyword, $role);
        require 'views/admin/users/manage.php';
    }

    public function toggleUserStatus() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : 1;
        if ($id) {
            if ($id == $_SESSION['user']['id']) {
                echo "<script>alert('Không thể tự khóa tài khoản Admin!'); window.history.back();</script>";
                return;
            }
            $userModel = new User();
            $userModel->updateStatus($id, $status);
        }
        header("Location: index.php?controller=admin&action=users");
    }

    public function deleteUser() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            if ($id == $_SESSION['user']['id']) {
                echo "<script>alert('Không thể tự xóa tài khoản Admin!'); window.history.back();</script>";
                return;
            }
            $userModel = new User();
            $userModel->delete($id);
        }
        header("Location: index.php?controller=admin&action=users");
    }

    // --- DUYỆT KHÓA HỌC (NEW) ---

    // 1. Danh sách chờ duyệt
    public function courses() {
        $courseModel = new Course();
        $pendingCourses = $courseModel->getPendingCourses();
        require 'views/admin/courses/approve.php';
    }

    // 2. Duyệt (Approve)
    public function approveCourse() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            $courseModel = new Course();
            $courseModel->approve($id);
        }
        header("Location: index.php?controller=admin&action=courses");
    }

    // 3. Từ chối (Xóa luôn hoặc đổi status) -> Ở đây làm Xóa cho gọn
    public function deleteCourse() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            $courseModel = new Course();
            $courseModel->delete($id);
        }
        header("Location: index.php?controller=admin&action=courses");
    }
}
?>

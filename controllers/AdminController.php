<?php
require_once 'models/Category.php';

class AdminController {

    // Middleware đơn giản: Kiểm tra phải là Admin mới được vào
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Nếu chưa đăng nhập hoặc role không phải 2 (Admin) -> Đá về login
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 2) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    }

    public function dashboard() {
        // Kết nối DB để đếm số liệu
        $db = new Database();
        $conn = $db->getConnection();

        // 1. Đếm số khóa học (Tất cả)
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM courses");
        $stmt->execute();
        $countCourses = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // 2. Đếm số Giảng viên (role=1)
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE role = 1");
        $stmt->execute();
        $countInstructors = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // 3. Đếm số Học viên (role=0)
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE role = 0");
        $stmt->execute();
        $countStudents = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        require 'views/admin/dashboard.php';
    }

    // --- QUẢN LÝ DANH MỤC (CATEGORIES) ---

    // 1. Xem danh sách
    public function categories() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        require 'views/admin/categories/list.php';
    }

    // 2. Thêm mới
    public function createCategory() {
        // Nếu submit form POST
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
            // Nếu method GET -> Hiển thị form
            require 'views/admin/categories/create.php';
        }
    }

    // 3. Chỉnh sửa
    public function editCategory() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $categoryModel = new Category();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            
            if ($categoryModel->update($id, $name, $description)) {
                header("Location: index.php?controller=admin&action=categories");
            } else {
                echo "Lỗi update!";
            }
        } else {
            // Lấy thông tin cũ để điền vào form
            $category = $categoryModel->getById($id);
            if (!$category) {
                echo "Danh mục không tồn tại!";
                exit();
            }
            require 'views/admin/categories/edit.php';
        }
    }

    // 4. Xóa
    public function deleteCategory() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            $categoryModel = new Category();
            $categoryModel->delete($id);
        }
        header("Location: index.php?controller=admin&action=categories");
    }
}
?>

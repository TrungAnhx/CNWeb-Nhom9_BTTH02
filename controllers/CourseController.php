<?php
require_once 'models/Course.php';
require_once 'config/Database.php';

class CourseController {
    private $courseModel;
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->courseModel = new Course($this->db);
    }
    
    /**
     * Trang chủ khóa học (cho học viên)
     */
    public function index() {
        require 'views/student/courses.php';
    }
    
    /**
     * Chi tiết khóa học (cho học viên)
     */
    public function detail() {
        require 'views/student/course_detail.php';
    }
    
    /**
     * Dashboard giảng viên (hiển thị khóa học của tôi)
     */
    public function dashboard() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            // Redirect về instructor login
            header('Location: ?controller=auth&action=instructorLogin');
            exit;
        }
        
        // Kiểm tra role (phải là giảng viên hoặc admin)
        if ($_SESSION['role'] != 1 && $_SESSION['role'] != 2) {
            die("⚠️ Bạn không có quyền truy cập trang này! Chỉ Instructor/Admin mới được vào.");
        }
        
        // Lấy instructor_id từ session (BẢO MẬT)
        $instructorId = $_SESSION['user_id'];
        
        // Lấy danh sách khóa học
        $courses = $this->courseModel->getCoursesByInstructor($instructorId);
        
        // Lấy thống kê
        $stats = $this->courseModel->getInstructorStatistics($instructorId);
        
        // Truyền dữ liệu sang view
        require 'views/instructor/dashboard.php';
    }
    
    /**
     * Alias cho dashboard (để tương thích với code cũ)
     */
    public function myCourses() {
        $this->dashboard();
    }
    
    /**
     * Form tạo khóa học mới
     */
    public function create() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=auth&action=instructorLogin');
            exit;
        }
        
        // Kiểm tra role (phải là giảng viên hoặc admin)
        if ($_SESSION['role'] != 1 && $_SESSION['role'] != 2) {
            die("⚠️ Bạn không có quyền truy cập trang này!");
        }
        
        // Lấy danh sách categories cho dropdown
        require_once 'models/Category.php';
        $categoryModel = new Category($this->db);
        $categories = $categoryModel->getAllCategories();
        
        require 'views/instructor/course/create.php';
    }
    
    /**
     * Xử lý tạo khóa học (POST)
     */
    public function store() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=auth&action=instructorLogin');
            exit;
        }
        
        // Kiểm tra role
        if ($_SESSION['role'] != 1 && $_SESSION['role'] != 2) {
            die("⚠️ Bạn không có quyền truy cập!");
        }
        
        // Validate dữ liệu
        $errors = [];
        
        if (empty($_POST['title'])) {
            $errors[] = 'Tiêu đề khóa học là bắt buộc';
        }
        
        if (empty($_POST['category_id'])) {
            $errors[] = 'Vui lòng chọn danh mục';
        }
        
        if (empty($_POST['level'])) {
            $errors[] = 'Vui lòng chọn cấp độ';
        }
        
        // Xử lý upload ảnh
        $imageName = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            
            if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                $errors[] = 'Chỉ chấp nhận file JPG, PNG, GIF';
            }
            
            if ($_FILES['image']['size'] > $maxSize) {
                $errors[] = 'Kích thước ảnh không được vượt quá 2MB';
            }
            
            if (empty($errors)) {
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid('course_') . '.' . $extension;
                $uploadPath = 'uploads/courses/' . $imageName;
                
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $errors[] = 'Không thể upload ảnh';
                }
            }
        }
        
        // Nếu có lỗi, quay lại form
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            header('Location: ?controller=course&action=create');
            exit;
        }
        
        // Chuẩn bị dữ liệu
        $data = [
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description'] ?? ''),
            'instructor_id' => $_SESSION['user_id'],
            'category_id' => intval($_POST['category_id']),
            'price' => floatval($_POST['price'] ?? 0),
            'duration_weeks' => intval($_POST['duration_weeks'] ?? 0),
            'level' => $_POST['level'],
            'image' => $imageName,
            'status' => 'draft' // Mặc định là nháp
        ];
        
        // Lưu vào database
        $courseId = $this->courseModel->createCourse($data);
        
        if ($courseId) {
            $_SESSION['success'] = 'Tạo khóa học thành công!';
            header('Location: ?controller=course&action=dashboard');
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi tạo khóa học';
            header('Location: ?controller=course&action=create');
        }
        exit;
    }
    
    /**
     * Form sửa khóa học
     */
    public function edit($id) {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=auth&action=instructorLogin');
            exit;
        }
        
        // Kiểm tra role
        if ($_SESSION['role'] != 1 && $_SESSION['role'] != 2) {
            die("⚠️ Bạn không có quyền truy cập!");
        }
        
        // Lấy thông tin khóa học
        $course = $this->courseModel->getCourseById($id);
        
        if (!$course) {
            $_SESSION['error'] = 'Không tìm thấy khóa học';
            header('Location: ?controller=course&action=dashboard');
            exit;
        }
        
        // Kiểm tra quyền sở hữu (chỉ giảng viên sở hữu mới được sửa)
        if ($course['instructor_id'] != $_SESSION['user_id'] && $_SESSION['role'] != 2) {
            $_SESSION['error'] = 'Bạn không có quyền chỉnh sửa khóa học này';
            header('Location: ?controller=course&action=dashboard');
            exit;
        }
        
        // Lấy danh sách categories
        require_once 'models/Category.php';
        $categoryModel = new Category($this->db);
        $categories = $categoryModel->getAllCategories();
        
        require 'views/instructor/course/edit.php';
    }
    
    /**
     * Xử lý cập nhật khóa học (POST)
     */
    public function update($id) {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=auth&action=instructorLogin');
            exit;
        }
        
        // Kiểm tra role
        if ($_SESSION['role'] != 1 && $_SESSION['role'] != 2) {
            die("⚠️ Bạn không có quyền truy cập!");
        }
        
        // Lấy thông tin khóa học hiện tại
        $course = $this->courseModel->getCourseById($id);
        
        if (!$course) {
            $_SESSION['error'] = 'Không tìm thấy khóa học';
            header('Location: ?controller=course&action=dashboard');
            exit;
        }
        
        // Kiểm tra quyền sở hữu
        if ($course['instructor_id'] != $_SESSION['user_id'] && $_SESSION['role'] != 2) {
            $_SESSION['error'] = 'Bạn không có quyền chỉnh sửa khóa học này';
            header('Location: ?controller=course&action=dashboard');
            exit;
        }
        
        // Validate dữ liệu
        $errors = [];
        
        if (empty($_POST['title'])) {
            $errors[] = 'Tiêu đề khóa học là bắt buộc';
        }
        
        if (empty($_POST['category_id'])) {
            $errors[] = 'Vui lòng chọn danh mục';
        }
        
        if (empty($_POST['level'])) {
            $errors[] = 'Vui lòng chọn cấp độ';
        }
        
        // Xử lý upload ảnh mới (nếu có)
        $imageName = $course['image']; // Giữ ảnh cũ
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            
            if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                $errors[] = 'Chỉ chấp nhận file JPG, PNG, GIF';
            }
            
            if ($_FILES['image']['size'] > $maxSize) {
                $errors[] = 'Kích thước ảnh không được vượt quá 2MB';
            }
            
            if (empty($errors)) {
                // Xóa ảnh cũ nếu có
                if ($course['image'] && file_exists('uploads/courses/' . $course['image'])) {
                    unlink('uploads/courses/' . $course['image']);
                }
                
                // Upload ảnh mới
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid('course_') . '.' . $extension;
                $uploadPath = 'uploads/courses/' . $imageName;
                
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    $errors[] = 'Không thể upload ảnh';
                    $imageName = $course['image']; // Giữ lại ảnh cũ nếu upload thất bại
                }
            }
        }
        
        // Nếu có lỗi, quay lại form
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            header('Location: ?controller=course&action=edit&id=' . $id);
            exit;
        }
        
        // Chuẩn bị dữ liệu cập nhật
        $data = [
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description'] ?? ''),
            'category_id' => intval($_POST['category_id']),
            'price' => floatval($_POST['price'] ?? 0),
            'duration_weeks' => intval($_POST['duration_weeks'] ?? 0),
            'level' => $_POST['level'],
            'image' => $imageName
        ];
        
        // Cập nhật vào database
        $success = $this->courseModel->updateCourse($id, $data);
        
        if ($success) {
            $_SESSION['success'] = 'Cập nhật khóa học thành công!';
            header('Location: ?controller=course&action=dashboard');
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật khóa học';
            header('Location: ?controller=course&action=edit&id=' . $id);
        }
        exit;
    }
    
    /**
     * Xóa khóa học
     */
    public function delete($id) {
        session_start();
        $instructorId = $_SESSION['user_id'];
        
        // Xóa (chỉ nếu là khóa học của giảng viên này)
        if ($this->courseModel->deleteCourse($id, $instructorId)) {
            header('Location: /instructor/my-courses?success=deleted');
        } else {
            header('Location: /instructor/my-courses?error=delete_failed');
        }
        exit;
    }
}
?>
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
        require 'views/courses/index.php';
    }
    
    /**
     * Chi tiết khóa học (cho học viên)
     */
    public function detail() {
        require 'views/courses/detail.php';
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
        // TODO: Implement create form
        require 'views/instructor/course/create.php';
    }
    
    /**
     * Xử lý tạo khóa học (POST)
     */
    public function store() {
        // TODO: Implement store logic
    }
    
    /**
     * Form sửa khóa học
     */
    public function edit($id) {
        // TODO: Implement edit form
        require 'views/instructor/course/edit.php';
    }
    
    /**
     * Xử lý cập nhật khóa học (POST)
     */
    public function update($id) {
        // TODO: Implement update logic
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
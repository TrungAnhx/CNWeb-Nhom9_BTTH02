<?php
require_once 'models/Lesson.php';
require_once 'models/Course.php';
require_once 'config/Database.php';

class LessonController {
    private $lessonModel;
    private $courseModel;
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->lessonModel = new Lesson($this->db);
        $this->courseModel = new Course($this->db);
    }
    
    /**
     * Quản lý bài học của 1 khóa
     */
    public function manage() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/instructor/login');
            exit;
        }
        
        // Kiểm tra role
        if ($_SESSION['role'] != 1 && $_SESSION['role'] != 2) {
            die("⚠️ Bạn không có quyền truy cập!");
        }
        
        // Lấy course_id từ URL
        $courseId = $_GET['course_id'] ?? 0;
        
        // Lấy thông tin khóa học
        $course = $this->courseModel->getCourseById($courseId);
        
        if (!$course) {
            $_SESSION['error'] = 'Không tìm thấy khóa học';
            header('Location: ' . BASE_URL . '/?controller=course&action=dashboard');
            exit;
        }
        
        // Kiểm tra quyền sở hữu
        if ($course['instructor_id'] != $_SESSION['user_id'] && $_SESSION['role'] != 2) {
            $_SESSION['error'] = 'Bạn không có quyền quản lý khóa học này';
            header('Location: ' . BASE_URL . '/?controller=course&action=dashboard');
            exit;
        }
        
        // Lấy danh sách bài học
        $lessons = $this->lessonModel->getLessonsByCourse($courseId);
        
        require 'views/instructor/lessons/manage.php';
    }
    
    /**
     * Form tạo bài học mới
     */
    public function create() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/instructor/login');
            exit;
        }
        
        // Kiểm tra role
        if ($_SESSION['role'] != 1 && $_SESSION['role'] != 2) {
            die("⚠️ Bạn không có quyền truy cập!");
        }
        
        // Lấy course_id
        $courseId = $_GET['course_id'] ?? 0;
        
        // Lấy thông tin khóa học
        $course = $this->courseModel->getCourseById($courseId);
        
        if (!$course) {
            $_SESSION['error'] = 'Không tìm thấy khóa học';
            header('Location: ' . BASE_URL . '/?controller=course&action=dashboard');
            exit;
        }
        
        // Kiểm tra quyền
        if ($course['instructor_id'] != $_SESSION['user_id'] && $_SESSION['role'] != 2) {
            $_SESSION['error'] = 'Bạn không có quyền tạo bài học cho khóa này';
            header('Location: ' . BASE_URL . '/?controller=course&action=dashboard');
            exit;
        }
        
        require 'views/instructor/lessons/create.php';
    }
    
    /**
     * Xử lý tạo bài học (POST)
     */
    public function store() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/instructor/login');
            exit;
        }
        
        // Validate
        $errors = [];
        
        if (empty($_POST['title'])) {
            $errors[] = 'Tiêu đề bài học là bắt buộc';
        }
        
        if (empty($_POST['course_id'])) {
            $errors[] = 'Course ID không hợp lệ';
        }
        
        // Nếu có lỗi
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            header('Location: ' . BASE_URL . '/?controller=lesson&action=create&course_id=' . $_POST['course_id']);
            exit;
        }
        
        // Chuẩn bị dữ liệu
        $data = [
            'course_id' => intval($_POST['course_id']),
            'title' => trim($_POST['title']),
            'content' => trim($_POST['content'] ?? ''),
            'video_url' => trim($_POST['video_url'] ?? ''),
            'order_num' => intval($_POST['order_num'] ?? 0)
        ];
        
        // Lưu vào database
        $lessonId = $this->lessonModel->createLesson($data);
        
        if ($lessonId) {
            $_SESSION['success'] = 'Tạo bài học thành công!';
            header('Location: ' . BASE_URL . '/?controller=lesson&action=manage&course_id=' . $data['course_id']);
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi tạo bài học';
            header('Location: ' . BASE_URL . '/?controller=lesson&action=create&course_id=' . $data['course_id']);
        }
        exit;
    }
    
    /**
     * Form sửa bài học
     */
    public function edit() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/instructor/login');
            exit;
        }
        
        // Lấy lesson_id
        $lessonId = $_GET['id'] ?? 0;
        
        // Lấy thông tin bài học
        $lesson = $this->lessonModel->getLessonById($lessonId);
        
        if (!$lesson) {
            $_SESSION['error'] = 'Không tìm thấy bài học';
            header('Location: ' . BASE_URL . '/?controller=course&action=dashboard');
            exit;
        }
        
        // Kiểm tra quyền
        if ($lesson['instructor_id'] != $_SESSION['user_id'] && $_SESSION['role'] != 2) {
            $_SESSION['error'] = 'Bạn không có quyền chỉnh sửa bài học này';
            header('Location: ' . BASE_URL . '/?controller=course&action=dashboard');
            exit;
        }
        
        require 'views/instructor/lessons/edit.php';
    }
    
    /**
     * Xử lý cập nhật bài học (POST)
     */
    public function update() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/instructor/login');
            exit;
        }
        
        $lessonId = $_GET['id'] ?? 0;
        
        // Validate
        $errors = [];
        
        if (empty($_POST['title'])) {
            $errors[] = 'Tiêu đề bài học là bắt buộc';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_data'] = $_POST;
            header('Location: ' . BASE_URL . '/?controller=lesson&action=edit&id=' . $lessonId);
            exit;
        }
        
        // Chuẩn bị dữ liệu
        $data = [
            'title' => trim($_POST['title']),
            'content' => trim($_POST['content'] ?? ''),
            'video_url' => trim($_POST['video_url'] ?? ''),
            'order_num' => intval($_POST['order_num'] ?? 1)
        ];
        
        // Cập nhật
        $success = $this->lessonModel->updateLesson($lessonId, $data);
        
        if ($success) {
            $_SESSION['success'] = 'Cập nhật bài học thành công!';
            $lesson = $this->lessonModel->getLessonById($lessonId);
            header('Location: ' . BASE_URL . '/?controller=lesson&action=manage&course_id=' . $lesson['course_id']);
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật';
            header('Location: ' . BASE_URL . '/?controller=lesson&action=edit&id=' . $lessonId);
        }
        exit;
    }
    
    /**
     * Xóa bài học
     */
    public function delete() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/instructor/login');
            exit;
        }
        
        $lessonId = $_GET['id'] ?? 0;
        
        // Lấy thông tin bài học
        $lesson = $this->lessonModel->getLessonById($lessonId);
        
        if (!$lesson) {
            $_SESSION['error'] = 'Không tìm thấy bài học';
            header('Location: ' . BASE_URL . '/?controller=course&action=dashboard');
            exit;
        }
        
        // Kiểm tra quyền
        if ($lesson['instructor_id'] != $_SESSION['user_id'] && $_SESSION['role'] != 2) {
            $_SESSION['error'] = 'Bạn không có quyền xóa bài học này';
            header('Location: ' . BASE_URL . '/?controller=course&action=dashboard');
            exit;
        }
        
        // Xóa
        $courseId = $lesson['course_id'];
        
        if ($this->lessonModel->deleteLesson($lessonId)) {
            $_SESSION['success'] = 'Xóa bài học thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa bài học';
        }
        
        header('Location: ' . BASE_URL . '/?controller=lesson&action=manage&course_id=' . $courseId);
        exit;
    }
}
?>
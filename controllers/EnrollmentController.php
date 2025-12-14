<?php
class EnrollmentController {
    private $enrollmentModel;
    private $courseModel;
    
    public function __construct() {
        $this->enrollmentModel = new Enrollment();
        $this->courseModel = new Course();
    }
    
    /**
     * Hiển thị danh sách học viên của 1 khóa học
     */
    public function listStudents() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=login');
            exit;
        }
        
        // Lấy course_id từ URL
        $courseId = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
        
        if ($courseId == 0) {
            $_SESSION['error'] = "Không tìm thấy khóa học!";
            header('Location: ' . BASE_URL . '/index.php?controller=course&action=dashboard');
            exit;
        }
        
        // Kiểm tra quyền sở hữu khóa học
        $course = $this->courseModel->getCourseById($courseId);
        if (!$course) {
            $_SESSION['error'] = "Không tìm thấy khóa học!";
            header('Location: ' . BASE_URL . '/index.php?controller=course&action=dashboard');
            exit;
        }
        
        if ($course['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Bạn không có quyền xem danh sách học viên của khóa học này!";
            header('Location: ' . BASE_URL . '/index.php?controller=course&action=dashboard');
            exit;
        }
        
        // Xử lý tìm kiếm (nếu có)
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        if (!empty($keyword)) {
            $students = $this->enrollmentModel->searchStudentsInCourse($courseId, $keyword);
        } else {
            $students = $this->enrollmentModel->getEnrollmentsByCourse($courseId);
        }
        
        // Lấy tổng số học viên
        $totalStudents = $this->enrollmentModel->countStudentsByCourse($courseId);
        
        // Load view
        require_once 'views/instructor/students/list.php';
    }
    
    /**
     * Xem chi tiết tiến độ của 1 học viên
     */
    public function viewProgress() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=login');
            exit;
        }
        
        $enrollmentId = isset($_GET['enrollment_id']) ? intval($_GET['enrollment_id']) : 0;
        
        if ($enrollmentId == 0) {
            $_SESSION['error'] = "Không tìm thấy thông tin đăng ký!";
            header('Location: ' . BASE_URL . '/index.php?controller=course&action=dashboard');
            exit;
        }
        
        // Lấy thông tin enrollment và kiểm tra quyền
        $enrollment = $this->enrollmentModel->getStudentProgress($enrollmentId);
        
        if (!$enrollment) {
            $_SESSION['error'] = "Không tìm thấy thông tin đăng ký!";
            header('Location: ' . BASE_URL . '/index.php?controller=course&action=dashboard');
            exit;
        }
        
        // Kiểm tra quyền: Phải là giảng viên của khóa học
        $course = $this->courseModel->getCourseById($enrollment['course_id']);
        if ($course['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Bạn không có quyền xem tiến độ này!";
            header('Location: ' . BASE_URL . '/index.php?controller=course&action=dashboard');
            exit;
        }
        
        // Load view chi tiết tiến độ (có thể tạo sau nếu cần)
        // require_once 'views/instructor/students/progress.php';
        
        // Tạm thời redirect về list với thông báo
        $_SESSION['info'] = "Chi tiết tiến độ: {$enrollment['fullname']} - {$enrollment['progress']}% hoàn thành";
        header('Location: ' . BASE_URL . '/index.php?controller=enrollment&action=listStudents&course_id=' . $enrollment['course_id']);
        exit;
    }
}
?>
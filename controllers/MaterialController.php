<?php
class MaterialController {
    private $materialModel;
    private $lessonModel;
    private $courseModel;
    
    public function __construct() {
        $this->materialModel = new Material();
        $this->lessonModel = new Lesson();
        $this->courseModel = new Course();
    }
    
    /**
     * Thêm material mới (chỉ lưu link)
     */
    public function store() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lessonId = $_POST['lesson_id'];
            
            // Lấy thông tin lesson và course
            $lesson = $this->lessonModel->getLessonById($lessonId);
            if (!$lesson) {
                $_SESSION['error'] = "Không tìm thấy bài học!";
                header('Location: ' . BASE_URL . '/index.php?controller=course&action=dashboard');
                exit;
            }
            
            // Kiểm tra quyền sở hữu course
            $course = $this->courseModel->getCourseById($lesson['course_id']);
            if ($course['instructor_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = "Bạn không có quyền thêm tài liệu cho bài học này!";
                header('Location: ' . BASE_URL . '/index.php?controller=course&action=dashboard');
                exit;
            }
            
            // Validation
            $errors = [];
            if (empty($_POST['filename'])) {
                $errors[] = "Tên tài liệu không được để trống!";
            }
            if (empty($_POST['file_path'])) {
                $errors[] = "Link tài liệu không được để trống!";
            } elseif (!filter_var($_POST['file_path'], FILTER_VALIDATE_URL)) {
                $errors[] = "Link tài liệu không đúng định dạng URL!";
            }
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                header('Location: ' . BASE_URL . '/index.php?controller=lesson&action=manage&course_id=' . $lesson['course_id']);
                exit;
            }
            
            // Tự động xác định file_type từ URL
            $fileType = 'link'; // Mặc định
            $url = strtolower($_POST['file_path']);
            if (strpos($url, '.pdf') !== false) {
                $fileType = 'pdf';
            } elseif (strpos($url, 'docs.google.com') !== false || strpos($url, '.doc') !== false) {
                $fileType = 'doc';
            } elseif (strpos($url, 'slides.google.com') !== false || strpos($url, '.ppt') !== false) {
                $fileType = 'ppt';
            } elseif (strpos($url, 'github.com') !== false) {
                $fileType = 'github';
            } elseif (strpos($url, 'drive.google.com') !== false) {
                $fileType = 'drive';
            }
            
            // Lưu vào database
            $data = [
                'lesson_id' => $lessonId,
                'filename' => trim($_POST['filename']),
                'file_path' => trim($_POST['file_path']),
                'file_type' => $fileType
            ];
            
            if ($this->materialModel->createMaterial($data)) {
                $_SESSION['success'] = "Thêm tài liệu thành công!";
            } else {
                $_SESSION['error'] = "Có lỗi khi thêm tài liệu!";
            }
            
            header('Location: ' . BASE_URL . '/index.php?controller=lesson&action=manage&course_id=' . $lesson['course_id']);
            exit;
        }
    }
    
    /**
     * Xóa material
     */
    public function delete() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php?controller=auth&action=login');
            exit;
        }
        
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $material = $this->materialModel->getMaterialById($id);
        
        if (!$material) {
            $_SESSION['error'] = "Không tìm thấy tài liệu!";
            header('Location: ' . BASE_URL . '/index.php?controller=course&action=dashboard');
            exit;
        }
        
        // Kiểm tra quyền
        $course = $this->courseModel->getCourseById($material['course_id']);
        if ($course['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Bạn không có quyền xóa tài liệu này!";
            header('Location: ' . BASE_URL . '/index.php?controller=course&action=dashboard');
            exit;
        }
        
        // Xóa
        if ($this->materialModel->deleteMaterial($id)) {
            $_SESSION['success'] = "Xóa tài liệu thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi khi xóa tài liệu!";
        }
        
        header('Location: ' . BASE_URL . '/index.php?controller=lesson&action=manage&course_id=' . $material['course_id']);
        exit;
    }
}
?>

<?php
// Gọi Model Course để lấy dữ liệu
require_once 'models/Course.php';

class HomeController {
    public function index() {
        // Khởi tạo model và lấy danh sách khóa học
        $courseModel = new Course();
        $courses = $courseModel->getAllApproved();

        // Gọi View và truyền biến $courses sang đó
        require 'views/home/index.php';
    }
}
?>

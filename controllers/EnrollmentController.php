<?php
require_once 'config/Database.php';
require_once 'models/Enrollment.php';

class EnrollmentController {
    private $enrollmentModel;

    public function __construct() {
        $this->enrollmentModel = new Enrollment();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // POST: enroll in a course
    public function enroll() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để đăng ký khóa học.';
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $courseId = intval($_POST['course_id'] ?? 0);
        $studentId = intval($_SESSION['user']['id']);

        if ($courseId <= 0) {
            $_SESSION['error'] = 'Khóa học không hợp lệ.';
            header('Location: index.php?controller=course&action=index');
            exit;
        }

        if ($this->enrollmentModel->isEnrolled($courseId, $studentId)) {
            $_SESSION['warning'] = 'Bạn đã đăng ký khóa học này.';
            header('Location: index.php?controller=course&action=detail&id=' . $courseId);
            exit;
        }

        $ok = $this->enrollmentModel->enroll($courseId, $studentId);

        if ($ok) {
            $_SESSION['success'] = 'Đăng ký khóa học thành công.';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi đăng ký.';
        }

        header('Location: index.php?controller=course&action=detail&id=' . $courseId);
        exit;
    }

    // POST: cancel enrollment
    public function cancel() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thực hiện thao tác.';
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $courseId = intval($_POST['course_id'] ?? 0);
        $studentId = intval($_SESSION['user']['id']);

        if ($courseId <= 0) {
            $_SESSION['error'] = 'Khóa học không hợp lệ.';
            header('Location: index.php?controller=student&action=myCourses');
            exit;
        }

        $ok = $this->enrollmentModel->cancel($courseId, $studentId);

        if ($ok) {
            $_SESSION['success'] = 'Bạn đã hủy đăng ký khóa học.';
        } else {
            $_SESSION['error'] = 'Không thể hủy đăng ký (hoặc chưa đăng ký).';
        }

        header('Location: index.php?controller=student&action=myCourses');
        exit;
    }

    // GET: list student's courses
    public function myCourses() {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $studentId = intval($_SESSION['user']['id']);
        $enrollments = $this->enrollmentModel->getByStudent($studentId);

        require 'views/student/my_courses.php';
    }
}
?>
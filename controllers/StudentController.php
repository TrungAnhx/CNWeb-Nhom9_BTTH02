<?php
require_once 'models/Enrollment.php';

class StudentController {
    private $enrollmentModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->enrollmentModel = new Enrollment();
    }

    public function dashboard() {
        $this->myCourses();
    }

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

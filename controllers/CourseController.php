<?php
class CourseController {
    public function index() {
        require 'views/courses/index.php';
    }
    public function detail() {
        require 'views/courses/detail.php';
    }
}
?>
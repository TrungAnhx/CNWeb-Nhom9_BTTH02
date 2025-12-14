<?php
require_once 'config/Database.php';

class HomeController {
    public function index() {
        $database = new Database();
        $db = $database->getConnection();
        
        $sql = "SELECT c.id, c.title, c.price, c.image, c.level, c.duration_weeks, u.fullname as instructor_name 
                FROM courses c
                LEFT JOIN users u ON c.instructor_id = u.id
                WHERE c.status = 'approved'
                ORDER BY c.created_at DESC";
        
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $courses = [];
        }

        require 'views/home/index.php';
    }
}
?>

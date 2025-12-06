<?php
session_start();

// Autoload basic (nếu không dùng Composer)
spl_autoload_register(function ($class_name) {
    if (file_exists('controllers/' . $class_name . '.php')) {
        require_once 'controllers/' . $class_name . '.php';
    } elseif (file_exists('models/' . $class_name . '.php')) {
        require_once 'models/' . $class_name . '.php';
    } elseif (file_exists('config/' . $class_name . '.php')) {
        require_once 'config/' . $class_name . '.php';
    }
});

// Detect project folder name for XAMPP
$script_name = $_SERVER['SCRIPT_NAME'];
$project_folder = dirname($script_name);
if ($project_folder === '/' || $project_folder === '\\') {
    $project_folder = '';
}

// Simple Router - Support both query string và clean URL
$controller = 'home';
$action = 'index';

// Check if using query string (?controller=&action=)
if (isset($_GET['controller'])) {
    $controller = $_GET['controller'];
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';
} else {
    // Try clean URL
    $request_uri = $_SERVER['REQUEST_URI'];
    $path = str_replace($project_folder, '', parse_url($request_uri, PHP_URL_PATH));
    $path = trim($path, '/');
    
    // Routing rules for clean URL
    if ($path === 'instructor/dashboard' || $path === 'instructor/my-courses') {
        require_once 'controllers/CourseController.php';
        $controllerObj = new CourseController();
        $controllerObj->dashboard();
        exit;
    } elseif (preg_match('#^instructor/courses/edit/(\d+)$#', $path, $matches)) {
        require_once 'controllers/CourseController.php';
        $controllerObj = new CourseController();
        $controllerObj->edit($matches[1]);
        exit;
    } elseif (preg_match('#^instructor/courses/delete/(\d+)$#', $path, $matches)) {
        require_once 'controllers/CourseController.php';
        $controllerObj = new CourseController();
        $controllerObj->delete($matches[1]);
        exit;
    } elseif ($path === 'instructor/login') {
        require_once 'controllers/AuthController.php';
        $controllerObj = new AuthController();
        $controllerObj->instructorLogin();
        exit;
    } elseif ($path === 'logout') {
        require_once 'logout.php';
        exit;
    } elseif ($path === 'test-db' || $path === 'test_db.php') {
        require_once 'test_db.php';
        exit;
    } elseif ($path === 'fake-login' || $path === 'fake_login.php') {
        require_once 'fake_login.php';
        exit;
    }
}

// Load controller
$controllerName = ucfirst($controller) . 'Controller';

if (file_exists("controllers/$controllerName.php")) {
    require_once "controllers/$controllerName.php";
    $controllerObj = new $controllerName();
    
    if (method_exists($controllerObj, $action)) {
        $controllerObj->$action();
    } else {
        echo "Action '$action' not found in '$controllerName'";
    }
} else {
    echo "Controller '$controllerName' not found";
}
?>

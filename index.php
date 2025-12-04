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

// Simple Router
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

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

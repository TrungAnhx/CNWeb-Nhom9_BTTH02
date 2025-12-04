<?php
class AuthController {
    public function login() {
        require 'views/auth/login.php';
    }
    public function register() {
        require 'views/auth/register.php';
    }
}
?>
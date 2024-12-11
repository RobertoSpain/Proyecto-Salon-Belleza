<?php
namespace Rober\SalonBelleza\Controllers;
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class HomeController {
    public function index() {
        require_once __DIR__ . '/../views/Home/home.php';
    }
}

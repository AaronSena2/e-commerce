<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
function require_admin() {
    if(empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
        header('Location: /users/login.php'); exit;
    }
}
?>
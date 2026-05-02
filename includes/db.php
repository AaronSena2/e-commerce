<?php
$host = 'localhost';
$db   = 'YOUR_DB_NAME'; // <-- replace
$user = 'YOUR_DB_USER'; // <-- replace
$pass = 'YOUR_DB_PASS'; // <-- replace
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
$pdo = new PDO($dsn, $user, $pass, $options);
?>
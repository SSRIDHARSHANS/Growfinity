<?php
// Database settings: default XAMPP values. Change them if your setup is different.
$DB_HOST = 'localhost';
$DB_NAME = 'growfinity';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Could not connect to the database. Start MySQL in XAMPP, import database/growfinity.sql, and check includes/db.php.');
}

<?php
// Database configuration for SMS_LP Lesson Plan system
$host = 'localhost';
$db   = 'SMS_LP';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// Table: Lesson_Plan
//
// CREATE TABLE Lesson_Plan (
//     LessonPlan_ID INT AUTO_INCREMENT PRIMARY KEY,
//     CLUSTER NVARCHAR(255),
//     THEME NVARCHAR(255),
//     SUB_THEME NVARCHAR(255),
//     TOPIC NVARCHAR(255),
//     YEAR NVARCHAR(50),
//     `DURATION (minutes)` INT,
//     `INSTRUCTIONAL DESIGN` NVARCHAR(255),
//     `TECHNOLOGY INTEGRATION` NVARCHAR(255),
//     `APPROACH` NVARCHAR(255),
//     `METHOD` NVARCHAR(255),
//     `PARENTAL INVOLVEMENT` NVARCHAR(50)
// );
?>

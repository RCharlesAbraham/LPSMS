<?php
require_once 'config.php';

// Create the database if it doesn't exist
try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db`;
                USE `$db`;
                
                CREATE TABLE IF NOT EXISTS `Lesson_Plan` (
                    `LessonPlan_ID` INT AUTO_INCREMENT PRIMARY KEY,
                    `CLUSTER` NVARCHAR(255),
                    `THEME` NVARCHAR(255),
                    `SUB_THEME` NVARCHAR(255),
                    `TOPIC` NVARCHAR(255),
                    `YEAR` NVARCHAR(50),
                    `DURATION (minutes)` INT,
                    `INSTRUCTIONAL DESIGN` NVARCHAR(255),
                    `TECHNOLOGY INTEGRATION` NVARCHAR(255),
                    `APPROACH` NVARCHAR(255),
                    `METHOD` NVARCHAR(255),
                    `PARENTAL INVOLVEMENT` NVARCHAR(50)
                );");
    
    echo "Database and table created successfully!";
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
?>
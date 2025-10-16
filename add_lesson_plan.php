<?php
require_once 'config.php';

// Start session and validate CSRF token
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token validation failed');
    }
    // Regenerate token after successful validation
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Create the database and table if they don't exist
try {
    $checkDb = new PDO("mysql:host=$host", $user, $pass);
    $checkDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $checkDb->exec("CREATE DATABASE IF NOT EXISTS `$db`");
    
    // Connect to the database
    $checkDb = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    
    // Create table if not exists
    $checkDb->exec("CREATE TABLE IF NOT EXISTS `lesson_plan` (
        `LessonPlan_ID` INT AUTO_INCREMENT PRIMARY KEY,
        `MEDIUM` VARCHAR(255),
        `NAME` VARCHAR(255),
        `TYPE` VARCHAR(255),
        `SUBJECT_CODE` VARCHAR(255),
        `BACKGROUND_COLOR` VARCHAR(7),
        `CLUSTER` VARCHAR(255),
        `THEME` VARCHAR(255),
        `SUB_THEME` VARCHAR(255),
        `TOPIC` VARCHAR(255),
        `YEAR` VARCHAR(50),
        `DURATION (minutes)` INT,
        `INSTRUCTIONAL DESIGN` VARCHAR(255),
        `TECHNOLOGY INTEGRATION` VARCHAR(255),
        `APPROACH` VARCHAR(255),
        `METHOD` VARCHAR(255),
        `PARENTAL INVOLVEMENT` VARCHAR(50)
    )");
} catch (PDOException $e) {
    // Log error but continue - we'll handle connection issues in the main code
    error_log("Database setup error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Collect and sanitize input
        $medium = $_POST['MEDIUM'] ?? '';
        $name = $_POST['NAME'] ?? '';
        $type = $_POST['TYPE'] ?? '';
        $subject_code = $_POST['SUBJECT_CODE'] ?? '';
        $background_color = $_POST['BACKGROUND_COLOR'] ?? '';
        $cluster = $_POST['CLUSTER'] ?? '';
        $theme = $_POST['THEME'] ?? '';
        $sub_theme = $_POST['SUB_THEME'] ?? '';
        $topic = $_POST['TOPIC'] ?? '';
        $year = $_POST['YEAR'] ?? '';
        $duration = $_POST['DURATION'] ?? '';
        $instructional_design = isset($_POST['INSTRUCTIONAL_DESIGN']) ? implode(', ', (array)$_POST['INSTRUCTIONAL_DESIGN']) : '';
        $technology_integration = isset($_POST['TECHNOLOGY_INTEGRATION']) ? implode(', ', (array)$_POST['TECHNOLOGY_INTEGRATION']) : '';
        $approach = isset($_POST['APPROACH']) ? implode(', ', (array)$_POST['APPROACH']) : '';
        $method = isset($_POST['METHOD']) ? implode(', ', (array)$_POST['METHOD']) : '';
        $parental_involvement = $_POST['PARENTAL_INVOLVEMENT'] ?? '';

        $sql = "INSERT INTO lesson_plan (MEDIUM, NAME, TYPE, SUBJECT_CODE, BACKGROUND_COLOR, CLUSTER, THEME, SUB_THEME, TOPIC, YEAR, `DURATION (minutes)`, `INSTRUCTIONAL DESIGN`, `TECHNOLOGY INTEGRATION`, `APPROACH`, `METHOD`, `PARENTAL INVOLVEMENT`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $medium, $name, $type, $subject_code, $background_color,
            $cluster, $theme, $sub_theme, $topic, $year, $duration,
            $instructional_design, $technology_integration, $approach, $method, $parental_involvement
        ]);
        
        // Redirect to prevent resubmission
        header('Location: Index.php?success=Lesson+Plan+Added+Successfully');
        exit;
    } catch (PDOException $e) {
        echo '<div style="text-align:center; padding:20px; background:#ffebee; border:1px solid #f44336; margin:20px; border-radius:5px;">';
        echo '<h2 style="color:#c62828;">Database Error</h2>';
        echo '<p>There was a problem saving your lesson plan: ' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<a href="Untitled-1.html" style="display:inline-block; margin:10px; padding:8px 16px; background:#f44336; color:white; text-decoration:none; border-radius:4px;">Try Again</a>';
        echo '</div>';
        exit;
    }
}
?>

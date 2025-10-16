<?php
require_once 'config.php';

// Create the database and table if they don't exist
try {
    $checkDb = new PDO("mysql:host=$host", $user, $pass);
    $checkDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $checkDb->exec("CREATE DATABASE IF NOT EXISTS `$db`");
    
    // Connect to the database
    $checkDb = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    
    // Create table if not exists
    $checkDb->exec("CREATE TABLE IF NOT EXISTS `Lesson_Plan` (
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
    )");
} catch (PDOException $e) {
    // Log error but continue - we'll handle connection issues in the main code
    error_log("Database setup error: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Collect and sanitize input
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

        $sql = "INSERT INTO Lesson_Plan (CLUSTER, THEME, SUB_THEME, TOPIC, YEAR, `DURATION (minutes)`, `INSTRUCTIONAL DESIGN`, `TECHNOLOGY INTEGRATION`, `APPROACH`, `METHOD`, `PARENTAL INVOLVEMENT`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $cluster, $theme, $sub_theme, $topic, $year, $duration,
            $instructional_design, $technology_integration, $approach, $method, $parental_involvement
        ]);
        
        echo '<div style="text-align:center; padding:20px; background:#e8f5e9; border:1px solid #4caf50; margin:20px; border-radius:5px;">';
        echo '<h2 style="color:#2e7d32;">Lesson Plan Added Successfully!</h2>';
        echo '<p>Your lesson plan has been saved to the database.</p>';
        echo '<a href="view_lesson_plans.php" style="display:inline-block; margin:10px; padding:8px 16px; background:#4caf50; color:white; text-decoration:none; border-radius:4px;">View All Lesson Plans</a>';
        echo '<a href="Untitled-1.html" style="display:inline-block; margin:10px; padding:8px 16px; background:#2196f3; color:white; text-decoration:none; border-radius:4px;">Add Another Lesson Plan</a>';
        echo '</div>';
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

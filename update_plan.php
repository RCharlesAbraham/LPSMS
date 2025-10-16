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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Check if ID is provided
        if (!isset($_POST['LessonPlan_ID']) || empty($_POST['LessonPlan_ID'])) {
            header('Location: Index.php?error=No ID provided');
            exit();
        }

        $planId = $_POST['LessonPlan_ID'];
        
        // Validate that the ID is numeric
        if (!is_numeric($planId)) {
            header('Location: Index.php?error=Invalid ID');
            exit();
        }

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
        $teaching_aids = $_POST['TEACHING_AIDS'] ?? '';

        // Update the lesson plan
        $sql = "UPDATE lesson_plan SET 
                CLUSTER = ?, 
                THEME = ?, 
                SUB_THEME = ?, 
                TOPIC = ?, 
                YEAR = ?, 
                `DURATION (minutes)` = ?, 
                `INSTRUCTIONAL DESIGN` = ?, 
                `TECHNOLOGY INTEGRATION` = ?, 
                `APPROACH` = ?, 
                `METHOD` = ?, 
                `PARENTAL INVOLVEMENT` = ?
                WHERE LessonPlan_ID = ?";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $cluster, $theme, $sub_theme, $topic, $year, $duration,
            $instructional_design, $technology_integration, $approach, $method, $parental_involvement,
            $planId
        ]);
        
        if ($result && $stmt->rowCount() > 0) {
            // Success - redirect back to index with success message
            header('Location: Index.php?success=Lesson plan updated successfully');
        } else {
            // No rows affected - lesson plan not found
            header('Location: Index.php?error=Lesson plan not found or no changes made');
        }
        
    } catch (PDOException $e) {
        // Database error
        header('Location: Index.php?error=Database error: ' . urlencode($e->getMessage()));
    }
} else {
    // Not a POST request
    header('Location: Index.php?error=Invalid request method');
}

exit();
?>

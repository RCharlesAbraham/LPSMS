<?php
require_once 'config.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: Index.php?error=No ID provided');
    exit();
}

$planId = $_GET['id'];

try {
    // Validate that the ID is numeric
    if (!is_numeric($planId)) {
        header('Location: Index.php?error=Invalid ID');
        exit();
    }
    
    // Prepare and execute the delete query
    $stmt = $pdo->prepare("DELETE FROM Lesson_Plan WHERE LessonPlan_ID = ?");
    $result = $stmt->execute([$planId]);
    
    if ($result && $stmt->rowCount() > 0) {
        // Success - redirect back to index with success message
        header('Location: Index.php?success=Lesson plan deleted successfully');
    } else {
        // No rows affected - lesson plan not found
        header('Location: Index.php?error=Lesson plan not found');
    }
    
} catch (PDOException $e) {
    // Database error
    header('Location: Index.php?error=Database error: ' . urlencode($e->getMessage()));
}

exit();
?>

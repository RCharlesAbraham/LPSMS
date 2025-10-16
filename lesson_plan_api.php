<?php
require_once 'config.php';

header('Content-Type: application/json');

function json_response($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';

    if ($action === 'list') {
        $stmt = $pdo->query("SELECT * FROM `Lesson_Plan` ORDER BY `LessonPlan_ID` DESC");
        $rows = $stmt->fetchAll();
        json_response([ 'success' => true, 'data' => $rows ]);
    }

    if ($action === 'get') {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) json_response([ 'success' => false, 'error' => 'Invalid ID' ], 400);
        $stmt = $pdo->prepare("SELECT * FROM `Lesson_Plan` WHERE `LessonPlan_ID` = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) json_response([ 'success' => false, 'error' => 'Not found' ], 404);
        json_response([ 'success' => true, 'data' => $row ]);
    }

    if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['LessonPlan_ID']) ? (int)$_POST['LessonPlan_ID'] : 0;
        if ($id <= 0) json_response([ 'success' => false, 'error' => 'Invalid ID' ], 400);

        $cluster = $_POST['CLUSTER'] ?? '';
        $theme = $_POST['THEME'] ?? '';
        $sub_theme = $_POST['SUB_THEME'] ?? '';
        $topic = $_POST['TOPIC'] ?? '';
        $year = $_POST['YEAR'] ?? '';
        $duration = $_POST['DURATION_minutes'] ?? $_POST['DURATION (minutes)'] ?? '';
        $instructional_design = $_POST['INSTRUCTIONAL_DESIGN'] ?? '';
        $technology_integration = $_POST['TECHNOLOGY_INTEGRATION'] ?? '';
        $approach = $_POST['APPROACH'] ?? '';
        $method = $_POST['METHOD'] ?? '';
        $parental_involvement = $_POST['PARENTAL_INVOLVEMENT'] ?? '';

        $sql = "UPDATE `Lesson_Plan`
                SET `CLUSTER` = ?, `THEME` = ?, `SUB_THEME` = ?, `TOPIC` = ?, `YEAR` = ?,
                    `DURATION (minutes)` = ?, `INSTRUCTIONAL DESIGN` = ?, `TECHNOLOGY INTEGRATION` = ?,
                    `APPROACH` = ?, `METHOD` = ?, `PARENTAL INVOLVEMENT` = ?
                WHERE `LessonPlan_ID` = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $cluster, $theme, $sub_theme, $topic, $year, $duration,
            $instructional_design, $technology_integration, $approach, $method, $parental_involvement,
            $id
        ]);
        json_response([ 'success' => true ]);
    }

    if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['LessonPlan_ID']) ? (int)$_POST['LessonPlan_ID'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);
        if ($id <= 0) json_response([ 'success' => false, 'error' => 'Invalid ID' ], 400);
        $stmt = $pdo->prepare("DELETE FROM `Lesson_Plan` WHERE `LessonPlan_ID` = ?");
        $stmt->execute([$id]);
        json_response([ 'success' => true ]);
    }

    json_response([ 'success' => false, 'error' => 'Unknown action' ], 400);
} catch (PDOException $e) {
    json_response([ 'success' => false, 'error' => $e->getMessage() ], 500);
}
?>



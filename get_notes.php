<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();
$user = getCurrentUser();

$project_id = intval($_GET['project_id'] ?? 0);

$stmt = $conn->prepare("SELECT id, content FROM temp_notes WHERE user_id = ? AND project_id = ? ORDER BY created_at DESC");
$stmt->bind_param("ii", $user['id'], $project_id);
$stmt->execute();
$result = $stmt->get_result();

$notes = [];
while ($row = $result->fetch_assoc()) {
    $notes[] = $row;
}

header('Content-Type: application/json');
echo json_encode($notes);

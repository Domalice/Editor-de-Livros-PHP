<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();
$user = getCurrentUser();

$content = $_POST['content'] ?? '';
$project_id = intval($_POST['project_id'] ?? 0);

if (!empty($content)) {
    $stmt = $conn->prepare("INSERT INTO temp_notes (user_id, project_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user['id'], $project_id, $content);
    $stmt->execute();
}

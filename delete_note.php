<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();
$user = getCurrentUser();

$note_id = intval($_POST['id'] ?? 0);

// Only delete notes that belong to the logged-in user
$stmt = $conn->prepare("DELETE FROM temp_notes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $note_id, $user['id']);
$stmt->execute();

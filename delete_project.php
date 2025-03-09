<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();

$user = getCurrentUser();

$project_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($project_id <= 0) {

    header("Location: my_projects.php");
    exit;
}

$stmt = $conn->prepare("SELECT id FROM projects WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $project_id, $user['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {

    header("Location: my_projects.php");
    exit;
}


$stmt = $conn->prepare("DELETE FROM projects WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $project_id, $user['id']);

if ($stmt->execute()) {

    header("Location: my_projects.php?success=Projeto excluído com sucesso!");
} else {

    header("Location: view_project.php?id={$project_id}&error=Erro ao excluir projeto.");
}
exit;
?>
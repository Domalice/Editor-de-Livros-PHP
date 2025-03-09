<?php
require_once 'config.php';
require_once 'auth.php';

// Ensure user is logged in
requireLogin();

// Get current user data
$user = getCurrentUser();

// Get project ID from URL parameter
$project_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($project_id <= 0) {
    // Invalid project ID
    header("Location: my_projects.php");
    exit;
}

// Get project data, ensuring it belongs to the current user
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $project_id, $user['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    // Project not found or doesn't belong to current user
    header("Location: my_projects.php");
    exit;
}

$project = $result->fetch_assoc();

$error = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Basic validation
    if (empty($title)) {
        $error = "O título é obrigatório";
    } elseif (empty($content)) {
        $error = "O conteúdo é obrigatório";
    } else {
        // Update project
        $stmt = $conn->prepare("UPDATE projects SET title = ?, content = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssii", $title, $content, $project_id, $user['id']);

        if ($stmt->execute()) {
            // Redirect to view project page
            header("Location: view_project.php?id={$project_id}&success=Projeto atualizado com sucesso!");
            exit;
        } else {
            $error = "Erro ao atualizar projeto: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Projeto</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <main>
        <section>
            <div class="div-relative">
                <picture>
                    <img src="assets/img/livro-aberto-tela-login.jpg" alt="Livro aberto com uma tinta e caneta pena.">
                </picture>
                <div class="container div-absolute custom-size2">
                    <div class="d-flex">
                        <h1>Editar Projeto</h1>

                        <?php if (!empty($error)): ?>
                            <div class="error"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $project_id); ?>" method="post">
                            <div class="form-group">
                                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
                            </div>

                            <div class="form-group">
                                <textarea id="content" name="content" class="editor" required><?php echo htmlspecialchars($project['content']); ?></textarea>
                            </div>

                            <div class="button-container">
                                <button type="submit" class="button-form">Salvar Alterações</button>
                                <a href="view_project.php?id=<?php echo $project_id; ?>" class="button button-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
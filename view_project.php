<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();

$user = getCurrentUser();

$project_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$success = isset($_GET['success']) ? $_GET['success'] : '';

if ($project_id <= 0) {
    header("Location: my_projects.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $project_id, $user['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: my_projects.php");
    exit;
}

$project = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <main>
        <section>
            <div class="div-relative">
                <picture>
                    <img src="assets/img/livro-aberto-tela-login.jpg" alt="Livro aberto com uma tinta e caneta pena.">
                </picture>
                <div class="container div-absolute">
                    <div class="d-flex">
                        <?php if (!empty($success)): ?>
                            <div class="success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <div class="project-header">
                            <h1><?php echo htmlspecialchars($project['title']); ?></h1>
                            <div class="project-actions">
                                <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="action-btn edit-btn" title="Editar projeto">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="action-btn delete-btn" title="Excluir projeto" onclick="openDeleteModal()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="project-content">
                            <?php echo nl2br(htmlspecialchars($project['content'])); ?>
                        </div>

                        <div class="project-meta">
                            <div>Criado em: <?php echo date('d/m/Y H:i', strtotime($project['created_at'])); ?></div>
                            <?php if ($project['updated_at'] != $project['created_at']): ?>
                                <div>Atualizado em: <?php echo date('d/m/Y H:i', strtotime($project['updated_at'])); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="button-container">
                            <a href="my_projects.php" class="button button-secondary">Voltar para Meus Projetos</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h2>Confirmar Exclusão</h2>
            <p>Tem certeza que deseja excluir este projeto? Esta ação não pode ser desfeita.</p>
            <div class="modal-buttons">
                <button class="button button-secondary" onclick="closeDeleteModal()">Cancelar</button>
                <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="button" style="background-color: #f44336;">Excluir</a>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal() {
            document.getElementById('deleteModal').style.display = 'block';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        window.onclick = function(event) {
            var modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                closeDeleteModal();
            }
        }
    </script>
</body>

</html>
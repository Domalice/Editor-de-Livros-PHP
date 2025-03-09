<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();


$user = getCurrentUser();


$success = isset($_GET['success']) ? $_GET['success'] : '';


$stmt = $conn->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$result = $stmt->get_result();
$projects = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Projects</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
                        <h1>Meus Projetos</h1>

                        <?php if (!empty($success)): ?>
                            <div class="success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <?php if (empty($projects)): ?>
                            <p>Você ainda não tem nenhum projeto.</p>
                        <?php else: ?>
                            <ul class="projects-list">
                                <?php foreach ($projects as $project): ?>
                                    <li class="project-item">
                                        <a href="view_project.php?id=<?php echo $project['id']; ?>" class="project-link">
                                            <h3 class="project-title"><?php echo htmlspecialchars($project['title']); ?></h3>
                                            <p class="project-date">Criado em: <?php echo date('d/m/Y H:i', strtotime($project['created_at'])); ?></p>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <div class="button-container text-center" style="margin-top: 20px;">
                            <a href="create_project.php" class="button">Criar Novo Projeto</a>
                            <a href="welcome.php" class="button button-secondary">Voltar para o Início</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

</body>

</html>
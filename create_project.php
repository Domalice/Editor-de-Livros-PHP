<?php
require_once 'config.php';
require_once 'auth.php';

// Ensure user is logged in
requireLogin();

// Get current user data
$user = getCurrentUser();

$error = "";
$success = "";

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
        // Insert new project
        $stmt = $conn->prepare("INSERT INTO projects (user_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user['id'], $title, $content);

        if ($stmt->execute()) {
            // Redirect to my projects page
            header("Location: my_projects.php?success=Projeto criado com sucesso!");
            exit;
        } else {
            $error = "Erro ao criar projeto: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Novo Projeto</title>
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
                        <h1>Criar Novo Projeto</h1>

                        <?php if (!empty($error)): ?>
                            <div class="error"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <input type="text" id="title" name="title" placeholder="Título do projeto" required>
                            </div>

                            <div class="form-group">
                                <textarea id="content" name="content" class="editor" placeholder="Conteúdo do projeto" required></textarea>
                            </div>

                            <div class="text-center" style="margin-top: 20px;">
                                <button type="submit" class="button-form">Salvar Projeto</button>
                                <a href="my_projects.php" class="button button-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Notes Sidebar -->
    <div id="note-sidebar" style="display:none; position:fixed; top:20%; right:0; background:#f9f9f9; width:300px; height:300px; padding:15px; box-shadow: -2px 0 5px rgba(0,0,0,0.1); z-index:1000;">
        <textarea id="note-content" style="width:100%; height:200px;"></textarea>
        <button onclick="saveNote()">Save Note</button>
    </div>

    <script src="js/script.js"></script>

</body>

</html>
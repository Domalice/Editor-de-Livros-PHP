<?php
require_once 'config.php';
require_once 'auth.php';

requireLogin();

$user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
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
                        <h1>Bem-vindo, <span class="initial-capslock"><?php echo htmlspecialchars($user['username']); ?></span>!</h1>

                        <div class="button-container text-center" style="margin-top: 20px;">
                            <a href="my_projects.php" class="button">Meus Projetos</a>
                            <a href="logout.php" class="button button-secondary">Sair</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

</body>

</html>
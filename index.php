<?php
require_once 'config.php';
require_once 'auth.php';

if (isLoggedIn()) {
    header("Location: welcome.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                session_regenerate_id();

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: welcome.php");
                exit;
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "User not found";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor de Livros - Login</title>
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


                    <?php if (!empty($error)): ?>
                        <div class="error"><?php echo $error; ?></div>
                    <?php endif; ?>


                    <div class="d-flex">
                    <h1>Editor de Livros</h1>
                        <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <input type="text" id="username" name="username" placeholder="Usuário" required>
                            </div>
                            <div class="form-group">
                                <input type="password" id="password" name="password" placeholder="Senha" required>
                            </div>
                            <div class="text-center">
                                <button class="button-form" type="submit">Login</button>
                            </div>
                        </form>
                    </div>
                    <div class="text-center" style="margin-top: 20px;">
                        <p>Não tem uma conta? <a href="register.php" class="button button-secondary">Registrar</a></p>
                    </div>

                </div>
            </div>


        </section>

    </main>
</body>

</html>
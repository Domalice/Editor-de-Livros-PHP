<?php
require_once 'config.php';
require_once 'auth.php';

if (isLoggedIn()) {
    header("Location: welcome.php");
    exit;
}

$error = "";
$success = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "É necessário preencher todos os campos";
    } elseif ($password !== $confirm_password) {
        $error = "As senhas não são iguais";
    } elseif (strlen($password) < 6) {
        $error = "A senha tem que ter pelo menos 6 caracteres";
    } else {

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Usuário já existe";
        } else {

            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Email já existe";
            } else {

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);


                $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed_password);

                if ($stmt->execute()) {
                    $success = "Conta criada! Agora você pode entrar.";
                } else {
                    $error = "Error: " . $stmt->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <main>
        <section>
            <div class="div-relative">
                <picture>
                    <img src="assets/img/livro-aberto-tela-login.jpg" alt="Livro aberto com uma tinta e caneta pena.">
                </picture>
                <div class="container div-absolute custom-size">
                    <h1>Registrar uma conta</h1>

                    <?php if (!empty($error)): ?>
                        <div class="error"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <div class="d-flex">
                        <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <input type="text" id="username" name="username" placeholder="Usuário" required>
                            </div>
                            <div class="form-group">
                                <input type="email" id="email" name="email" placeholder="E-mail" required>
                            </div>
                            <div class="form-group">
                                <input type="password" id="password" name="password" placeholder="Senha" required>
                            </div>
                            <div class="form-group">
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirme sua senha" required>
                            </div>
                            <div class="text-center">
                                <button class="button-login" type="submit">Registrar</button>
                            </div>
                        </form>
                    </div>

                    <div class="text-center" style="margin-top: 20px;">
                        <p>Já tem uma conta? <a href="index.php" class="button button-secondary">Entrar</a></p>
                    </div>
                </div>
            </div>
        </section>
    </main>

</body>

</html>
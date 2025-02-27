<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'conexao.php';

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header("Location: dashboard.php");
        exit();
    } else {
        $erro = "Email ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Restaurante Universitário</title>
    <link rel="stylesheet" href="style.css">
    <?php include 'config.php' ?>
</head>

<body>
    <div class="topo">
        <div class="voltar">
            <a href="index.php">
                <img src="midia/voltar.png" alt="">
                <p>Voltar</p>
            </a>
        </div>
    </div>
    <div class="topo">
        <img src="midia/QrMeal1.png" alt="">
    </div>
    <h2>Esqueceu a senha</h2>
    <div class="info">
        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        <!-- <div class="input"> -->
            <form method="POST">
                <label for="Nova senha">Senha:</label>
                <input type="password" id="senha" name="senha" placeholder="Digite a nova senha" required>
                
                <label for="confirmacao">Confirme a senha:</label>
                <input type="password" id="confirmacao" name="confirmacao" placeholder="Repita a senha" required>

                <button class="btwhite" type="submit">Entrar</button>
            </form>
        <!-- </div> -->
    </div>
</body>

</html>
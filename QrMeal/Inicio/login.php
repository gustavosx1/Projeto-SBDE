<?php
session_start();
// if (isset($_SESSION['usuario_id'])) {
//     header("Location: ../Principal/menu.php");
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'conexao.php';

    $matricula = $_POST['matricula'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM pessoa WHERE matricula = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header("Location: ../Principal/menu.php");
        exit();
    } else {
        $erro = "Matrícula ou senha incorretas.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Restaurante Universitário</title>
    <link rel="stylesheet" href="../style.css">
    <?php include 'config.php' ?>
</head>

<body>
    <div class="topo">
        <div class="voltar">
            <a href="index.php">
                <img src="../midia/voltar.png" alt="">
                <p>Voltar</p>
            </a>
        </div>
    </div>
    <div class="topo">
        <img src="../midia/QrMeal1.png" alt="">
    </div>
    <h2>Login</h2>
    <div class="info">
        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        <!-- <div class="input"> -->
        <form method="POST">
            <label for="matricula">Matrícula:</label>
            <input type="matricula" id="matricula" name="matricula" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <a href="codigoSenha.php">Esqueceu a senha?</a>
            <button class="btwhite" type="submit">Entrar</button>
        </form>
        <p class="aviso">Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
        <!-- </div> -->
    </div>
</body>

</html>
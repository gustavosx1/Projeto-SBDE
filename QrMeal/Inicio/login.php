<?php
session_start();
// if (isset($_SESSION['usuario_id'])) {
//     header("Location: ../Principal/menu.php");
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../Banco de Dados/conexao.php';

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Alterando a consulta para usar a tabela 'pessoa'
    $stmt = $pdo->prepare("SELECT * FROM pessoa WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['idPessoa'];  // Usando o idPessoa da tabela 'pessoa'
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header("Location: ../Principal/menu.php");
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
    <link rel="stylesheet" href="../style.css">
    <?php include 'config.php' ?>
</head>

<body>
    <div class="topo mgtop fullW">
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
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <a href="codigoSenha.php">Esqueceu a senha?</a>
            <button class="btwhite" type="submit">Entrar</button>
        </form>
        <p class="aviso">Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
    </div>
    <div class="scroll-indicator">
        <img class="arrow" src="../midia/setinha.png"></img>
        <script src="../setinha.js"></script>
    </div>
</body>

</html>

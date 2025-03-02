<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'conexao.php';

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $matricula = $_POST['matricula'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, matricula, senha) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $matricula, $senha]);
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $erro = "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Restaurante Universitário</title>
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
    <div class="info">
        <h2>Cadastro</h2>
        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="matricula">Matrícula:</label>
            <input type="text" id="matricula" name="matricula" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <p class="aviso">Já tem uma conta? <a href="login.php">Faça login</a></p>
            <button class="btwhite" type="submit">Cadastrar</button>
        </form>
    </div>
</body>

</html>
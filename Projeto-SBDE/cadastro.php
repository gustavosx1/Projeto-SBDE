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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro</h1>
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

            <button type="submit">Cadastrar</button>
        </form>
        <p>Já tem uma conta? <a href="index.php">Faça login</a></p>
    </div>
</body>
</html>
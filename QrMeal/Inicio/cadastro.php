<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'conexao.php';

    $matricula = $_POST['matricula']; // Captura a matrícula do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $tipoAuxilio = $_POST['tipoAuxilio'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

    try {
        // Insere o novo usuário com a matrícula
        $stmt = $pdo->prepare("INSERT INTO pessoa (matricula, nome, email, telefone, tipoAuxilio, senha) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$matricula, $nome, $email, $telefone, $tipoAuxilio, $senha]);

        header("Location: login.php");
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
    <?php include 'config.php'; ?>
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
            <label for="matricula">Matrícula:</label>
            <input type="text" id="matricula" name="matricula" required>

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="telefone">Telefone:</label>
            <input type="number" id="telefone" name="telefone">

            <label for="tipoAuxilio">Possui auxílio?:</label>
            <select id="tipoAuxilio" name="tipoAuxilio">
                <option value="0">Não</option>
                <option value="1">Sim</option>
            </select>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <p class="aviso">Já tem uma conta? <a href="login.php">Faça login</a></p>
            <button class="btwhite" type="submit">Cadastrar</button>
        </form>
    </div>
</body>

</html>

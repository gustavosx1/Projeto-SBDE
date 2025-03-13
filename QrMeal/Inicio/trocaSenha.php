<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../Banco de Dados/conexao.php';

    // Recuperando o código e as senhas do formulário
    $codigoConfirmacao = $_POST['confirmacao'];
    $novaSenha = $_POST['senha'];

    // Verificar se o código de confirmação corresponde ao código armazenado na sessão
    if (isset($_SESSION['codigo_recuperacao']) && $_SESSION['codigo_recuperacao'] == $codigoConfirmacao) {
        // Verificar se as senhas coincidem
        if ($_POST['senha'] == $_POST['confirmacao']) {
            $senhaHash = password_hash($novaSenha, PASSWORD_BCRYPT);
            $email = $_SESSION['email_recuperacao'];

            // Atualizar a senha no banco de dados
            $stmt = $pdo->prepare("UPDATE pessoa SET senha = ? WHERE email = ?");
            $stmt->execute([$senhaHash, $email]);

            // Limpar a sessão
            unset($_SESSION['codigo_recuperacao']);
            unset($_SESSION['email_recuperacao']);

            // Redirecionar para a página de login após a mudança de senha
            header("Location: login.php");
            exit();
        } else {
            $erro = "As senhas não coincidem.";
        }
    } else {
        $erro = "Código de recuperação incorreto.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Troca de Senha</title>
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
    <h2>Troca de Senha</h2>
    <div class="info">
        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="senha">Nova senha:</label>
            <input type="password" id="senha" name="senha" placeholder="Digite a nova senha" required>

            <label for="confirmacao">Confirme a senha:</label>
            <input type="password" id="confirmacao" name="confirmacao" placeholder="Repita a senha" required>

            <button class="btwhite" type="submit">Alterar Senha</button>
        </form>
    </div>
</body>

</html>

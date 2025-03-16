<?php
session_start();
require '../Banco de Dados/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        $stmt = $pdo->prepare("SELECT * FROM pessoa WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            $codigoRecuperacao = rand(100000, 999999);  // Gerar código aleatório

            $_SESSION['codigo_recuperacao'] = $codigoRecuperacao;
            $_SESSION['email_recuperacao'] = $email;

            $assunto = "Código de Recuperação de Senha";
            $mensagem = "Seu código de recuperação é: $codigoRecuperacao";
            $headers = "From: samuelsa@acad.ifma.edu.br";

            mail($email, $assunto, $mensagem, $headers);

            header("Location: trocaSenha.php");
            exit();
        } else {
            $erro = "E-mail não encontrado!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha</title>
    <link rel="stylesheet" href="../style.css">
    <?php include 'config.php' ?>
</head>
<body>
    <div class="topo fullW">
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
    <h2>Esqueceu a senha</h2>
    <div class="info">
        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <button class="btwhite" type="submit">Receber código</button>
        </form>
    </div>
</body>
</html>

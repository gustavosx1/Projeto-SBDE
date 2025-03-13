<?php
session_start();
require '../Banco de Dados/conexao.php'; // A conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Etapa 1: O usuário fornece o e-mail
    if (isset($_POST['email'])) {
        $email = $_POST['email'];

        // Verificar se o e-mail existe no banco de dados
        $stmt = $pdo->prepare("SELECT * FROM pessoa WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            // Etapa 2: Gerar código de recuperação e enviar ao e-mail
            $codigoRecuperacao = rand(100000, 999999);  // Gerar código aleatório

            // Armazenar o código na sessão para validação posterior
            $_SESSION['codigo_recuperacao'] = $codigoRecuperacao;
            $_SESSION['email_recuperacao'] = $email;  // Armazenar o e-mail para a troca de senha

            // Enviar o código por e-mail (garanta que a função mail() esteja configurada corretamente no seu servidor)
            $assunto = "Código de Recuperação de Senha";
            $mensagem = "Seu código de recuperação é: $codigoRecuperacao";
            $headers = "From: samuelsa@acad.ifma.edu.br"; // Modifique para o e-mail do seu domínio

            // Enviar o e-mail
            mail($email, $assunto, $mensagem, $headers);

            // Redirecionar para a página de confirmação do código
            header("Location: trocaSenha.php");
            exit();
        } else {
            // Se o e-mail não for encontrado
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

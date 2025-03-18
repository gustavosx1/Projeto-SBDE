<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    // session_destroy();
    header("Location: ../Inicio/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Restaurante Universitário</title>
    <link rel="stylesheet" href="../style.css">
    <?php include '../Inicio/config.php' ?>
</head>

<body>
    <div class="topo white separa fullW">
        <img id="logo" src="../midia/QrMeal1.png" alt="">
        <a id="sair" href="../Inicio/index.php">
            <img src="../midia/Sair.png" alt="">
            <p>Sair</p>
        </a>
    </div>
    <h3 class="colorWhite">Funcionário</h3>
    <h2 class="colorWhite">Bem-vindo<br><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h2>
    <div class="info menu">
        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        <form method="POST">
            <a class="btwhite button" href="perfilFunc.php">Meu perfil</a>
            <a class="btwhite button" href="../Ticket/lerTicket.php">Ler tickets</a>
            <a class="btwhite button padbot" href="sobreFunc.php">Sobre</a>
        </form>
    </div>
</body>

</html>
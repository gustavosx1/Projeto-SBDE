<?php

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
    <h3 class="colorWhite">Estudante</h3>
    <div class="info menu">
        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        <form method="POST">
            <a class="btwhite button" href="perfil.php">Meu perfil</a>
            <a class="btwhite button" href="../Pagamento/metodo.php">Comprar ticket</a>
            <a class="btwhite button" href="ticket.php">Meus Tickets</a>
            <a class="btwhite button mgbot" href="sobre.php">Sobre</a>
        </form>
    </div>
</body>

</html>
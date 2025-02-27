<?php

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Restaurante Universitário</title>
    <link rel="stylesheet" href="style.css">
    <?php include 'config.php' ?>
</head>

<body>
    <div class="topo white">
        <img id="logo" src="midia/QrMeal1.png" alt="">
        <a id="sair" href="login.php">
            <img src="midia/Sair.png" alt="">
            <p>Sair</p>
        </a>
    </div>
    <h3 class="white">Estudante</h3>
    <div class="info menu">
        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        <!-- <div class="input"> -->
        <form method="POST">
            <a class="btwhite button" href="">Meu perfil</a>
            <a class="btwhite button" href="">Comprar ticket</a>
            <a class="btwhite button" href="">Tickets</a>
            <a class="btwhite button" href="">Sobre</a>
        </form>
        <!-- </div> -->
    </div>
</body>

</html>
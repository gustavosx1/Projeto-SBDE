
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
    <h2>Esqueceu a senha</h2>
    <div class="info">
        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        <!-- <div class="input"> -->
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="confirmacao">Código de recuperação:</label>
            <div class="codigo">
                <input type="number" id="confirmacao" name="confirmacao" required>
                <button class="btwhite" type="submit">Receber código</button>
            </div>

            <button class="btwhite" type="submit">Continuar</button>
        </form>
        <!-- </div> -->
    </div>
</body>

</html>
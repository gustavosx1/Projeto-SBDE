<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Restaurante Universitário</title>
    <link rel="stylesheet" href="../style.css">
    <?php include '../Inicio/config.php' ?>
</head>

<body class="">
    <div class="topo">
        <div class="voltar">
            <a href="menu.php">
                <img src="../midia/voltar.png" alt="">
                <p>Voltar</p>
            </a>
        </div>
    </div>
    <div class="info">
        <h3 style="color: white;">Meus Tickets</h3>
        <div class="button ticket">
            <p>Compra: <strong>12/02/2025 - 11:45</strong></p>
            <h2>Código: <span>ABCD1234</span></h2>
            <p>Validade: 14/03/2025 - 11:45</p>
            <button class="button btwhite qrcode" onclick="window.location='mostrarTicket.php'">
                <img src="../midia/qrcode.png" alt="">
                <p>
                    Ver QRCODE
                </p>
            </button>
        </div>
        <div class="button ticket desativado">
            <p>Compra: <strong>12/02/2025 - 11:45</strong></p>
            <h2>Código: <span>ABCD1234</span></h2>
            <p>Validade: 14/03/2025 - 11:45</p>
            <button class="button btwhite qrcode">
                <img src="../midia/qrcode.png" alt="">
                <p>
                    Ver QRCODE
                </p>
            </button>
        </div>
    </div>
</body>

</html>
<?php
$ticket_id = "123456";
$estudante = "João Silva";
$data = "2025-02-28";
$hora = "12:00";
$codigo = "ABC123";

$dados = json_encode([
    "ticket_id" => $ticket_id,
    "estudante" => $estudante,
    "data" => $data,
    "hora" => $hora,
    "codigo" => $codigo
]);

$qr_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($dados) . "&size=200x200";
?>

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
    <div class="topo fullW">
        <div class="voltar">
            <a href="../Principal/ticket.php">
                <img src="../midia/voltar.png" alt="">
                <p>Voltar</p>
            </a>
        </div>
    </div>
    <div class="info">
        <h3 style="color: white;">Ticket</h3>
        <div class="button btwhite padtop">
            <img src="<?php echo $qr_url; ?>" alt="QR Code do Ticket">
            <p class="padtop">Código:</p>
            <h2 id="codQRCODE" style="color: #2f9e41 !important;">23262632</h2>
        </div>
    </div>
</body>

</html>
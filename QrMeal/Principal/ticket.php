<?php
session_start();
require '../Banco de Dados/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../Inicio/login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

try {
    $stmt = $pdo->prepare("
        SELECT t.idTicket, t.dataTicket, t.dataValidade, t.valorTicket
        FROM pagamento p
        INNER JOIN ticket t ON p.ticket_idTicket = t.idTicket
        WHERE p.pessoa_idPessoa = ?
        ORDER BY t.dataTicket DESC
        LIMIT 5
    ");
    $stmt->execute([$usuario_id]);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar tickets: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Tickets - Restaurante Universitário</title>
    <link rel="stylesheet" href="../style.css">
    <?php include '../Inicio/config.php' ?>
</head>

<body>
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
        <?php if (count($tickets) > 0): ?>
            <?php foreach ($tickets as $ticket): ?>
                <div class="button ticket">
                    <p>Compra: <strong><?php echo date('d/m/Y - H:i', strtotime($ticket['dataTicket'])); ?></strong></p>
                    <h2>Código: <span><?php echo htmlspecialchars($ticket['idTicket']); ?></span></h2>
                    <p>Validade: <?php echo date('d/m/Y - H:i', strtotime($ticket['dataValidade'])); ?></p>
                    <button class="button btwhite qrcode" onclick="window.location='../Ticket/mostrarTicket.php?id=<?php echo $ticket['idTicket']; ?>'">
                        <img src="../midia/qrcode.png" alt="">
                        <p>Ver QRCODE</p>
                    </button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: white;">Nenhum ticket encontrado.</p>
        <?php endif; ?>
    </div>
</body>

</html>

<!-- <div class="topo fullW">
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
        <button class="button btwhite qrcode" onclick="window.location='../Ticket/mostrarTicket.php'">
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
</div> -->
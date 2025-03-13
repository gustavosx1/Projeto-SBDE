<?php
session_start();
require '../Inicio/conexao.php';

// Verificar se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../Login/login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

try {
    //busca os tickets do usuário logado
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

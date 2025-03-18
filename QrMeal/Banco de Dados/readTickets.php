<?php
session_start();
require '../Banco de Dados/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../Inicio/login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

try {
    // Consulta para obter todos os tickets do usuário
    $sql = "
        SELECT t.idTicket, t.dataTicket, t.dataValidade, t.valorTicket, t.utilizado
        FROM ticket t
        WHERE t.idPessoa = ?";  // Filtrar pelos tickets do usuário

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);

    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao buscar tickets: " . $e->getMessage());
}

// Verifica se o botão de "marcar como utilizado" foi clicado
if (isset($_GET['utilizar_ticket_id'])) {
    $ticket_id = $_GET['utilizar_ticket_id'];

    try {
        // Atualiza o ticket para marcar como utilizado (utilizado = 1)
        $update_sql = "UPDATE ticket SET utilizado = 1 WHERE idTicket = ? AND idPessoa = ?";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([$ticket_id, $usuario_id]);

        // Redireciona de volta para a página após a atualização
        header("Location: readTickets.php");
        exit();
    } catch (PDOException $e) {
        die("Erro ao atualizar o ticket: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Tickets - Restaurante Universitário</title>
    <link rel="stylesheet" href="../style.css">
    <?php include '../Inicio/config.php'; ?>
</head>

<body>
    <div class="info">
        <h3>Meus Tickets</h3>
        <?php if (count($tickets) > 0): ?>
            <?php foreach ($tickets as $ticket):
                $validade = new DateTime($ticket['dataValidade']);
                $agora = new DateTime();
                $ticket_expirado = $agora > $validade;
                $classe_ticket = $ticket_expirado ? 'desativado' : '';
                $ticket_utilizado = $ticket['utilizado'] == 1;
            ?>
                <div class="button ticket <?php echo $classe_ticket; ?>">
                    <p><strong>Compra:</strong> <?php echo date('d/m/Y', strtotime($ticket['dataTicket'])); ?></p>
                    <h2><strong>Código:</strong> <?php echo htmlspecialchars($ticket['idTicket']); ?></h2>
                    <p><strong>Validade:</strong> <?php echo date('d/m/Y - H:i', strtotime($ticket['dataValidade'])); ?></p>

                    <?php if ($ticket_utilizado): ?>
                        <p style="color: red; font-weight: bold;">Ticket já utilizado</p>
                    <?php elseif (!$ticket_expirado): ?>
                        <button class="button btwhite qrcode" onclick="window.location='../Ticket/mostrarTicket.php?id=<?php echo $ticket['idTicket']; ?>'">
                            <img src="../midia/qrcode.png" alt="QR Code">
                            <p>Ver QRCODE</p>
                        </button>
                        <!-- Botão para marcar como utilizado -->
                        <a href="?utilizar_ticket_id=<?php echo $ticket['idTicket']; ?>" class="button btwhite">
                            Marcar como Utilizado
                        </a>
                    <?php else: ?>
                        <p style="color: red; font-weight: bold;">Ticket expirado</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p style="color: white;">Nenhum ticket encontrado.</p>
        <?php endif; ?>
    </div>
</body>

</html>

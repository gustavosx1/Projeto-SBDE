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
        SELECT * from ticket WHERE pessoa_idPessoa = ?;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $usuario_id, PDO::PARAM_INT);
    $stmt->execute();

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
    <?php include '../Inicio/config.php'; ?>
</head>

<body>
    <div class="topo">
        <div class="voltar">
            <a href="menu.php">
                <img src="../midia/voltar.png" alt="Voltar">
                <p>Voltar</p>
            </a>
        </div>
    </div>

    <div class="info">
        <h3>Meus Tickets</h3>
        <?php if (count($tickets) > 0): ?>
            <?php foreach ($tickets as $ticket): 
                // Define a validade do ticket (até as 19h do dia da compra)
                $data_compra = new DateTime($ticket['dataTicket']);
                $validade = clone $data_compra;
                $validade->setTime(19, 0, 0);

                // Compara com a data atual
                $agora = new DateTime();
                $ticket_expirado = $agora > $validade;
                $classe_ticket = $ticket_expirado ? 'desativado' : '';

                // Verifica se o ticket já foi utilizado
                $ticket_utilizado = $ticket['utilizado'] == 1;
            ?>
                <div class="ticket-item <?php echo $classe_ticket; ?>">
                    <p><strong>Compra:</strong> <?php echo date('d/m/Y - H:i', strtotime($ticket['dataTicket'])); ?></p>
                    <h2><strong>Código:</strong> <?php echo htmlspecialchars($ticket['idTicket']); ?></h2>
                    <p><strong>Validade:</strong> <?php echo date('d/m/Y - H:i', strtotime($ticket['dataValidade'])); ?></p>
                    <p><strong>Valor:</strong> R$ <?php echo number_format($ticket['valorTicket'], 2, ',', '.'); ?></p>

                    <?php if ($ticket_utilizado): ?>
                        <p style="color: red; font-weight: bold;">Ticket já utilizado</p>
                    <?php elseif (!$ticket_expirado): ?>
                        <button class="button btwhite qrcode" onclick="window.location='../Ticket/mostrarTicket.php?id=<?php echo $ticket['idTicket']; ?>'">
                            <img src="../midia/qrcode.png" alt="QR Code">
                            <p>Ver QRCODE</p>
                        </button>
                    <?php else: ?>
                        <p style="color: red; font-weight: bold;">Ticket expirado</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p>Nenhum ticket encontrado.</p>
        <?php endif; ?>
    </div>
</body>

</html>

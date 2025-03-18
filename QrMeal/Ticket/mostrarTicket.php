<?php
// Conexão com o banco de dados
require '../Banco de Dados/conexao.php';
session_start();
if (!isset($_GET['id'])) {
    die("Ticket não encontrado no GET");
}

$ticket_id = $_GET['id'];
try {
    // Buscar informações do ticket
    $stmt = $pdo->prepare("
    SELECT t.idTicket, t.dataTicket, t.dataValidade, t.valorTicket, t.utilizado, p.nome
    FROM ticket t
    JOIN pessoa p ON p.idPessoa = t.idPessoa
    WHERE t.idTicket = ?");
    $stmt->execute([$ticket_id]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        echo $ticket_id . "<br>";
        echo $ticket . "<br>";
        die("Ticket não encontrado no banco");
    }

    // Dados para o QR Code
    $dados = json_encode([
        "ticket_id" => $ticket['idTicket'],
        "estudante" => $ticket['nome'],
        "data" => date('d/m/Y', strtotime($ticket['dataTicket'])),
        "hora" => date('H:i', strtotime($ticket['dataTicket'])),
        "codigo" => $ticket['idTicket']
    ]);

    // Gerar URL para o QR Code
    $qr_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($dados) . "&size=200x200";
} catch (PDOException $e) {
    die("Erro ao buscar ticket: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - Restaurante Universitário</title>
    <link rel="stylesheet" href="../style.css">
    <?php include '../Inicio/config.php'; ?>
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
            <img style="padding-top: 20px !important;" src="<?php echo $qr_url; ?>" alt="QR Code do Ticket">
            <p class="padtop">Código:</p>
            <h2 id="codQRCODE" style="color: #2f9e41 !important; font-size:6vw !important"><?php echo $ticket['idTicket']; ?></h2>
            <p><strong>Estudante:</strong> <?php echo $ticket['nome']; ?></p>
        </div>
    </div>
</body>

</html>
<?php
require '../Banco de Dados/conexao.php';

if (isset($_POST['ticket_id'])) {
    $ticket_id = $_POST['ticket_id'];

    // Buscar o ticket no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM ticket WHERE ticket_id = ?");
    $stmt->execute([$ticket_id]);
    $ticket = $stmt->fetch();

    if ($ticket) {
        // Marcar o ticket como utilizado
        $updateStmt = $pdo->prepare("UPDATE ticket SET utilizado = 1 WHERE ticket_id = ?");
        $updateStmt->execute([$ticket_id]);

        // Retornar os dados do ticket
        echo json_encode([
            'success' => true,
            'ticket' => [
                'ticket_id' => $ticket['ticket_id'],
                'validade' => $ticket['validade'],
                'valor' => $ticket['valor'],
                'status' => 'utilizado'
            ]
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>

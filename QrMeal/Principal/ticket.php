<?php
session_start();
require '../Banco de Dados/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../Inicio/login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Definição da paginação
$por_pagina = 5;
$pagina_atual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_atual - 1) * $por_pagina;

// Verifica se o usuário quer ver apenas tickets não utilizados
$mostrarNaoUtilizados = isset($_GET['nao_utilizados']) ? true : false;

try {
    // Consulta para obter os tickets do usuário
    $sql = "
        SELECT t.idTicket, t.dataTicket, t.dataValidade, t.valorTicket, t.utilizado
        FROM pagamento p
        INNER JOIN ticket t ON p.ticket_idTicket = t.idTicket
        WHERE p.pessoa_idPessoa = ?
    ";

    if ($mostrarNaoUtilizados) {
        $sql .= " AND t.utilizado = 0"; // Filtra apenas tickets não utilizados
    }

    $sql .= " ORDER BY t.dataTicket DESC LIMIT ? OFFSET ?";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $usuario_id, PDO::PARAM_INT);
    $stmt->bindValue(2, (int) $por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(3, (int) $offset, PDO::PARAM_INT);
    $stmt->execute();

    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Contar total de tickets para paginação
    $stmt_total = $pdo->prepare("
        SELECT COUNT(*) as total FROM pagamento p
        INNER JOIN ticket t ON p.ticket_idTicket = t.idTicket
        WHERE p.pessoa_idPessoa = ?
    ");
    
    if ($mostrarNaoUtilizados) {
        $stmt_total = $pdo->prepare("
            SELECT COUNT(*) as total FROM pagamento p
            INNER JOIN ticket t ON p.ticket_idTicket = t.idTicket
            WHERE p.pessoa_idPessoa = ? AND t.utilizado = 0
        ");
    }

    $stmt_total->execute([$usuario_id]);
    $total_tickets = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];

    $total_paginas = ceil($total_tickets / $por_pagina);
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
                <img src="../midia/voltar.png" alt="">
                <p>Voltar</p>
            </a>
        </div>
    </div>

    <div class="filtro">
        <a href="?nao_utilizados=1">
            <button>Mostrar Somente Não Utilizados</button>
        </a>
        <a href="?">
            <button>Mostrar Todos</button>
        </a>
    </div>

    <div class="info">
        <h3 style="color: white;">Meus Tickets</h3>
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
                <div class="button ticket <?php echo $classe_ticket; ?>">
                    <p>Compra: <strong><?php echo date('d/m/Y - H:i', strtotime($ticket['dataTicket'])); ?></strong></p>
                    <h2>Código: <span><?php echo htmlspecialchars($ticket['idTicket']); ?></span></h2>
                    <p>Validade: <?php echo date('d/m/Y - H:i', strtotime($ticket['dataValidade'])); ?></p>
                    
                    <?php if ($ticket_utilizado): ?>
                        <p style="color: red; font-weight: bold;">Ticket já utilizado</p>
                    <?php elseif (!$ticket_expirado): ?>
                        <button class="button btwhite qrcode" onclick="window.location='../Ticket/mostrarTicket.php?id=<?php echo $ticket['idTicket']; ?>'">
                            <img src="../midia/qrcode.png" alt="">
                            <p>Ver QRCODE</p>
                        </button>
                    <?php else: ?>
                        <p style="color: red; font-weight: bold;">Ticket expirado</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <!-- Paginação -->
            <div class="paginacao">
                <?php if ($pagina_atual > 1): ?>
                    <a href="?pagina=<?php echo $pagina_atual - 1; ?>" class="botao">← Anterior</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <a href="?pagina=<?php echo $i; ?>" 
                    class="botao <?php echo ($i == $pagina_atual) ? 'pagina-atual' : ''; ?>">
                    <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($pagina_atual < $total_paginas): ?>
                    <a href="?pagina=<?php echo $pagina_atual + 1; ?>" class="botao">Próxima →</a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <p style="color: white;">Nenhum ticket encontrado.</p>
        <?php endif; ?>
    </div>
</body>

</html>

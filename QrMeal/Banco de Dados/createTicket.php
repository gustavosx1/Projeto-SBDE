<?php
session_start();
require '../Banco de Dados/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../Inicio/login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $valor_ticket = $_POST['valor_ticket'];
    $data_validade = $_POST['data_validade']; // Exemplo de data no formato: 'Y-m-d H:i:s'
    $metodo_pagamento = $_POST['metodo_pagamento']; // Método de pagamento
    $cartao_id = $_POST['cartao_id']; // ID do cartão de pagamento

    if (empty($valor_ticket) || empty($data_validade) || empty($metodo_pagamento) || empty($cartao_id)) {
        $error = "Por favor, preencha todos os campos!";
    } else {
        try {
            $pdo->beginTransaction();

            // Gerar ID único para o ticket
            $id_ticket = uniqid('TKT'); // Prefixo TKT para o ID do ticket

            $data_ticket = date('Y-m-d'); // Data de criação do ticket

            // Inserir ticket na tabela ticket
            $sql_ticket = "INSERT INTO ticket (idTicket, dataTicket, dataValidade, valorTicket, utilizado) 
                           VALUES (?, ?, ?, ?, 0)";
            $stmt_ticket = $pdo->prepare($sql_ticket);
            $stmt_ticket->execute([$id_ticket, $data_ticket, $data_validade, $valor_ticket]);

            // Verificar se o cartão já está associado à pessoa na carteira
            $sql_verificar_carteira = "SELECT * FROM carteira WHERE pessoa_idPessoa = ? AND cartao_idCartao = ?";
            $stmt_verificar_carteira = $pdo->prepare($sql_verificar_carteira);
            $stmt_verificar_carteira->execute([$usuario_id, $cartao_id]);

            if ($stmt_verificar_carteira->rowCount() == 0) {
                // Adicionar cartão à carteira se não estiver presente
                $sql_carteira = "INSERT INTO carteira (pessoa_idPessoa, cartao_idCartao) VALUES (?, ?)";
                $stmt_carteira = $pdo->prepare($sql_carteira);
                $stmt_carteira->execute([$usuario_id, $cartao_id]);
            }

            // Inserir pagamento na tabela pagamento
            $sql_pagamento = "INSERT INTO pagamento (pessoa_idPessoa, ticket_idTicket, metodoPagamento, dataCompra, cartao_idCartao) 
                              VALUES (?, ?, ?, ?, ?)";
            $stmt_pagamento = $pdo->prepare($sql_pagamento);
            $stmt_pagamento->execute([$usuario_id, $id_ticket, $metodo_pagamento, $data_ticket, $cartao_id]);

            $pdo->commit();

            $success = "Ticket criado com sucesso!";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Erro ao criar o ticket: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Ticket - Restaurante Universitário</title>
    <link rel="stylesheet" href="../style.css">
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
        <h3>Criar Novo Ticket</h3>
        
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php elseif (isset($success)): ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>

        <form action="createTicket.php" method="POST">
            <div class="input-group">
                <label for="valor_ticket">Valor do Ticket (R$):</label>
                <input type="number" name="valor_ticket" id="valor_ticket" step="0.01" required>
            </div>

            <div class="input-group">
                <label for="data_validade">Data de Validade:</label>
                <input type="datetime-local" name="data_validade" id="data_validade" required>
            </div>

            <div class="input-group">
                <label for="metodo_pagamento">Método de Pagamento:</label>
                <select name="metodo_pagamento" id="metodo_pagamento" required>
                    <option value="Cartão de Crédito">Cartão de Crédito</option>
                    <option value="Cartão de Débito">Cartão de Débito</option>
                    <option value="Boleto">Boleto</option>
                </select>
            </div>

            <div class="input-group">
                <label for="cartao_id">ID do Cartão:</label>
                <input type="number" name="cartao_id" id="cartao_id" required>
            </div>

            <button type="submit" class="button">Criar Ticket</button>
        </form>
    </div>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantidade = $_POST['quantidade'];
    $valor_unitario = 3.00;
    $valor_total = $quantidade * $valor_unitario;

    $stmt = $pdo->prepare("INSERT INTO compras (usuario_id, quantidade, valor_total) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['usuario_id'], $quantidade, $valor_total]);
    $sucesso = "Compra realizada com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Restaurante Universitário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo, <?php echo $_SESSION['usuario_nome']; ?>!</h1>
        <p>Valor do ticket: R$3,00</p>
        <?php if (isset($sucesso)): ?>
            <p class="sucesso"><?php echo $sucesso; ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="quantidade">Quantidade de Tickets:</label>
            <input type="number" id="quantidade" name="quantidade" min="1" required>
            <button type="submit">Comprar</button>
        </form>
        <p><a href="logout.php">Sair</a></p>
    </div>
</body>
</html>
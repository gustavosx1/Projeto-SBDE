<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "mydb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM pessoa WHERE idPessoa = ?");
        $stmt->execute([$id]);
        $pessoa = $stmt->fetch();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $deleteStmt = $conn->prepare("DELETE FROM pessoa WHERE idPessoa = ?");
            $deleteStmt->execute([$id]);

            echo "Pessoa excluída com sucesso!";
            header("Location: readPessoa.php");
        }
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Pessoa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .actions {
            text-align: center;
        }

        .actions a {
            color: #007BFF;
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .actions button {
            padding: 10px 15px;
            background-color: #FF0000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .actions button:hover {
            background-color: #D10000;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Excluir Pessoa</h2>
    <?php if (isset($pessoa)): ?>
        <p>Tem certeza que deseja excluir a pessoa: <strong><?= $pessoa['nome'] ?></strong>?</p>
        <div class="actions">
            <form method="POST">
                <button type="submit">Sim, Excluir</button>
            </form>
            <a href="readPessoa.php">Cancelar</a>
        </div>
    <?php else: ?>
        <p>Pessoa não encontrada!</p>
    <?php endif; ?>
</div>

</body>
</html>

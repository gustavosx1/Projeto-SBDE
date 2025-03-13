<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Pessoas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Lista de Pessoas</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Tipo Auxílio</th>
            <th>Senha</th>
        </tr>
        <?php
        require 'conexao.php';

        try {
            $stmt = $pdo->query("SELECT idPessoa, nome, email, telefone, tipoAuxilio, senha FROM pessoa");
            while ($usuario = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($usuario['idPessoa']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['nome']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['email']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['telefone']) . "</td>";
                echo "<td>" . ($usuario['tipoAuxilio'] ? 'Sim' : 'Não') . "</td>";
                echo "<td>" . htmlspecialchars($usuario['senha']) . "</td>";
                echo "</tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='6'>Erro ao buscar usuários: " . $e->getMessage() . "</td></tr>";
        }
        ?>
    </table>
</body>
</html>
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
        .actions button {
            margin: 0 5px;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .actions .update {
            background-color: #4CAF50;
            color: white;
        }
        .actions .delete {
            background-color: #f44336;
            color: white;
        }
        .actions .create {
            background-color: #008CBA;
            color: white;
            padding: 10px 20px;
            text-align: center;
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Lista de Pessoas</h2>

    <!-- Botão para Criar Nova Pessoa -->
    <a href="createPessoa.php" class="actions create">Criar Nova Pessoa</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Tipo Pessoa</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Tipo Auxílio</th>
            <th>Foto</th>
            <th>Ações</th>
        </tr>
        <?php
        require 'conexao.php';

        try {
            $stmt = $pdo->query("SELECT idPessoa, nome, tipoPessoa, email, telefone, tipoAuxilio, foto FROM pessoa");
            while ($usuario = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($usuario['idPessoa']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['nome']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['tipoPessoa']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['email']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['telefone']) . "</td>";
                echo "<td>" . ($usuario['tipoAuxilio'] ? 'Sim' : 'Não') . "</td>";
                echo "<td>" . (!empty($usuario['foto']) ? "<img src='" . htmlspecialchars($usuario['foto']) . "' alt='Foto' width='50'>" : 'Sem foto') . "</td>";
                echo "<td class='actions'>";
                // Botões de Ações: Update e Delete
                echo "<a href='update.php?id=" . $usuario['idPessoa'] . "'><button class='update'>Atualizar</button></a>";
                echo "<a href='delete.php?id=" . $usuario['idPessoa'] . "'><button class='delete'>Excluir</button></a>";
                echo "</td>";
                echo "</tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='8'>Erro ao buscar usuários: " . $e->getMessage() . "</td></tr>";
        }
        ?>
    </table>
</body>
</html>

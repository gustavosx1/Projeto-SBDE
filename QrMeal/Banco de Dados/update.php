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
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $tipoPessoa = $_POST['tipoPessoa'];
        $senha = $_POST['senha'];

        $stmt = $conn->prepare("UPDATE pessoa SET nome = ?, email = ?, telefone = ?, tipoPessoa = ?, senha = ? WHERE idPessoa = ?");
        $stmt->execute([$nome, $email, $telefone, $tipoPessoa, $senha, $id]);

        echo "Dados atualizados com sucesso!";
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
    <title>Atualizar Pessoa</title>
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

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        input[type="hidden"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="password"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
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
    </style>
</head>

<body>

    <div class="container">
        <h2>Atualizar Pessoa</h2>
        <?php if (isset($pessoa)): ?>
            <form method="POST">
                <input type="hidden" name="idPessoa" value="<?= $pessoa['idPessoa'] ?>">
                <input type="text" name="nome" placeholder="Nome" value="<?= $pessoa['nome'] ?>" >
                <input type="email" name="email" placeholder="Email" value="<?= $pessoa['email'] ?>" >
                <input type="tel" name="telefone" placeholder="Telefone" value="<?= $pessoa['telefone'] ?>" >
                <label for="tipoPessoa">Tipo de Pessoa:</label>
                <select id="tipoPessoa" name="tipoPessoa" >
                    <option value="Administrador">Administrador</option>
                    <option value="Funcionario">Funcionário</option>
                    <option value="Estudante">Estudante</option>
                </select>
                <input type="password" name="senha" placeholder="Senha">
                <button type="submit">Atualizar</button>
            </form>
        <?php else: ?>
            <p>Pessoa não encontrada!</p>
        <?php endif; ?>
        <div class="actions">
            <a href="createPessoa.php">Voltar para a lista</a>
        </div>
    </div>

</body>

</html>
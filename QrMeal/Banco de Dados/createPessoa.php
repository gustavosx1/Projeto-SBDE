<?php
session_start();
$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'conexao.php';

    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $tipoAuxilio = $_POST['tipoAuxilio'];
    $tipoPessoa = $_POST['tipoPessoa'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO pessoa (idPessoa, nome, email, telefone, tipoAuxilio, tipoPessoa, senha) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$codigo, $nome, $email, $telefone, $tipoAuxilio, $tipoPessoa, $senha]);
        
        $_SESSION['usuario_id'] = $codigo;
        $_SESSION['usuario_nome'] = $nome;
        
        header("Location: readPessoa.php");
        exit();
    } catch (PDOException $e) {
        $erro = "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Pessoa</title>
    <style>
        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="tel"], select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 14px;
        }
        span{
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Criar Nova Pessoa</h2>

    <?php if ($erro): ?>
        <div class="error"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="codigo"><span>*</span>Código (ID):</label>
        <input type="text" id="codigo" name="codigo" required>

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" >

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" >

        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" >

        <label for="tipoAuxilio"><span>*</span>Tipo Auxílio:</label>
        <select id="tipoAuxilio" name="tipoAuxilio" required>
            <option value="1">Sim</option>
            <option value="0">Não</option>
        </select>

        <label for="tipoPessoa"><span>*</span>Tipo Pessoa:</label>
        <select id="tipoPessoa" name="tipoPessoa" required>
            <option value="Física">Pessoa Física</option>
            <option value="Jurídica">Pessoa Jurídica</option>
        </select>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" >

        <input type="submit" value="Criar Pessoa">
    </form>
</body>
</html>

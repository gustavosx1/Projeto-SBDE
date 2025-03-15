<?php
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

        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        $erro = "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>

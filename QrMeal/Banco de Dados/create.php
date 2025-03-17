<?php
session_start();

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
        
        header("Location: ../Principal/menu.php");
        exit();
    } catch (PDOException $e) {
        $erro = "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>

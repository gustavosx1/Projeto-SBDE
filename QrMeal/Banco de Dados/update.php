<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'conexao.php';

    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $tipoAuxilio = $_POST['tipoAuxilio'];
    $senha = $_POST['senha'] ? password_hash($_POST['senha'], PASSWORD_BCRYPT) : null;

    try {
        if ($senha) {
            $stmt = $pdo->prepare("UPDATE pessoa SET nome = ?, email = ?, telefone = ?, tipoAuxilio = ?, senha = ? WHERE idPessoa = ?");
            $stmt->execute([$nome, $email, $telefone, $tipoAuxilio, $senha, $codigo]);
        } else {
            $stmt = $pdo->prepare("UPDATE pessoa SET nome = ?, email = ?, telefone = ?, tipoAuxilio = ? WHERE idPessoa = ?");
            $stmt->execute([$nome, $email, $telefone, $tipoAuxilio, $codigo]);
        }

        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        $erro = "Erro ao atualizar: " . $e->getMessage();
    }
}
?>

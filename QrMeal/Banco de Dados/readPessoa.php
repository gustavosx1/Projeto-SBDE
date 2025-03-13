<?php
require 'conexao.php';

try {
    // Consulta para obter todos os usuários cadastrados
    $stmt = $pdo->query("SELECT idPessoa, nome, email, telefone, tipoAuxilio FROM pessoa");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar usuários: " . $e->getMessage());
}
?>

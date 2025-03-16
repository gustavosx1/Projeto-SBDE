<?php
require 'conexao.php';

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO pessoa (idPessoa, nome, email, telefone, tipoAuxilio, senha) VALUES (?, ?, ?, ?, ?, ?)");
    
    $dados = [
        ['101', 'João Silva', 'joao@email.com', '98999999999', 1, password_hash('senha123', PASSWORD_BCRYPT)],
        ['102', 'Maria Oliveira', 'maria@email.com', '98988888888', 0, password_hash('senha456', PASSWORD_BCRYPT)],
        ['103', 'Carlos Souza', 'carlos@email.com', '98977777777', 1, password_hash('senha789', PASSWORD_BCRYPT)],
    ];
    
    foreach ($dados as $usuario) {
        $stmt->execute($usuario);
    }

    $pdo->commit();
    echo "Registros inseridos com sucesso!";
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Erro ao inserir usuários: " . $e->getMessage());
}
?>

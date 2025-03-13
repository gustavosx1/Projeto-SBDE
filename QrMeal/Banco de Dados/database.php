<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criar banco de dados
    $conn->exec("DROP DATABASE IF EXISTS mydb;CREATE DATABASE IF NOT EXISTS mydb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $conn->exec("USE mydb;");

    // Criar tabelas
    $tabelas = [
        "CREATE TABLE IF NOT EXISTS pessoa (
            idPessoa VARCHAR(20) NOT NULL PRIMARY KEY,
            nome VARCHAR(150),
            tipoPessoa VARCHAR(30),
            email VARCHAR(150),
            telefone BIGINT,
            tipoAuxilio INT,
            senha VARCHAR(255)
        ) ENGINE=InnoDB;",
        "CREATE TABLE IF NOT EXISTS ticket (
            idTicket VARCHAR(20) NOT NULL PRIMARY KEY,
            dataTicket DATE,
            dataValidade DATETIME,
            valorTicket DECIMAL(10,2)
        ) ENGINE=InnoDB;",
        "CREATE TABLE IF NOT EXISTS cartao (
            idCartao INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            bandeiraCartao VARCHAR(150),
            numeroCartao BIGINT,
            nomeCartao VARCHAR(150),
            vencimentoCartao DATE,
            tipoCartao INT,
            CVV INT
        ) ENGINE=InnoDB;",
        "CREATE TABLE IF NOT EXISTS pagamento (
            pessoa_idPessoa VARCHAR(20) NOT NULL,
            ticket_idTicket VARCHAR(20) NOT NULL,
            metodoPagamento VARCHAR(20),
            dataCompra DATETIME,
            cartao_idCartao INT NOT NULL,
            PRIMARY KEY (pessoa_idPessoa, ticket_idTicket),
            FOREIGN KEY (pessoa_idPessoa) REFERENCES pessoa(idPessoa),
            FOREIGN KEY (ticket_idTicket) REFERENCES ticket(idTicket),
            FOREIGN KEY (cartao_idCartao) REFERENCES cartao(idCartao)
        ) ENGINE=InnoDB;",
        "CREATE TABLE IF NOT EXISTS carteira (
            pessoa_idPessoa VARCHAR(20) NOT NULL,
            cartao_idCartao INT NOT NULL,
            PRIMARY KEY (pessoa_idPessoa, cartao_idCartao),
            FOREIGN KEY (pessoa_idPessoa) REFERENCES pessoa(idPessoa),
            FOREIGN KEY (cartao_idCartao) REFERENCES cartao(idCartao)
        ) ENGINE=InnoDB;"
    ];

    foreach ($tabelas as $tabela) {
        $conn->exec($tabela);
    }

    echo "Banco de dados e tabelas criados com sucesso.";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

$conn = null;
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    // Conectar ao servidor MySQL
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criar o banco de dados, se não existir
    $conn->exec("
    DROP DATABASE IF EXISTS mydb;
    CREATE DATABASE IF NOT EXISTS mydb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    $conn->exec("USE mydb;");

    // Definir as tabelas a serem criadas
    $tabelas = [
        "CREATE TABLE IF NOT EXISTS pessoa (
            idPessoa VARCHAR(20) NOT NULL PRIMARY KEY,
            nome VARCHAR(150) NOT NULL,
            tipoPessoa VARCHAR(30) NOT NULL,
            email VARCHAR(150) NOT NULL,
            telefone BIGINT NOT NULL,
            tipoAuxilio INT NOT NULL,
            foto VARCHAR(255),
            senha VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB;",

        "CREATE TABLE IF NOT EXISTS ticket (
            pessoa_idPessoa VARCHAR(20), -- Correção para o tamanho adequado
            idTicket VARCHAR(20) NOT NULL PRIMARY KEY,
            dataTicket DATE NOT NULL,
            dataValidade DATETIME NOT NULL,
            valorTicket DECIMAL(10,2) NOT NULL,
            utilizado INT NOT NULL DEFAULT 0,
            FOREIGN KEY (pessoa_idPessoa) REFERENCES pessoa(idPessoa) -- Adicionando chave estrangeira
        ) ENGINE=InnoDB;",

        "CREATE TABLE IF NOT EXISTS cartao (
            idCartao INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            bandeiraCartao VARCHAR(150) NOT NULL,
            numeroCartao BIGINT NOT NULL,
            nomeCartao VARCHAR(150) NOT NULL,
            vencimentoCartao DATE NOT NULL,
            tipoCartao INT NOT NULL,
            CVV INT NOT NULL
        ) ENGINE=InnoDB;",

        "CREATE TABLE IF NOT EXISTS pagamento (
            pessoa_idPessoa VARCHAR(20) NOT NULL,
            ticket_idTicket VARCHAR(20) NOT NULL,
            metodoPagamento VARCHAR(20) NOT NULL,
            dataCompra DATETIME NOT NULL,
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

    // Executar a criação das tabelas
    foreach ($tabelas as $tabela) {
        $conn->exec($tabela);
    }

    echo "Banco de dados e tabelas criados com sucesso.";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

$conn = null;
?>

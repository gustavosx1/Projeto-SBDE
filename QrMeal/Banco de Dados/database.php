<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
            SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
            SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
            
            DROP DATABASE IF EXISTS mydb;
            CREATE DATABASE mydb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS mydb.pessoa (
              idPessoa VARCHAR(20) NOT NULL,
              nome VARCHAR(150) NULL,
              tipoPessoa VARCHAR(30) NULL,
              email VARCHAR(150) NULL,
              telefone BIGINT NULL,
              tipoAuxilio INT NULL,
              senha VARCHAR(20) NULL,
              PRIMARY KEY (idPessoa)
            ) ENGINE = InnoDB;
            
            CREATE TABLE IF NOT EXISTS mydb.ticket (
              idTicket VARCHAR(20) NOT NULL,
              dataTicket DATE NULL,
              dataValidade DATETIME NULL,
              valorTicket DECIMAL(10,2) NULL,
              PRIMARY KEY (idTicket)
            ) ENGINE = InnoDB;
            
            CREATE TABLE IF NOT EXISTS mydb.cartao (
              idCartao INT NOT NULL,
              bandeiraCartao VARCHAR(150) NULL,
              numeroCartao BIGINT NULL,
              nomeCartao VARCHAR(150) NULL,
              vencimentoCartao DATE NULL,
              tipoCartao INT NULL,
              CVV INT NULL,
              PRIMARY KEY (idCartao)
            ) ENGINE = InnoDB;
            
            CREATE TABLE IF NOT EXISTS mydb.pagamento (
              pessoa_idPessoa INT NOT NULL,
              ticket_idTicket VARCHAR(20) NOT NULL,
              metodoPagamento VARCHAR(20) NULL,
              dataCompra DATETIME NULL,
              cartao_idCartao INT NOT NULL,
              PRIMARY KEY (pessoa_idPessoa, ticket_idTicket),
              INDEX idx_ticket (ticket_idTicket),
              INDEX idx_pessoa (pessoa_idPessoa),
              INDEX idx_cartao (cartao_idCartao),
              CONSTRAINT fk_pagamento_pessoa FOREIGN KEY (pessoa_idPessoa) REFERENCES mydb.pessoa (idPessoa) ON DELETE NO ACTION ON UPDATE NO ACTION,
              CONSTRAINT fk_pagamento_ticket FOREIGN KEY (ticket_idTicket) REFERENCES mydb.ticket (idTicket) ON DELETE NO ACTION ON UPDATE NO ACTION,
              CONSTRAINT fk_pagamento_cartao FOREIGN KEY (cartao_idCartao) REFERENCES mydb.cartao (idCartao) ON DELETE NO ACTION ON UPDATE NO ACTION
            ) ENGINE = InnoDB;
            
            CREATE TABLE IF NOT EXISTS mydb.carteira (
              pessoa_idPessoa INT NOT NULL,
              cartao_idCartao INT NOT NULL,
              PRIMARY KEY (pessoa_idPessoa, cartao_idCartao),
              INDEX idx_cartao (cartao_idCartao),
              INDEX idx_pessoa (pessoa_idPessoa),
              CONSTRAINT fk_carteira_pessoa FOREIGN KEY (pessoa_idPessoa) REFERENCES mydb.pessoa (idPessoa) ON DELETE NO ACTION ON UPDATE NO ACTION,
              CONSTRAINT fk_carteira_cartao FOREIGN KEY (cartao_idCartao) REFERENCES mydb.cartao (idCartao) ON DELETE NO ACTION ON UPDATE NO ACTION
            ) ENGINE = InnoDB;
            
            SET SQL_MODE=@OLD_SQL_MODE;
            SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
            SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;";

    $conn->exec($sql);
    echo "Banco de dados e tabelas criados com sucesso.";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

$conn = null;
?>

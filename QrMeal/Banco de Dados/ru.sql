SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- Criando o esquema do banco de dados
CREATE DATABASE IF NOT EXISTS mydb DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mydb;

-- Criando a tabela pessoa
CREATE TABLE IF NOT EXISTS pessoa (
  idPessoa INT NOT NULL,
  nome VARCHAR(150) NULL,
  tipoPessoa VARCHAR(30) NULL,
  email VARCHAR(150) NULL,
  telefone BIGINT NULL,
  tipoAuxilio INT NULL,
  senha VARCHAR(20) NULL,
  PRIMARY KEY (idPessoa)
) ENGINE = InnoDB;

-- Criando a tabela ticket
CREATE TABLE IF NOT EXISTS ticket (
  idTicket VARCHAR(20) NOT NULL,
  dataTicket DATE NULL,
  dataValidade DATETIME NULL,
  valorTicket DECIMAL(10,2) NULL,
  PRIMARY KEY (idTicket)
) ENGINE = InnoDB;

-- Criando a tabela cartao
CREATE TABLE IF NOT EXISTS cartao (
  idCartao INT NOT NULL,
  bandeiraCartao VARCHAR(150) NULL,
  numeroCartao BIGINT NULL,
  nomeCartao VARCHAR(150) NULL,
  vencimentoCartao DATE NULL,
  tipoCartao INT NULL,
  CVV INT NULL,
  PRIMARY KEY (idCartao)
) ENGINE = InnoDB;

-- Criando a tabela pagamento
CREATE TABLE IF NOT EXISTS pagamento (
  pessoa_idPessoa INT NOT NULL,
  ticket_idTicket VARCHAR(20) NOT NULL,
  metodoPagamento VARCHAR(20) NULL,
  dataCompra DATETIME NULL,
  cartao_idCartao INT NOT NULL,
  PRIMARY KEY (pessoa_idPessoa, ticket_idTicket),
  INDEX idx_ticket (ticket_idTicket),
  INDEX idx_pessoa (pessoa_idPessoa),
  INDEX idx_cartao (cartao_idCartao),
  CONSTRAINT fk_pagamento_pessoa
    FOREIGN KEY (pessoa_idPessoa)
    REFERENCES pessoa (idPessoa)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_pagamento_ticket
    FOREIGN KEY (ticket_idTicket)
    REFERENCES ticket (idTicket)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_pagamento_cartao
    FOREIGN KEY (cartao_idCartao)
    REFERENCES cartao (idCartao)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- Criando a tabela carteira
CREATE TABLE IF NOT EXISTS carteira (
  pessoa_idPessoa INT NOT NULL,
  cartao_idCartao INT NOT NULL,
  PRIMARY KEY (pessoa_idPessoa, cartao_idCartao),
  INDEX idx_cartao (cartao_idCartao),
  INDEX idx_pessoa (pessoa_idPessoa),
  CONSTRAINT fk_carteira_pessoa
    FOREIGN KEY (pessoa_idPessoa)
    REFERENCES pessoa (idPessoa)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_carteira_cartao
    FOREIGN KEY (cartao_idCartao)
    REFERENCES cartao (idCartao)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

use hdesk;

-- Passo 4: Gere essas Procedures       Inicie no Passo 1, linha 60
DELIMITER //

CREATE PROCEDURE ConsultarTicketsAbertos()
BEGIN
    SELECT t.id_ticket, t.titulo, t.descricao, t.status_ticket, 
           IFNULL(u.nome, 'Sem analista atribuído') AS nome_analista
    FROM ticket t
    LEFT JOIN usuarios u ON t.cod_analista = u.id_usuario
    WHERE t.status_ticket = 'Aberto';
END//

DELIMITER ;

CALL ConsultarTicketsAbertos();


DELIMITER //

CREATE PROCEDURE ConsultarTicketsComAnalistas()
BEGIN
    SELECT t.id_ticket, t.titulo, t.descricao, t.status_ticket, 
           IFNULL(u.nome, 'Sem analista atribuído') AS nome_analista
    FROM ticket t
    LEFT JOIN usuarios u ON t.cod_analista = u.id_usuario;
END//

DELIMITER ;

CALL ConsultarTicketsComAnalistas();



DELIMITER //

CREATE PROCEDURE ConsultarTicketsPorEmpresa(IN p_cod_empresa INT)
BEGIN
    SELECT t.id_ticket, t.titulo, t.descricao, t.status_ticket, 
           IFNULL(e.nome, 'sem analista atribuído') AS nome_analista
    FROM ticket t
    LEFT JOIN usuarios e ON e.id_usuario = t.cod_analista
    WHERE t.cod_empresa = p_cod_empresa;
END//

DELIMITER ;

CALL ConsultarTicketsPorEmpresa(123);

-- Passo 3: insira esse adm no sistema
insert into usuarios (nome,email,senha,telefone,tp_usuario) values ("Administrador","admin@hdesk.com","admin","40028922",1);

-- Passo 2: Insira esses 3 insert
INSERT INTO hdesk.tipo_usuario (tp_usuario, descricao) VALUES (1, 'Administrador');
INSERT INTO hdesk.tipo_usuario (tp_usuario, descricao) VALUES (2, 'Analista');
INSERT INTO hdesk.tipo_usuario (tp_usuario, descricao) VALUES (3, 'Empresa');

-- Passo 1: Antes de criar as tabelas, desative as verificações de chaves únicas e estrangeiras temporariamente(rode da Linha 60 a 143)
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

-- Defina o modo SQL
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- Criação do esquema se não existir e uso dele
CREATE SCHEMA IF NOT EXISTS hdesk DEFAULT CHARACTER SET utf8;
USE hdesk;

-- Criação da tabela tipo_usuario
CREATE TABLE IF NOT EXISTS hdesk.tipo_usuario (
  tp_usuario INT NOT NULL,
  descricao VARCHAR(45) NULL,
  PRIMARY KEY (tp_usuario)
)
ENGINE = InnoDB;

-- Criação da tabela usuarios
CREATE TABLE IF NOT EXISTS hdesk.usuarios (
  id_usuario INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(45) NULL,
  email VARCHAR(45) NULL UNIQUE,
  senha VARCHAR(45) NULL,
  telefone VARCHAR(15) NULL,
  tp_usuario INT NOT NULL,
  PRIMARY KEY (id_usuario),
  CONSTRAINT usuarios_fk
    FOREIGN KEY (tp_usuario)
    REFERENCES hdesk.tipo_usuario (tp_usuario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

-- Criação da tabela empresa
CREATE TABLE IF NOT EXISTS hdesk.empresa (
  cod_empresa INT NOT NULL AUTO_INCREMENT,
  cnpj VARCHAR(20) NULL UNIQUE,
  nome_empresa VARCHAR(50) NULL,
  telefone VARCHAR(15) NULL,
  email VARCHAR(45) NULL UNIQUE,
  senha VARCHAR(45) NULL,
  tp_usuario INT NOT NULL,
  PRIMARY KEY (cod_empresa),
  INDEX tp_usuario_idx (tp_usuario),
  CONSTRAINT empresa_tp_usuario_fk
    FOREIGN KEY (tp_usuario)
    REFERENCES hdesk.tipo_usuario (tp_usuario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

-- Criação da tabela ticket
CREATE TABLE IF NOT EXISTS hdesk.ticket (
  id_ticket INT NOT NULL AUTO_INCREMENT,
  status_ticket ENUM('pendente', 'aberto', 'resolvido') NULL,
  data_hora DATETIME NULL,
  prioridade VARCHAR(100) NULL,
  descricao VARCHAR(200) NULL,
  cod_empresa INT NULL,
  cod_analista INT NULL,
  titulo varchar(300) NULL,
  PRIMARY KEY (id_ticket),
  INDEX cod_empresa_idx (cod_empresa),
  INDEX cod_analista_idx (cod_analista),
  CONSTRAINT ticket_empresa_fk
    FOREIGN KEY (cod_empresa)
    REFERENCES hdesk.empresa (cod_empresa)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT ticket_analista_fk
    FOREIGN KEY (cod_analista)
    REFERENCES hdesk.usuarios (id_usuario)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

-- Restauração das configurações originais
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
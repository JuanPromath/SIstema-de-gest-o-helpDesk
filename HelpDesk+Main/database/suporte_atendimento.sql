CREATE DATABASE IF NOT EXISTS HelpDeskMais CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE HelpDeskMais;

CREATE TABLE IF NOT EXISTS Cliente (
    codigo INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    cpf CHAR(11) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS Cargo (
    codigo INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS Funcionario (
    codigo INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    cpf CHAR(11) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    id_cargo INT NOT NULL,
    FOREIGN KEY (id_cargo) REFERENCES Cargo(codigo) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Conta_Sistema (
    codigo INT PRIMARY KEY AUTO_INCREMENT,
    senha VARCHAR(255) NOT NULL,
    Id_funcionario INT NOT NULL,
    FOREIGN KEY (Id_funcionario) REFERENCES Funcionario(codigo) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Chamado (
    codigo INT PRIMARY KEY AUTO_INCREMENT,
    bo TEXT NOT NULL,
    status ENUM('aberto','em andamento','fechado') NOT NULL DEFAULT 'aberto',
    Id_cliente INT NOT NULL,
    Id_funcionario INT NOT NULL,
    Id_conta INT NOT NULL,
    id_cargo INT NOT NULL,
    data_abertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_fechamento DATETIME,
    FOREIGN KEY (id_cargo) REFERENCES Cargo(codigo) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (Id_cliente) REFERENCES Cliente(codigo) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (Id_funcionario) REFERENCES Funcionario(codigo) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (Id_conta) REFERENCES Conta_Sistema(codigo) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Telefone_Cliente (
    codigo INT PRIMARY KEY AUTO_INCREMENT,
    telefone VARCHAR(20) NOT NULL,
    Id_cliente INT NOT NULL,
    FOREIGN KEY (Id_cliente) REFERENCES Cliente(codigo) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS Telefone_Funcionario (
    codigo INT PRIMARY KEY AUTO_INCREMENT,
    telefone VARCHAR(20) NOT NULL,
    Id_funcionario INT NOT NULL,
    FOREIGN KEY (Id_funcionario) REFERENCES Funcionario(codigo) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE INDEX idx_cliente_cpf ON Cliente(cpf);
CREATE INDEX idx_funcionario_cpf ON Funcionario(cpf);
CREATE INDEX idx_chamado_status ON Chamado(status);

INSERT INTO Cargo (nome) VALUES ('Técnico'), ('Administrador'), ('Atendente') ON DUPLICATE KEY UPDATE nome=nome;
INSERT INTO Cliente (nome, cpf, email) VALUES ('João Silva', '12345678901', 'joao@email.com') ON DUPLICATE KEY UPDATE nome=nome;
INSERT INTO Funcionario (nome, cpf, email, id_cargo) VALUES ('Maria Souza', '98765432100', 'maria@email.com', 1) ON DUPLICATE KEY UPDATE nome=nome;
INSERT INTO Conta_Sistema (senha, Id_funcionario) VALUES (MD5('senha123'), 1) ON DUPLICATE KEY UPDATE senha=senha;
INSERT INTO Chamado (bo, status, Id_cliente, Id_funcionario, Id_conta, id_cargo) VALUES ('Problema no sistema', 'aberto', 1, 1, 1, 1) ON DUPLICATE KEY UPDATE bo=bo;
INSERT INTO Telefone_Cliente (telefone, Id_cliente) VALUES ('11999999999', 1) ON DUPLICATE KEY UPDATE telefone=telefone;
INSERT INTO Telefone_Funcionario (telefone, Id_funcionario) VALUES ('11888888888', 1) ON DUPLICATE KEY UPDATE telefone=telefone;

SELECT * FROM Cliente;
SELECT * FROM Funcionario;
SELECT * FROM Chamado;
SELECT * FROM Cargo;

SELECT c.nome AS nome_cliente, c.cpf AS cliente_cpf, f.nome AS nome_funcionario, f.cpf AS cpf_funcionario, cg.nome AS cargo, cs.codigo AS conta_codigo, ch.status, ch.data_abertura, ch.data_fechamento
FROM Chamado ch
INNER JOIN Cliente c ON ch.Id_cliente = c.codigo
INNER JOIN Funcionario f ON ch.Id_funcionario = f.codigo
INNER JOIN Conta_Sistema cs ON ch.Id_conta = cs.codigo
INNER JOIN Cargo cg ON ch.id_cargo = cg.codigo;
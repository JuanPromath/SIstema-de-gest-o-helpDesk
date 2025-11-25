CREATE DATABASE IF NOT EXISTS HelpDeskMais;
USE HelpDeskMais;

-- Tabela de cargos
CREATE TABLE IF NOT EXISTS Cargo (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

-- Tabela de funcionários
CREATE TABLE IF NOT EXISTS Funcionario (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    id_cargo INT,
    FOREIGN KEY (id_cargo) REFERENCES Cargo(codigo)
);

-- Tabela de contas do sistema
CREATE TABLE IF NOT EXISTS Conta_Sistema (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    senha VARCHAR(255) NOT NULL,
    Id_funcionario INT NOT NULL,
    FOREIGN KEY (Id_funcionario) REFERENCES Funcionario(codigo)
);

-- Tabela de clientes
CREATE TABLE IF NOT EXISTS Cliente (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL
);

-- Tabela de chamados
CREATE TABLE IF NOT EXISTS Chamado (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    bo VARCHAR(100) NOT NULL,
    status VARCHAR(50) NOT NULL,
    id_cliente INT,
    id_funcionario INT,
    id_conta INT,
    id_cargo INT,
    data_abertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_fechamento DATETIME,
    FOREIGN KEY (id_cliente) REFERENCES Cliente(codigo),
    FOREIGN KEY (id_funcionario) REFERENCES Funcionario(codigo),
    FOREIGN KEY (id_conta) REFERENCES Conta_Sistema(codigo),
    FOREIGN KEY (id_cargo) REFERENCES Cargo(codigo)
);

-- Inserir cargo admin
INSERT INTO Cargo (nome) VALUES ('Administrador');

-- Inserir funcionário admin
INSERT INTO Funcionario (nome, cpf, email, id_cargo) VALUES ('Admin', '00000000000', 'admin@helpdesk.com', 1);

-- Inserir conta admin (senha: admin123)
INSERT INTO Conta_Sistema (senha, Id_funcionario) VALUES
('$2y$10$wH8Qw1Qw1Qw1Qw1Qw1Qw1uQw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1', 1);
-- (A senha acima é um hash de exemplo, gere um hash real com password_hash('admin123', PASSWORD_DEFAULT) no PHP)

-- Inserir cliente de exemplo
INSERT INTO Cliente (nome, cpf, email) VALUES ('Cliente Exemplo', '11111111111', 'cliente@exemplo.com');

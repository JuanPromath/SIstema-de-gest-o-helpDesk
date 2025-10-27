CREATE DATABASE projetoHelpDesk;

USE projetoHelpDesk;

CREATE TABLE Cliente(
	codigo INT PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(50),
    cpf CHAR(11),
    email TEXT
);

CREATE TABLE Funcionario(
	codigo INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50),
    cpf CHAR(11),
    email TEXT,
    cargo VARCHAR(50)
);

CREATE TABLE Conta_Sistema(
	codigo INT PRIMARY KEY AUTO_INCREMENT,
    senha VARCHAR(20) NOT NULL,
    Id_funcionario INT NOT NULL,
    FOREIGN KEY (Id_funcionario) REFERENCES Funcionario(codigo)
);

CREATE TABLE Chamado(
	codigo INT PRIMARY KEY AUTO_INCREMENT,
    cargo VARCHAR(50),
    bo TEXT,
    status TEXT,
    Id_cliente int not null,
    Id_funcionario int not null,
    Id_conta int not null,
    FOREIGN KEY (Id_cliente) REFERENCES Cliente(codigo),
    FOREIGN KEY (Id_funcionario) REFERENCES Funcionario(codigo),
    FOREIGN KEY (Id_conta) REFERENCES Conta_Sistema(codigo)
);

CREATE TABLE telefone_cliente(
	codigo INT PRIMARY KEY AUTO_INCREMENT,
    telefone TEXT,
    Id_cliente INT NOT NULL,
    FOREIGN KEY (Id_cliente) REFERENCES Cliente(codigo)
);

CREATE TABLE telefone_funcionario(
	codigo INT PRIMARY KEY AUTO_INCREMENT,
    telefone TEXT,
    Id_funcionario INT NOT NULL,
    FOREIGN KEY (Id_funcionario) REFERENCES Funcionario(codigo)
);
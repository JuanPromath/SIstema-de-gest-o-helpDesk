CREATE DATABASE projetoHelpDesk;

USE projetoHelpDesk;

CREATE TABLE Cliente(
	codigo INT PRIMARY KEY AUTO_INCREMENT,
	nome VARCHAR(50) NOT NULL,
    cpf CHAR(11) NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE
);

CREATE TABLE cargo(

    codigo int PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE Funcionario(
	codigo INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    cpf CHAR(11) NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    id_cargo int NOT NULL,
    FOREIGN KEY (id_cargo) REFERENCES cargo(codigo) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE Conta_Sistema(
	codigo INT PRIMARY KEY AUTO_INCREMENT,
    senha VARCHAR(255) NOT NULL,
    nivel_acesso text NOT NULL,# 1 - ADM, 2 - ATENDENTE, 3 - FUNCIONARIO
    Id_funcionario INT NOT NULL,
    FOREIGN KEY (Id_funcionario) REFERENCES Funcionario(codigo) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Chamado(
	codigo INT PRIMARY KEY AUTO_INCREMENT,
    bo TEXT not null,
    status TEXT not null,
    Id_cliente int not null,
    Id_funcionario int not null,
    Id_conta int not null,
    id_cargo int not null,
    FOREIGN KEY (id_cargo) REFERENCES cargo(codigo) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (Id_cliente) REFERENCES Cliente(codigo) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (Id_funcionario) REFERENCES Funcionario(codigo) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (Id_conta) REFERENCES Conta_Sistema(codigo) ON DELETE RESTRICT ON UPDATE CASCADE
);

SELECT * FROM funcionario;
SELECT * FROM cargo;
SELECT * FROM Conta_sistema inner join funcionario on Id_funcionario = funcionario.codigo
INNER JOIN Cargo on funcionario.id_cargo = Cargo.codigo;

SELECT * FROM funcionario INNER JOIN Cargo on funcionario.id_cargo = Cargo.codigo;

INSERT INTO funcionario (nome, cpf, email, id_cargo) VALUES('Juan Pablo', '11111111111', 'juan@gmail.com', 1);
INSERT INTO cargo(nome) VALUES('Administrador');
INSERT INTO Conta_sistema(senha, nivel_acesso,id_funcionario) VALUES ('123456', '1', '1');





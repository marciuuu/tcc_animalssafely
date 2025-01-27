create database animalssafely;

use animalssafely;

create table usuario(
    codUsuario int primary key auto_increment,
    nome varchar(100),
    cpf varchar(15),
    cep varchar(20),
    numero VARCHAR(8),
    telefone varchar(20),
    dataNasc DATE,
    email varchar(50),
    senha varchar(100),
    situacao boolean
    );

create table instituicao(
    codInstituicao int primary key auto_increment,
    nome varchar(100),
    cnpj varchar(15),
    cep varchar(20),
    numero VARCHAR(8),
    telefone varchar(20),
    email varchar(50),
    senha varchar(100),
    situacao boolean
);

create table raca(
    codRaca int primary key auto_increment,
    descricao varchar(20)
);

create table especie(
    codEspecie int primary key auto_increment,
    descricao varchar(20),
    codRaca_fk int,
    constraint foreign key(codRaca_fk) references raca(codRaca)
);

create table animal(
    codAnimal int primary key auto_increment,
    nome varchar(50),
    sexo varchar(20),
    porte varchar(20),
    idade varchar(10),
    descricao varchar(100), 
    situacao boolean,
    codUsuario_fk int null,
    codInstituicao_fk int null,
    codEspecie_fk int,
    imagem VARCHAR(255) NULL,
    
    constraint foreign key(codUsuario_fk) references usuario(codUsuario),
    constraint foreign key(codInstituicao_fk) references instituicao(codInstituicao),
	constraint foreign key(codEspecie_fk) references especie(codEspecie)
	 );
     
create table adocao(
	codAdocao int primary key auto_increment,
    dataAdocao date,
    codAnimal_fk int null,
    codUsuario_fk int null,
    
    constraint foreign key(codAnimal_fk) references animal(codAnimal),
    constraint foreign key(codUsuario_fk) references usuario(codUsuario)
);

INSERT INTO usuario (codUsuario, nome, cpf, cep, numero, telefone, dataNasc, email, senha, situacao)
VALUES(-1, "nulo", "000000", "0000000", "0000", "0000000000", "0000/00/00", "nulo@nulo", "nulo", 0);

INSERT INTO instituicao (codInstituicao, nome, cnpj, cep, numero, telefone, email, senha, situacao)
VALUES(-1, "nulo", "000000", "0000000", "0000", "0000000000", "nulo@nulo", "nulo", 0);

INSERT INTO usuario (nome, cpf, cep, numero, telefone, dataNasc, email, senha, situacao)
VALUES("Lucas Abib", "47338981850", "15040531", "1770", "17981205335", "2004/12/01", "Lucas_oba@outlook.com", "Dougras12321", 1);

INSERT INTO instituicao (nome, cnpj, cep, numero, telefone, email, senha, situacao)
VALUES("Animais", "111111111", "15040531", "1770", "17981295555", "animais@outlook.com", "cleiton", 1);

select * from animal;

select * from usuario;

SELECT * FROM instituicao; 	

select * from adocao;
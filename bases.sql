use apholos_universys;

CREATE TABLE Usuarios(
usuario varchar(50) not null unique,
contraseña varchar(30) not null,
id_rol int not null,
PRIMARY KEY(mail));

CREATE TABLE Alumnos(
matricula int not null unique,
nombre varchar(30) not null,
apellido varchar(30) not null,
mail varchar(50) not null unique,
PRIMARY KEY (matricula),
FOREIGN KEY (mail) REFERENCES Usuarios(usuario));

CREATE TABLE Sesiones(
idSesion int not null unique,
usuario varchar(50) not null,
PRIMARY KEY(idSesion),
FOREIGN KEY (usuario) REFERENCES Usuarios(usuario));








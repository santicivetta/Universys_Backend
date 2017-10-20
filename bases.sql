use apholos_universys;

CREATE TABLE Usuarios(
usuario varchar(50) not null unique,
contraseña varchar(30) not null,
idRol int not null,
PRIMARY KEY(mail),
FOREIGN KEY(id_rol) REFERENCES Roles(id_rol));

CREATE TABLE Alumnos(
matricula int not null unique,
documento int not null,
nombre varchar(30) not null,
apellido varchar(30) not null,
mail varchar(50) not null unique,
fechaNacimiento date,
genero varchar(20),
domicilio varchar(100),
PRIMARY KEY (matricula),
FOREIGN KEY (mail) REFERENCES Usuarios(usuario));

CREATE TABLE Sesiones(
idSesion int not null unique,
usuario varchar(50) not null,
PRIMARY KEY(idSesion),
FOREIGN KEY (usuario) REFERENCES Usuarios(usuario));

CREATE TABLE Roles(
idRol int not null unique,
descripcion varchar(30) not null,
tabla varchar(30) not null,
PRIMARY KEY (id_rol));

CREATE TABLE Profesores(
legajo int not null unique,
documento int not null,
nombre varchar(30) not null,
apellido varchar(30) not null,
mail varchar(50) not null unique,
fechaNacimiento date,
genero varchar(20),
domicilio varchar(100),
PRIMARY KEY (legajo),
FOREIGN KEY (mail) REFERENCES Usuarios(usuario));

CREATE TABLE Administradores(
legajo int not null unique,
documento int not null,
nombre varchar(30) not null,
apellido varchar(30) not null,
mail varchar(50) not null unique,
fechaNacimiento date,
genero varchar(20),
domicilio varchar(100),
PRIMARY KEY (legajo),
FOREIGN KEY (mail) REFERENCES Usuarios(usuario));

CREATE TABLE Carreras(
idCarrera int not null unique,
nombre varchar(100) not null,
PRIMARY KEY (idCarrera));

CREATE TABLE Materias(
idMateria int not null unique,
nombre varchar(50) not null unique,
PRIMARY KEY (idMateria));

CREATE TABLE Catedras(
idMateria int not null,
catedra varchar(30) not null,
horasCatedra int,
titular varchar(50),
PRIMARY KEY(idMateria,catedra),
FOREIGN KEY(idMateria) REFERENCES Materias(idMateria));

CREATE TABLE Cursadas(
idCursada int not null unique,
idMateria int not null,
catedra int not null,
año year,
PRIMARY KEY(idCursada),
FOREIGN KEY(idMateria) REFERENCES Catedras(idMateria),
FOREIGN KEY(catedra) REFERENCES Catedras(catedra));

CREATE TABLE AlumnosXCursada(
idCursada int not null,
matricula int not null,
PRIMARY KEY(idCursada,matricula),
FOREIGN KEY(matricula) REFERENCES Alumnos(matricula),
FOREIGN KEY(idCursada) REFERENCES Cursadas(idCursada));

CREATE TABLE ProfesoresXCursada(
idCursada int not null,
legajo int not null,
PRIMARY KEY(idCursada,legajo),
FOREIGN KEY(legajo) REFERENCES Profesores(legajo),
FOREIGN KEY(idCursada) REFERENCES Cursadas(idCursada));





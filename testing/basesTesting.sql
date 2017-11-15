use apholos_universys_testing;

DROP TABLE IF EXISTS Usuarios;
CREATE TABLE Usuarios(
usuario varchar(50) not null unique,
contraseña varchar(50) not null,
idRol int not null,
fechaHasta date default null,
PRIMARY KEY(usuario));

DROP TABLE IF EXISTS Alumnos;
CREATE TABLE Alumnos(
matricula int not null unique,
documento int not null,
nombre varchar(30) not null,
apellido varchar(30) not null,
mail varchar(50) not null unique,
fechaNacimiento date,
genero varchar(20),
domicilio varchar(100),
telefono varchar(100),
PRIMARY KEY (matricula));

DROP TABLE IF EXISTS Sesiones;
CREATE TABLE Sesiones(
idSesion int not null auto_increment,
usuario varchar(50) not null,
fechaAlta date,
PRIMARY KEY(idSesion));

DROP TABLE IF EXISTS Roles;
CREATE TABLE Roles(
idRol int not null unique auto_increment,
descripcion varchar(30) not null,
tabla varchar(30) not null,
PRIMARY KEY (idRol));

DROP TABLE IF EXISTS Profesores;
CREATE TABLE Profesores(
legajo int not null unique,
documento int not null,
nombre varchar(30) not null,
apellido varchar(30) not null,
mail varchar(50) not null unique,
fechaNacimiento date,
genero varchar(20),
domicilio varchar(100),
telefono varchar(100),
PRIMARY KEY (legajo));

DROP TABLE IF EXISTS Administradores;
CREATE TABLE Administradores(
legajo int not null unique,
documento int not null,
nombre varchar(30) not null,
apellido varchar(30) not null,
mail varchar(50) not null unique,
fechaNacimiento date,
genero varchar(20),
domicilio varchar(100),
telefono varchar(100),
PRIMARY KEY (legajo));

DROP TABLE IF EXISTS Carreras;
CREATE TABLE Carreras(
idCarrera int not null unique auto_increment,
nombre varchar(100) not null,
fechaHasta date default null,
PRIMARY KEY (idCarrera));

DROP TABLE IF EXISTS MateriasXCarreras;
CREATE TABLE MateriasXCarreras(
idCarrera int not null,
idMateria int not null,
PRIMARY KEY(idCarrera,idMateria));

DROP TABLE IF EXISTS Materias;
CREATE TABLE Materias(
idMateria int not null unique auto_increment,
nombre varchar(50) not null unique,
fechaHasta date default null,
PRIMARY KEY (idMateria));

DROP TABLE IF EXISTS Catedras;
CREATE TABLE Catedras(
idCatedra int not null unique auto_increment,
idMateria int not null,
catedra varchar(30) not null,
horasCatedra int,
titular varchar(50),
fechaHasta date default null,
PRIMARY KEY(idCatedra),
FOREIGN KEY(idMateria) REFERENCES Materias(idMateria));

DROP TABLE IF EXISTS Cursadas;
CREATE TABLE Cursadas(
idCursada int not null auto_increment,
idMateria int not null,
<<<<<<< Updated upstream
catedra varchar(30) not null,
=======
idCatedra int not null,
cuatrimestre varchar(100),
>>>>>>> Stashed changes
año year not null,
horario varchar(100),
parcial datetime,
recuperatorio1 datetime,
recuperatorio2 datetime,
PRIMARY KEY(idCursada));

DROP TABLE IF EXISTS Finales;
CREATE TABLE Finales(
idFinal int not null unique,
idMateria int not null,
catedra int not null,
fecha datetime,
PRIMARY KEY(idFinal));

DROP TABLE IF EXISTS NotasDeFinales;
CREATE TABLE NotasDeFinales(
idNota int not null unique,
idFinal int not null,
idCursada int not null,
matricula int not null,
nota int not null,
PRIMARY KEY(idNota));

DROP TABLE IF EXISTS AlumnosXCursada;
CREATE TABLE AlumnosXCursada(
idCursada int not null,
matricula int not null,
notaParcial int,
notaRecuperatorio int,
PRIMARY KEY(idCursada,matricula));

DROP TABLE IF EXISTS ProfesoresXCursada;
CREATE TABLE ProfesoresXCursada(
idCursada int not null,
legajo int not null,
PRIMARY KEY(idCursada,legajo));

DROP TABLE IF EXISTS AlumnosXCarrera;
CREATE TABLE AlumnosXCarrera(
matricula int not null,
idCarrera int not null,
PRIMARY KEY(matricula,idCarrera));

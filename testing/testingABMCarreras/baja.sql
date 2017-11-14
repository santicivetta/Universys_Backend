TRUNCATE TABLE Roles;
INSERT INTO Roles(descripcion,tabla) values
("Alumno","Alumnos"),
("Profesor","Profesores"),
("Administrador","Administradores");

TRUNCATE TABLE Usuarios;
INSERT INTO Usuarios values
("bedel.gato@comunidad.ub.edu.ar",md5("contraseñabedel"),3,null);

TRUNCATE TABLE Sesiones;
INSERT INTO Sesiones(usuario,fechaAlta) values
("bedel.gato@comunidad.ub.edu.ar",CURDATE());

TRUNCATE TABLE Carreras;
INSERT INTO Carreras(nombre) values
("Tecnicatura en Programacion"),
("Tecnicatura en RRHH");

TRUNCATE TABLE Materias;
INSERT INTO Materias(nombre) values
("Analisis Matematico"),
("Algebra");

TRUNCATE TABLE Catedras;
INSERT INTO Catedras(idMateria,catedra) values
(1,"abc"),
(1,"acb"),
(2,"abc");

TRUNCATE TABLE Cursadas;
INSERT INTO Cursadas(idMateria,catedra,año) values
(1,"abc",2017),
(1,"acb",2017),
(2,"abc",2017);

TRUNCATE TABLE MateriasXCarreras;
INSERT INTO MateriasXCarreras values
(1,1),
(1,2),
(2,1);

TRUNCATE TABLE Roles;
INSERT INTO Roles(descripcion,tabla) values
("Alumno","Alumnos"),
("Profesor","Profesores"),
("Administrador","Administradores");

TRUNCATE TABLE Usuarios;
INSERT INTO Usuarios values
("santiago.civetta@comunidad.ub.edu.ar",md5("contraseñasantiago"),1,null),
("mariano.martin@comunidad.ub.edu.ar",md5("contraseñamariano"),1,null),
("gaston.bodeman@comunidad.ub.edu.ar",md5("contraseñagaston"),1,null),
("andres.didier@comunidad.ub.edu.ar",md5("contraseñaandres"),2,null),
("bedel.gato@comunidad.ub.edu.ar",md5("contraseñabedel"),3,null);

TRUNCATE TABLE Sesiones;

TRUNCATE TABLE Alumnos;
INSERT INTO Alumnos values
(3147,32789765,'Santiago','Civetta','santiago.civetta@comunidad.ub.edu.ar','1990-05-22','Masculino','Cabildo 530',null),
(3148,38940878,'Mariano','Martin','mariano.martin@comunidad.ub.edu.ar','1995-06-09','Masculino','Elcano 2776',null);

TRUNCATE TABLE Profesores;
INSERT INTO Profesores values
(5100,29098765,'Andres','Didier','andres.didier@comunidad.ub.edu.ar','1985-02-28','Masculino','La pampa 263',null);

TRUNCATE TABLE Administradores;
INSERT INTO Administradores values
(5101,27012134,'Bedel','Gato','bedel.gato@comunidad.ub.edu.ar','1980-01-15','Masculino','Piedra Buena 162',null);






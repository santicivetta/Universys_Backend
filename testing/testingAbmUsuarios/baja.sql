TRUNCATE TABLE Roles;
INSERT INTO Roles(descripcion,tabla) values
("Alumno","Alumnos"),
("Profesor","Profesores"),
("Administrador","Administradores");

TRUNCATE TABLE Usuarios;
INSERT INTO Usuarios values
("bedel.gato@comunidad.ub.edu.ar",md5("contraseñabedel"),3,null),
("santiago.civetta@comunidad.ub.edu.ar",md5("contraseñasantiago"),1,null),
("andres.didier@comunidad.ub.edu.ar",md5("contraseñadidier"),2,null);

TRUNCATE TABLE Sesiones;
INSERT INTO Sesiones(usuario,fechaAlta) values
("bedel.gato@comunidad.ub.edu.ar",CURDATE()),
("santiago.civetta@comunidad.ub.edu.ar",curdate());












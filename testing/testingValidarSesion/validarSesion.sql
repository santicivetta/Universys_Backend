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
INSERT INTO Sesiones(usuario,fechaAlta) values
("santiago.civetta@comunidad.ub.edu.ar",DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
("gaston.bodeman@comunidad.ub.edu.ar",CURDATE()),
("andres.didier@comunidad.ub.edu.ar",DATE_SUB(CURDATE(), INTERVAL 2 DAY));
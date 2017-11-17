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

TRUNCATE TABLE Materias;
INSERT INTO Materias(nombre,fechaHasta) values ('Algebra',curdate());
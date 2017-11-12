TRUNCATE TABLE Roles;
INSERT INTO Roles(descripcion,tabla) values
("Alumno","Alumnos"),
("Profesor","Profesores"),
("Administrador","Administradores");

TRUNCATE TABLE Usuarios;
INSERT INTO Usuarios values
("bedel.gato@comunidad.ub.edu.ar",md5("contraseñabedel"),3,null),
("santiago.civetta@comunidad.ub.edu.ar",md5("contraseñasantiago"),1,curdate());

TRUNCATE TABLE Sesiones;
INSERT INTO Sesiones(usuario,fechaAlta) values
("bedel.gato@comunidad.ub.edu.ar",CURDATE()),
("santiago.civetta@comunidad.ub.edu.ar",curdate());


TRUNCATE TABLE Administradores;

TRUNCATE TABLE Profesores;

TRUNCATE TABLE Alumnos;
INSERT INTO Alumnos values
("3141","32341231","Santiago","Civetta","santiago.civetta@comunidad.ub.edu.ar","1990-03-02","Masculino","cabildo 123","47134567");
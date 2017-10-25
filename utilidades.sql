use apholos_universys;

select * from Sesiones;
select * from Usuarios;
select * from Roles;

TRUNCATE TABLE Roles;
INSERT INTO Roles(descripcion,tabla) values
("Alumno","Alumnos"),
("Profesor","Profesores"),
("Administrador","Administradores");

TRUNCATE TABLE Usuarios;
INSERT INTO Usuarios values
("santiago.civetta@comunidad.ub.edu.ar",md5("contraseñasantiago"),1),
("mariano.martin@comunidad.ub.edu.ar",md5("contraseñamariano"),1),
("gaston.bodeman@comunidad.ub.edu.ar",md5("contraseñagaston"),1),
("andres.didier@comunidad.ub.edu.ar",md5("contraseñaandres"),2),
("bedel.gato@comunidad.ub.edu.ar",md5("contraseñabedel"),3);

TRUNCATE TABLE Sesiones;
INSERT INTO Sesiones(usuario,fechaAlta) values
("santiago.civetta@comunidad.ub.edu.ar",curdate()),
("gaston.bodeman@comunidad.ub.edu.ar",curdate()),
("andres.didier@comunidad.ub.edu.ar",curdate());

SELECT u.usuario,r.tabla,s.fechaAlta
FROM 	Sesiones s,
		Usuarios u,
		Roles r
WHERE 	s.usuario=u.usuario
and		u.idRol=r.idRol
and s.idSesion=1;






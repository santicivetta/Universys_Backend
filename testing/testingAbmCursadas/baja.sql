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

INSERT INTO Materias(nombre) values
("Testing");


TRUNCATE TABLE Catedras;
INSERT INTO Catedras(idMateria, catedra, fechaHasta) values ('1','Marmol', curdate());


TRUNCATE TABLE Cursadas;
INSERT INTO Cursadas(idCatedra, cuatrimestre, año, horario, parcial, recuperatorio1, recuperatorio2, fechaHasta) 
values ('1','primer', "2017", "ABC",curdate(), curdate(),  curdate(), curdate());

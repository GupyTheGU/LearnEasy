USE learneasy;

##AREAS DE CONOCIMIENTO
INSERT INTO area_conocimiento(descripcion)VALUES("MATEMATICAS"),("COMPUTACION"),("DISEÑO GRAFICO");
INSERT INTO area_conocimiento(descripcion)VALUES("SEGURIDAD");

## TUTOR
CALL sp_registroCuenta("Jonathan","Czerwiak","","5534372897",23, "hola@gmail.com","n0mel0",'T'); ## T de Troy... de Tutor
CALL sp_registroCuenta("Molin","Santiago","Ramirez","5534372897",28, "123","123",'T');
CALL sp_iniciaSesion("hola@gmail.com","n0mel0");
UPDATE tutor SET descripcion = "5 Años de experiencia en arquitectura de computadoras..." WHERE idCuenta = 1;
INSERT INTO costos_tutor(idTutor, descripcion, monto, tipoTutoria) VALUES ("1", "Una sola sesion", 200.0, 'I'); ## I - tutoria suelta
INSERT INTO costos_tutor(idTutor, descripcion, monto, tipoTutoria) VALUES ("1", "Tutorado 1 mes", 800.0, 'E');  ## E - tutoria extendida
INSERT INTO tutor_area(idTutor,idArea)VALUES(1,2); ## es tutor en  computacion

## APRENDIZ
CALL sp_registroCuenta("Zitlaly","Cirigo","Muñoz","5534372899",22, "nanmj1","123",'A'); ## A de aprendiz

##HORARIOS
CALL sp_registrarPeriodo('2021-04-26','17:19:00','18:30:00');
INSERT INTO rel_horario_periodo(idHorario, idPeriodo)VALUES(2,1); ## el horario 2 es el horario de disponibilidad del tutor 1

##Solicitudes
CALL sp_registrarPeriodo('2021-04-23','15:19:00','16:30:00');
CALL sp_registrarSolicitud(2,1,2,2,2,"Buenas tardes escribo para solicitar una tutoria de...",'2021-04-22'); ## solicitud de aprendiz al tutor en un periodo

##TUTORIAS
CALL sp_registrarTutoria(1,"Arquitectura de computadoras"); ## cuando el tutor acepta la solicitud y se convierte a una tutoria. Se vincula a los horarios de tutorias de cada participante.

SELECT * FROM solicitud;
SELECT * FROM cuenta;
SELECT * FROM rel_horario_Tutoria;
SELECT * FROM rel_horario_periodo;

SELECT cuenta.idCuenta,cuenta.nombre, cuenta.pApellido, cuenta.sApellido, cuenta.telefono, cuenta.edad, cuenta.correo, tutor.descripcion, idHorarioDisponibilidad,valoracionTotal,tutor_area.idArea, area_conocimiento.descripcion FROM cuenta INNER JOIN tutor INNER JOIN tutor_area INNER JOIN area_conocimiento on cuenta.idCuenta = tutor.idCuenta AND tutor.idCuenta = tutor_area.idTutor AND tutor_area.idArea = area_conocimiento.idArea and area_conocimiento.descripcion LIKE CONCAT('%','J', '%') order by cuenta.idCuenta;
select * from area_conocimiento
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
php process dinamic forms

20.5 redes neuronales
162 artificial intelligence russel un enfoque moderno

CARROS
Negro - faro
camion - 6071

piel - 1009
tela - 5034

aprendiz.php -> la imagen de fondo
tutor.php -> el fondo y lo de abajo

L34rnE5yPr0

select idHorario, tipoHorario, PERIODO.idPeriodo, idDia, fecha, horaIn, horaOut, idEvento FROM PERIODO NATURAL JOIN REL_HORARIO_PERIODO WHERE idHorario = 4 ORDER BY idDia

#drop procedure if exists sp_registrarPeriodo;
delimiter $$
CREATE PROCEDURE sp_registrarPeriodo (IN dia DATE,IN entrada TIME, IN salida TIME)
BEGIN
		DECLARE existe INT DEFAULT 0;
		DECLARE idCont INT DEFAULT 0;
		
		set idCont=(select ifnull(MAX(idPeriodo),0)+1 from PERIODO);
		INSERT INTO periodo(idPeriodo,horaIn, horaOut)VALUES(idCont, dia,entrada,salida);
		SELECT idCont AS idPeriodo;
END$$
delimiter ;




#drop procedure if exists sp_registrarTutoria;
delimiter $$
CREATE PROCEDURE sp_registrarTutoria (IN idSol INT UNSIGNED, IN nombre VARCHAR(40))
BEGIN
		DECLARE existe INT DEFAULT 0;
		DECLARE idCont INT DEFAULT 0;
		DECLARE idAlum INT UNSIGNED DEFAULT 0;
		DECLARE idProf INT UNSIGNED DEFAULT 0;
		DECLARE horarioAlum INT UNSIGNED DEFAULT 0;
		DECLARE horarioProf INT UNSIGNED DEFAULT 0;
		
		set idCont =(select count(*)from EVENTO WHERE idEvento = idSol);
		if idCont = 0 then
			SELECT existe;
		else
			SET idCont = (select estadoSolicitud from Solicitud WHERE idSolicitud = idSol);
			SET idCont = (SELECT STRCMP(idCont,'0'));
			if idCont = 0 then
				SET idAlum = (SELECT idAprendiz FROM evento WHERE idEvento = idSol);
				SET idProf = (SELECT idTutor FROM evento WHERE idEvento = idSol);
				SET horarioAlum = (SELECT idHorario FROM cuenta WHERE idCuenta = idAlum);
				SET horarioProf = (SELECT idHorario FROM cuenta WHERE idCuenta = idProf);
				INSERT INTO TUTORIA(idTutoria,nombreTutoria)VALUES(idSol,nombre);
				UPDATE solicitud SET estadoSolicitud = '1' WHERE idSolicitud = idSol;
				INSERT INTO rel_horario_Tutoria(idHorario,idTutoria)VALUES(horarioAlum,idSol);
				INSERT INTO rel_horario_Tutoria(idHorario,idTutoria)VALUES(horarioProf,idSol);
				SET existe = -1;
				SELECT existe;
			else
				SET existe = -2;
				SELECT existe;
			END if;
		END if;
END$$
delimiter ;


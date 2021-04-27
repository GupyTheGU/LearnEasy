DROP DATABASE learneasy;
CREATE DATABASE learneasy;
USE learneasy;

CREATE TABLE CUENTA(
	idCuenta		INT UNSIGNED NOT NULL,
	nombre		VARCHAR(30) NOT NULL,
	pApellido	VARCHAR(30) NOT NULL,
	sApellido	VARCHAR(30),
	telefono		VARCHAR(10),
	edad			DECIMAL (3,0),
	correo		VARCHAR(30) NOT NULL,
	pass			VARCHAR(30) NOT NULL,
	idTipo		CHAR(1) NOT NULL,
	idAgenda		INT UNSIGNED DEFAULT NULL,
	idHorario		INT UNSIGNED DEFAULT NULL,
	PRIMARY KEY(idCuenta)
);

CREATE TABLE TUTOR(
	idCuenta		INT UNSIGNED NOT NULL,		
	descripcion	VARCHAR(400),
	idHorarioDisponibilidad INT UNSIGNED DEFAULT NULL,
	valoracionTotal	DECIMAL(2,1) DEFAULT 0.0,
	PRIMARY KEY(idCuenta)
);

CREATE TABLE AREA_CONOCIMIENTO(
	idArea	INT UNSIGNED AUTO_INCREMENT,
	descripcion VARCHAR(40) NOT NULL,
	PRIMARY KEY(idArea)
);

CREATE TABLE TUTOR_AREA(
	idTutor INT UNSIGNED NOT NULL,
	idArea  INT UNSIGNED NOT NULL,
	PRIMARY KEY(idTutor,idArea)
);

CREATE TABLE COSTOS_TUTOR(
	idCosto		INT UNSIGNED AUTO_INCREMENT,
	idTutor    INT UNSIGNED NOT NULL, ## idCuenta
	descripcion VARCHAR(40),
	monto       DECIMAL(5) NOT NULL,
	tipoTutoria CHAR(1) NOT NULL,
	PRIMARY KEY(idCosto)
);

CREATE TABLE HORARIO(
	idHorario	INT UNSIGNED NOT NULL,
	tipoHorario	CHAR(1) NOT NULL,
	PRIMARY KEY(idHorario)
);

CREATE TABLE REL_HORARIO_PERIODO(
	idHorario INT UNSIGNED NOT NULL,
	idPeriodo INT UNSIGNED NOT NULL,
	PRIMARY KEY(idHorario, idPeriodo)
);

CREATE TABLE REL_HORARIO_TUTORIA(
	idHorario INT UNSIGNED NOT NULL,
	idTutoria INT UNSIGNED NOT NULL,
	PRIMARY KEY(idHorario, idTutoria)
);

CREATE TABLE PERIODO(
	idPeriodo INT UNSIGNED NOT NULL,
	fecha		 DATE NOT NULL,
	horaIn	 TIME NOT NULL,
	horaOut   TIME NOT NULL,
	PRIMARY KEY(idPeriodo)
);

CREATE TABLE EVENTO(
	idEvento 	INT UNSIGNED AUTO_INCREMENT,
	idAprendiz	INT UNSIGNED NOT NULL,    ## id Cuenta
	idTutor		INT UNSIGNED NOT NULL,    ## id Cuenta
	idCosto     INT UNSIGNED NOT NULL,
	descripcion VARCHAR(400),
	idPeriodo	INT UNSIGNED NOT NULL,
	idArea		INT UNSIGNED NOT NULL,
	PRIMARY KEY(idEvento)
);

CREATE TABLE SOLICITUD(
	idSolicitud     INT UNSIGNED NOT NULL,  ##idEvento
	estadoSolicitud CHAR(1) NOT NULL,
	comentarioTutor VARCHAR(100),
	fechaSol 	DATE NOT NULL,
	PRIMARY KEY(idSolicitud)
);

CREATE TABLE TUTORIA(
	idTutoria			INT UNSIGNED NOT NULL,  ## idEvento
	nombreTutoria		VARCHAR(40),				## para identificar los tutorados extendidos	
	linkConferencia	VARCHAR(300),
	asisAprendiz		CHAR(1),
	asisTutor			CHAR(1),
	idValoracion		INT UNSIGNED DEFAULT NULL,
	estadoPago        CHAR(1) DEFAULT '0',
	PRIMARY KEY(idTutoria)
	
);

CREATE TABLE VALORACION(
	idValoracion INT UNSIGNED AUTO_INCREMENT,
	idAprendiz	 INT UNSIGNED NOT NULL,
	idTutor	 	 INT UNSIGNED NOT NULL,
	comentario	 VARCHAR(500),
	puntuacion	 DECIMAL(2,1),
	fecha			 DATE,
	PRIMARY KEY(idValoracion)
);

## Creacion de FK;
alter table TUTOR
	add foreign key(idCuenta) REFERENCES CUENTA(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD FOREIGN KEY(idHorarioDisponibilidad) REFERENCES horario(idHorario) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE TUTOR;
	
alter table COSTOS_TUTOR
	add foreign key(idTutor) REFERENCES TUTOR(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE COSTOS_TUTOR;

alter table REL_HORARIO_PERIODO
	add foreign key(idHorario) REFERENCES HORARIO(idHorario) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idPeriodo) REFERENCES PERIODO(idPeriodo) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE REL_HORARIO_PERIODO;
	
alter table REL_HORARIO_PERIODO
	add foreign key(idHorario) REFERENCES HORARIO(idHorario) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idPeriodo) REFERENCES PERIODO(idPeriodo) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE REL_HORARIO_PERIODO;

ALTER TABLE EVENTO
	add foreign key(idAprendiz) REFERENCES CUENTA(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idTutor) REFERENCES TUTOR(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idPeriodo) REFERENCES PERIODO(idPeriodo) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idCosto) REFERENCES COSTOS_TUTOR(idCosto) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idArea) REFERENCES AREA_CONOCIMIENTO(idArea) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE EVENTO;

ALTER TABLE SOLICITUD
	add foreign key(idSolicitud) REFERENCES EVENTO(idEvento) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE SOLICITUD;
	
ALTER TABLE TUTORIA
	add foreign key(idTutoria) REFERENCES EVENTO(idEvento) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idValoracion) REFERENCES VALORACION(idValoracion) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE TUTORIA;
	
ALTER TABLE VALORACION
	add foreign key(idAprendiz) REFERENCES CUENTA(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idTutor) REFERENCES TUTOR(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE VALORACION;
	
ALTER TABLE TUTOR_AREA 
	add foreign key(idTutor) REFERENCES TUTOR(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idArea) REFERENCES AREA_CONOCIMIENTO(idArea) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE TUTOR_AREA;
	
############################################################################################################################
## para resultados   0-no existe | -1-existe_ej_exito | -2-existe pero no se puede ejecutar

#drop procedure if exists sp_registroCuentaAux;
delimiter $$
CREATE PROCEDURE sp_registroCuentaAux(IN nomb VARCHAR(30),IN materno VARCHAR(30),IN paterno VARCHAR(30),IN telefonico VARCHAR(10),IN age DECIMAL(3,0),IN mail VARCHAR(30),IN contra VARCHAR(30), IN tipo CHAR(1))
BEGIN
		declare idCont 	int UNSIGNED DEFAULT 0;
   	declare existe		int default 0;
   	DECLARE idAux       INT UNSIGNED DEFAULT 0;
   	
		set idCont=(select ifnull(MAX(idCuenta),0)+1 from CUENTA);
		set idAux=(select ifnull(MAX(idHorario),0)+1 from HORARIO);
		INSERT INTO horario(idHorario,tipoHorario)VALUES(idAux,'T');
		INSERT INTO CUENTA(idCuenta, nombre, pApellido, sApellido,telefono,edad, correo,pass, idTipo, idHorario) VALUES (idCont,nomb,materno,paterno,telefonico, age, mail,contra,tipo, idAux);
		SET existe = (SELECT STRCMP(tipo,'T'));
		if existe = 0 then
			set idAux=(select ifnull(MAX(idHorario),0)+1 from HORARIO);
			INSERT INTO horario(idHorario,tipoHorario)VALUES(idAux,'D');
			INSERT INTO TUTOR(idCuenta, descripcion,idHorarioDisponibilidad)VALUES (idCont,"",idAux);			
		END if;
END$$
delimiter ;

#drop procedure if exists sp_registroCuenta;
delimiter $$
create procedure sp_registroCuenta(IN nomb VARCHAR(30),IN materno VARCHAR(30),IN paterno VARCHAR(30),IN telefonico VARCHAR(10),IN age DECIMAL(3,0),IN mail VARCHAR(30),IN contra VARCHAR(30), IN tipo CHAR(1))
begin
	declare idCont 	int UNSIGNED DEFAULT 0;
   declare existe		int default 0;
    
   set idCont =(select count(*)from cuenta WHERE correo = mail);
    
	if idCont = 0 then
		CALL sp_registroCuentaAux(nomb,materno,paterno,telefonico, age,mail,contra,tipo);
		SET existe = 0;
		SELECT existe;
	else
		set idCont =(select count(*)from cuenta WHERE (correo = mail AND pass = contra) OR (correo = mail AND idTipo = tipo) );
		if idCont = 0 then 
			CALL sp_registroCuentaAux(nomb,materno,paterno,mail,contra,tipo);
			SET existe = -1;
			SELECT existe;
		else
			SET existe = -2; ## no se puede registrar porque ya existe
			SELECT existe;
		END if;
    end if;
end$$
delimiter ;

#drop procedure if exists sp_iniciaSesion;
delimiter $$
CREATE PROCEDURE sp_iniciaSesion (IN mail VARCHAR(30), IN passw VARCHAR(30))
BEGIN
		DECLARE existe INT DEFAULT 0;
		DECLARE idCont INT DEFAULT 0;
		SET idCont = (SELECT idCuenta FROM CUENTA WHERE cuenta.correo=mail AND cuenta.pass=passw);
		if idCont > 0 then
			SET existe = 1;
			SELECT existe, idCont;
		else
			SELECT existe;
		END if;
END$$
delimiter ;

#drop procedure if exists sp_registrarPeriodo;
delimiter $$
CREATE PROCEDURE sp_registrarPeriodo (IN dia DATE, IN entrada TIME, IN salida TIME)
BEGIN
		DECLARE existe INT DEFAULT 0;
		DECLARE idCont INT DEFAULT 0;
		
		set idCont=(select ifnull(MAX(idPeriodo),0)+1 from PERIODO);
		INSERT INTO periodo(idPeriodo,fecha, horaIn, horaOut)VALUES(idCont, dia,entrada,salida);
		SELECT idCont AS idPeriodo;
END$$
delimiter ;

#drop procedure if exists sp_registrarSolicitud;
delimiter $$
CREATE PROCEDURE sp_registrarSolicitud (IN alumno INT UNSIGNED, IN maestro INT UNSIGNED, IN costo INT UNSIGNED,IN idPeriod INT UNSIGNED, IN asignatura INT UNSIGNED, IN des VARCHAR(400), IN dia DATE)
BEGIN
		DECLARE existe INT DEFAULT 0;
		DECLARE idCont INT DEFAULT 0;
		
		set idCont=(select ifnull(MAX(idEvento),0)+1 from EVENTO);
		INSERT INTO EVENTO(idEvento,idAprendiz, idTutor, idCosto, descripcion,idPeriodo,idArea)VALUES(idCont,alumno,maestro,costo,des,idPeriod,asignatura);
		INSERT INTO solicitud(idSolicitud,estadoSolicitud,fechaSol)VALUES(idCont,'0',dia);
		SELECT idCont AS idSolicitud;
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
#drop procedure if exists sp_buscarArea;
delimiter $$
CREATE PROCEDURE sp_buscarArea(IN asignatura VARCHAR(40))
BEGIN
		SELECT cuenta.idCuenta,cuenta.nombre, cuenta.pApellido, cuenta.sApellido, cuenta.telefono, cuenta.edad, cuenta.correo, tutor.descripcion,
		idHorarioDisponibilidad,valoracionTotal,tutor_area.idArea, area_conocimiento.descripcion FROM cuenta INNER JOIN tutor INNER JOIN tutor_area 
		INNER JOIN area_conocimiento on cuenta.idCuenta = tutor.idCuenta AND tutor.idCuenta = tutor_area.idTutor AND tutor_area.idArea = area_conocimiento.idArea 
		and area_conocimiento.descripcion LIKE CONCAT('%',asignatura, '%') Group by cuenta.idCuenta;
END$$
delimiter ;
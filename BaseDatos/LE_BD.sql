DROP DATABASE learneasy;
CREATE DATABASE learneasy;
ALTER DATABASE learneasy CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE learneasy;

CREATE TABLE CUENTA(
	idCuenta		INT UNSIGNED NOT NULL,
	nombre		VARCHAR(30) NOT NULL,
	pApellido	VARCHAR(30) NOT NULL,
	sApellido	VARCHAR(30) default '',
	telefono		VARCHAR(10) Default '0000000000',
	edad			DECIMAL (3,0),
	correo		VARCHAR(30) NOT NULL,
	pass			VARCHAR(30) NOT NULL,
	idTipo		CHAR(1) NOT NULL,					#'T': tutor 'A': aprendiz
	idAgenda		INT UNSIGNED DEFAULT 0,
	idHorario		INT UNSIGNED DEFAULT 0,
	PRIMARY KEY(idCuenta)
);

CREATE TABLE TUTOR(
	idCuenta		INT UNSIGNED NOT NULL,		
	descripcion	    VARCHAR(2000) default '',
	idHorarioDisponibilidad INT UNSIGNED DEFAULT 0,
	valoracionTotal	DECIMAL(2,1) DEFAULT 0.0,
	PRIMARY KEY(idCuenta)
);

CREATE TABLE AREA_CONOCIMIENTO(
	idArea	INT UNSIGNED NOT NULL,
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
	descripcion VARCHAR(100),
	monto       DECIMAL(5) NOT NULL,
	tipoTutoria CHAR(1) NOT NULL,
	PRIMARY KEY(idCosto)
);

CREATE TABLE HORARIO(
	idHorario	INT UNSIGNED NOT NULL,
	tipoHorario	CHAR(1) NOT NULL,			#'T': tutorias  'D':disponibilidad
	PRIMARY KEY(idHorario, tipoHorario)
);

CREATE TABLE REL_HORARIO_PERIODO(
	idHorario INT UNSIGNED NOT NULL,
	tipoHorario CHAR(1) NOT NULL,
	idPeriodo INT UNSIGNED NOT NULL,
	idDia     DECIMAL(2) DEFAULT 0,
	PRIMARY KEY(idHorario, tipoHorario, idPeriodo)
);

CREATE TABLE REL_HORARIO_TUTORIA(
	idHorario INT UNSIGNED NOT NULL,
	tipoHorario	CHAR(1) NOT NULL,
	idTutoria INT UNSIGNED NOT NULL,
	PRIMARY KEY(idHorario, tipoHorario, idTutoria)
);

CREATE TABLE PERIODO(
	idPeriodo INT UNSIGNED NOT NULL,
	fecha	  DATE	DEFAULT NULL,
	horaIn	  TIME NOT NULL,
	horaOut   TIME NOT NULL,
	idEvento  INT UNSIGNED DEFAULT NULL,
	PRIMARY KEY(idPeriodo)
);

CREATE TABLE EVENTO(
	idEvento 	INT UNSIGNED,
	idAprendiz	INT UNSIGNED NOT NULL,    ## id Cuenta
	idTutor		INT UNSIGNED NOT NULL,    ## id Cuenta
	idCosto     INT UNSIGNED NOT NULL,
	descripcion VARCHAR(400) default '',
	idArea		INT UNSIGNED NOT NULL,
	estadoTutoria CHAR(1) default 'P',
	PRIMARY KEY(idEvento)
);

CREATE TABLE SOLICITUD(
	idSolicitud     INT UNSIGNED NOT NULL,  ##idEvento
	estadoSolicitud CHAR(1) NOT NULL,
	comentarioTutor VARCHAR(400) default '',
	fechaSol 	    DATE NOT NULL,
	PRIMARY KEY(idSolicitud)
);

CREATE TABLE TUTORIA(
	idTutoria			INT UNSIGNED NOT NULL,  ## idEvento
	idValoracion 		INT UNSIGNED DEFAULT NULL,
	nombreTutoria		VARCHAR(40)  default '',				## para identificar los tutorados extendidos	
	linkConferencia		VARCHAR(300) DEFAULT '0',
	asisAprendiz		CHAR(1),
	asisTutor			CHAR(1),
	estadoPago        	CHAR(1) DEFAULT '0',
	certificado 		CHAR(1) DEFAULT '0',
	PRIMARY KEY(idTutoria)
	
);

CREATE TABLE VALORACION(
	idValoracion INT UNSIGNED NOT NULL,
	idAprendiz	INT UNSIGNED NOT NULL,    ## id Cuenta
	idTutor		INT UNSIGNED NOT NULL,    ## id Cuenta
	comentario	 VARCHAR(500) default '',
	puntuacion	 DECIMAL(2,1),
	fecha			 DATE,
	PRIMARY KEY(idValoracion)
);

## Creacion de FK;
alter table TUTOR
	add foreign key(idCuenta) REFERENCES CUENTA(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE,
	ADD FOREIGN KEY(idHorarioDisponibilidad) REFERENCES HORARIO(idHorario) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE TUTOR;

ALTER TABLE TUTOR_AREA 
	add foreign key(idTutor) REFERENCES TUTOR(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idArea) REFERENCES AREA_CONOCIMIENTO(idArea) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE TUTOR_AREA;
	
alter table COSTOS_TUTOR
	add foreign key(idTutor) REFERENCES TUTOR(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE COSTOS_TUTOR;

ALTER TABLE VALORACION
	add foreign key(idAprendiz) REFERENCES CUENTA(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idTutor) REFERENCES TUTOR(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE VALORACION;

ALTER TABLE EVENTO
	add foreign key(idAprendiz) REFERENCES CUENTA(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idTutor) REFERENCES TUTOR(idCuenta) ON DELETE CASCADE ON UPDATE CASCADE,
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
	
alter table REL_HORARIO_PERIODO
	add foreign key(idHorario,tipoHorario) REFERENCES HORARIO(idHorario,tipoHorario) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idPeriodo) REFERENCES PERIODO(idPeriodo) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE REL_HORARIO_PERIODO
	
alter table REL_HORARIO_TUTORIA
	add foreign key(idHorario, tipoHorario) REFERENCES HORARIO(idHorario,tipoHorario) ON DELETE CASCADE ON UPDATE CASCADE,
	add foreign key(idTutoria) REFERENCES TUTORIA(idTutoria) ON DELETE CASCADE ON UPDATE CASCADE;
	#DESCRIBE REL_HORARIO_TUTORIA;

alter table PERIODO
	add foreign key(idEvento) REFERENCES EVENTO(idEvento) ON DELETE CASCADE ON UPDATE CASCADE;
	
############################################################################################################################
## para resultados   0-no existe | -1-existe_ej_exito | -2-existe pero no se puede ejecutar
## '0001': EL NUMERO DE USOS PARA ESE CORREO YA EXCEDE EL LIMITE
## '0002': YA EXISTE UNA CUENTA DE ESE TIPO PARA UN MISMO CORREO
## '0003': REGISTRO EXITOSO
## '0004': LA CONTRASEÑA NO COINCIDE
## '0005': ACTUALIZACION EXITOSA
## '0006': LA CONTRASEÑA YA ESTA EN USO CON UN MISMO CORREO

#drop procedure if exists sp_registroCuenta;
delimiter $$
create procedure sp_registroCuenta(IN nomb VARCHAR(30),IN materno VARCHAR(30),IN paterno VARCHAR(30),IN telefonico VARCHAR(10),IN age DECIMAL(3,0),IN mail VARCHAR(30),IN contra VARCHAR(30), IN tipo CHAR(1))
begin
	declare idCont 	int UNSIGNED DEFAULT 0;
	declare esTutor	int default 0;
	DECLARE salida  CHAR(4);
	DECLARE idhora  INT UNSIGNED DEFAULT 0;
    
   set idCont =(select count(*)from CUENTA WHERE correo = mail);
    
	IF idCont > 1 THEN
		SET salida = '0001';
	ELSEIF idCont = 1 THEN
		## Verifica la existencia de un tipo de cuenta en un correo
		SET idCont =(SELECT COUNT(idCuenta)FROM CUENTA WHERE correo = mail AND idTipo = tipo);
		IF idCont > 0 THEN
			SET salida = '0002';
		ELSE
			SET idCont =(SELECT COUNT(idCuenta)FROM CUENTA WHERE correo = mail AND pass = contra);
			if idCont > 0 THEN
				SET salida = '0006';
			ELSE
				set idCont=(select ifnull(MAX(idCuenta),0)+1 from CUENTA);
				set idhora=(select ifnull(MAX(idHorario),0)+1 from HORARIO);
				
				INSERT INTO HORARIO(idHorario,tipoHorario)VALUES(idhora,'T');
				INSERT INTO CUENTA(idCuenta, nombre, pApellido, sApellido,telefono,edad, correo,pass, idTipo, idHorario) 
					VALUES (idCont,nomb,materno,paterno,telefonico, age, mail,contra,tipo, idhora);
					
				SET esTutor = (SELECT STRCMP(tipo,'T'));
				if esTutor = 0 then
					set idhora=(select ifnull(MAX(idHorario),0)+1 from HORARIO);
					INSERT INTO HORARIO(idHorario,tipoHorario)VALUES(idHora,'D');
					INSERT INTO TUTOR(idCuenta, descripcion,idHorarioDisponibilidad)VALUES (idCont,'',idhora);
				end if;
				SET salida = '0003';
			END IF;
		END IF;
	ELSE
			set idCont=(select ifnull(MAX(idCuenta),0)+1 from CUENTA);
			set idhora=(select ifnull(MAX(idHorario),0)+1 from HORARIO);
			
			INSERT INTO HORARIO(idHorario,tipoHorario)VALUES(idhora,'T');
			INSERT INTO CUENTA(idCuenta, nombre, pApellido, sApellido,telefono,edad, correo,pass, idTipo, idHorario) 
				VALUES (idCont,nomb,materno,paterno,telefonico, age, mail,contra,tipo, idhora);
				
			SET esTutor = (SELECT STRCMP(tipo,'T'));
			if esTutor = 0 then
				set idhora=(select ifnull(MAX(idHorario),0)+1 from HORARIO);
				INSERT INTO HORARIO(idHorario,tipoHorario)VALUES(idHora,'D');
				INSERT INTO TUTOR(idCuenta, descripcion,idHorarioDisponibilidad)VALUES (idCont,'',idhora);
			end if;
			SET salida = '0003';
	END IF;
	SELECT salida;
end$$
delimiter ;	

#drop procedure if exists sp_agregarArea;
delimiter $$
CREATE PROCEDURE sp_agregarArea (IN maestro INT UNSIGNED, IN especialidad VARCHAR(40))
BEGIN
		DECLARE existe INT DEFAULT 0;
		DECLARE idCont INT DEFAULT 0;
		
		SET idCont = (SELECT IF((select idArea from AREA_CONOCIMIENTO WHERE descripcion = especialidad)IS NULL,0,1));
		IF idCont = 1 THEN
			SET idCont = (select idArea from AREA_CONOCIMIENTO WHERE descripcion = especialidad);
			SET existe = (select count(*)from TUTOR_AREA WHERE idTutor = maestro AND idArea = idCont);
			IF existe = 0 THEN 
				INSERT INTO TUTOR_AREA(idTutor,idArea)VALUES(maestro,idCont);
			END IF;
		ELSE
			set idCont=(select ifnull(MAX(idArea),0)+1 from AREA_CONOCIMIENTO);
			INSERT INTO AREA_CONOCIMIENTO(idArea,descripcion)VALUES(idCont,especialidad);
			INSERT INTO TUTOR_AREA(idTutor,idArea)VALUES(maestro,idCont);
		END IF;
END$$
delimiter ;

#drop procedure if exists sp_buscarArea;
delimiter $$
CREATE PROCEDURE sp_buscarArea(IN asignatura VARCHAR(40))
BEGIN
		SELECT CUENTA.idCuenta, CUENTA.nombre, CUENTA.pApellido, CUENTA.sApellido, CUENTA.telefono, CUENTA.edad, CUENTA.correo, TUTOR.descripcion,
		idHorarioDisponibilidad,valoracionTotal,TUTOR_AREA.idArea, AREA_CONOCIMIENTO.descripcion FROM CUENTA INNER JOIN TUTOR INNER JOIN TUTOR_AREA 
		INNER JOIN AREA_CONOCIMIENTO on CUENTA.idCuenta = TUTOR.idCuenta AND TUTOR.idCuenta = TUTOR_AREA.idTutor AND TUTOR_AREA.idArea = AREA_CONOCIMIENTO.idArea 
		and AREA_CONOCIMIENTO.descripcion LIKE CONCAT('%',asignatura, '%') Group by CUENTA.idCuenta;
END$$
delimiter ;

#drop procedure if exists sp_registrarPeriodoDisp;
delimiter $$
CREATE PROCEDURE sp_registrarPeriodoDisp (IN numHorario INT UNSIGNED, IN dia DECIMAL(2),IN entrada TIME, IN salida TIME)
BEGIN
		DECLARE existe INT DEFAULT 0;
		DECLARE idCont INT DEFAULT 0;
		
		set idCont=(select ifnull(MAX(idPeriodo),0)+1 from PERIODO);
		INSERT INTO PERIODO(idPeriodo,fecha, horaIn, horaOut,idEvento)VALUES(idCont,'1000-01-01',entrada,salida,0);
		INSERT INTO REL_HORARIO_PERIODO(idHorario,tipoHorario,idPeriodo,idDia)VALUES(numHorario,'D',idCont,dia);
END$$
delimiter ;

#drop procedure if exists sp_registrarSolicitud;
delimiter $$
CREATE PROCEDURE sp_registrarSolicitud (IN alumno INT UNSIGNED, IN maestro INT UNSIGNED, IN costo INT UNSIGNED, IN des VARCHAR(400), IN asignatura INT UNSIGNED, IN fechaPeriodo DATE, IN entrada TIME, IN salida TIME)
BEGIN
		DECLARE aux INT UNSIGNED DEFAULT 0;
		DECLARE idCont INT UNSIGNED DEFAULT 0;
		
		set idCont=(select ifnull(MAX(idEvento),0)+1 from EVENTO);
		INSERT INTO EVENTO(idEvento,idAprendiz, idTutor, idCosto, descripcion,idArea)VALUES(idCont,alumno,maestro,costo,des,asignatura);
		INSERT INTO SOLICITUD(idSolicitud,estadoSolicitud,fechaSol)VALUES(idCont,'0',CURRENT_DATE);
		set aux=(select ifnull(MAX(idPeriodo),0)+1 from PERIODO);
		INSERT INTO PERIODO(idPeriodo,fecha, horaIn, horaOut)VALUES(aux,fechaPeriodo,entrada,salida);
END$$
delimiter ;

#drop procedure if exists sp_consultarSolicitudes;
delimiter $$
CREATE PROCEDURE sp_consultarSolicitudes (IN persona INT UNSIGNED, IN tipoPersona INT)
BEGIN
		if tipoPersona = 0 THEN
			select idSolicitud,idAprendiz,EVENTO.idTutor, fecha, horaIn,horaOut,EVENTO.descripcion as 'descSol', AREA_CONOCIMIENTO.descripcion as 'asignatura', COSTOS_TUTOR.descripcion as 'descCosto', monto, tipoTutoria, estadoSolicitud, CONCAT_WS( " ", nombre, pApellido, sApellido ) as fullname, CUENTA.idHorario from PERIODO NATURAL JOIN EVENTO INNER JOIN COSTOS_TUTOR INNER JOIN SOLICITUD INNER JOIN AREA_CONOCIMIENTO INNER JOIN CUENTA WHERE EVENTO.idCosto=COSTOS_TUTOR.idCosto AND EVENTO.idEvento=SOLICITUD.idSolicitud AND EVENTO.idArea=AREA_CONOCIMIENTO.idArea AND CUENTA.idCuenta=EVENTO.idAprendiz AND EVENTO.idTutor=persona ORDER BY fecha;
		else
			select idSolicitud,idAprendiz,EVENTO.idTutor, fecha, horaIn,horaOut,EVENTO.descripcion as 'descSol', AREA_CONOCIMIENTO.descripcion as 'asignatura', COSTOS_TUTOR.descripcion as 'descCosto', monto, tipoTutoria, estadoSolicitud, CONCAT_WS( " ", nombre, pApellido, sApellido ) as fullname, CUENTA.idHorario from PERIODO NATURAL JOIN EVENTO INNER JOIN COSTOS_TUTOR INNER JOIN SOLICITUD INNER JOIN AREA_CONOCIMIENTO INNER JOIN CUENTA WHERE EVENTO.idCosto=COSTOS_TUTOR.idCosto AND EVENTO.idEvento=SOLICITUD.idSolicitud AND EVENTO.idArea=AREA_CONOCIMIENTO.idArea AND CUENTA.idCuenta=EVENTO.idTutor AND EVENTO.idAprendiz=persona ORDER BY fecha;
		end if;
END$$
delimiter ;

#drop procedure if exists sp_getHorarios;
delimiter $$
CREATE PROCEDURE sp_getHorarios(IN student INT UNSIGNED, IN teacher INT UNSIGNED)
BEGIN
		SELECT (SELECT idHorario FROM CUENTA WHERE idCuenta=student)as 'horarioAprendiz' ,(SELECT idHorario FROM CUENTA WHERE idCuenta=teacher )as 'horarioTutor'; 
END$$
delimiter ;

#drop procedure if exists sp_aceptarSolicitud;
delimiter $$
CREATE PROCEDURE sp_aceptarSolicitud(IN alumnoHorario INT UNSIGNED, IN maestroHorario INT UNSIGNED, IN numSol INT UNSIGNED)
BEGIN
		INSERT INTO TUTORIA(idTutoria)VALUES(numSol);
		INSERT INTO REL_HORARIO_TUTORIA(idHorario,tipoHorario,idTutoria)VALUES(alumnoHorario,'T',numSol);
		INSERT INTO REL_HORARIO_TUTORIA(idHorario,tipoHorario,idTutoria)VALUES(maestroHorario,'T',numSol);
		UPDATE SOLICITUD SET estadoTutoria = '1' WHERE idSolicitud = numSol;
END$$
delimiter ;

#drop procedure if exists sp_consultarTutorias;
delimiter $$
CREATE PROCEDURE sp_consultarTutorias (IN persona INT UNSIGNED, IN tipoPersona INT)
BEGIN
		if tipoPersona = 0 THEN
			select TUTORIA.idTutoria,EVENTO.idAprendiz,EVENTO.idTutor, PERIODO.fecha, horaIn,horaOut,EVENTO.descripcion as 'descSol', AREA_CONOCIMIENTO.descripcion as 'asignatura', COSTOS_TUTOR.descripcion as 'descCosto', monto, tipoTutoria, estadoPago, CONCAT_WS( " ", nombre, pApellido, sApellido ) as fullname, CUENTA.idHorario, idValoracion, linkConferencia,asisAprendiz,asisTutor,estadoTutoria from PERIODO NATURAL JOIN EVENTO INNER JOIN COSTOS_TUTOR INNER JOIN TUTORIA INNER JOIN AREA_CONOCIMIENTO INNER JOIN CUENTA WHERE EVENTO.idCosto=COSTOS_TUTOR.idCosto AND EVENTO.idEvento=TUTORIA.idTutoria AND EVENTO.idArea=AREA_CONOCIMIENTO.idArea AND CUENTA.idCuenta=EVENTO.idAprendiz AND EVENTO.idTutor=persona ORDER BY estadoPago, fecha ASC;
		else
			select TUTORIA.idTutoria,EVENTO.idAprendiz,EVENTO.idTutor, PERIODO.fecha, horaIn,horaOut,EVENTO.descripcion as 'descSol', AREA_CONOCIMIENTO.descripcion as 'asignatura', COSTOS_TUTOR.descripcion as 'descCosto', monto, tipoTutoria, estadoPago, CONCAT_WS( " ", nombre, pApellido, sApellido ) as fullname, CUENTA.idHorario, idValoracion, linkConferencia,asisAprendiz,asisTutor,estadoTutoria from PERIODO NATURAL JOIN EVENTO INNER JOIN COSTOS_TUTOR INNER JOIN TUTORIA INNER JOIN AREA_CONOCIMIENTO INNER JOIN CUENTA WHERE EVENTO.idCosto=COSTOS_TUTOR.idCosto AND EVENTO.idEvento=TUTORIA.idTutoria AND EVENTO.idArea=AREA_CONOCIMIENTO.idArea AND CUENTA.idCuenta=EVENTO.idTutor AND EVENTO.idAprendiz=persona ORDER BY estadoPago, fecha ASC; 
		end if;
END$$
delimiter ;

#drop procedure if exists sp_consultarSingleTutoria;
delimiter $$
CREATE PROCEDURE sp_consultarSingleTutoria (IN tutorado INT UNSIGNED)
BEGIN
		select idEvento, EVENTO.descripcion as 'descTut',COSTOS_TUTOR.descripcion as 'descCosto',monto,tipoTutoria,AREA_CONOCIMIENTO.descripcion as 'descArea' from EVENTO INNER JOIN COSTOS_TUTOR INNER JOIN AREA_CONOCIMIENTO WHERE EVENTO.idCosto=COSTOS_TUTOR.idCosto AND EVENTO.idArea=AREA_CONOCIMIENTO.idArea AND idEvento=tutorado;
END$$
delimiter ;

#drop procedure if exists sp_consultarSingleTutoria;
delimiter $$
CREATE PROCEDURE sp_getCertificado (IN alumno INT UNSIGNED, IN tutorado INT UNSIGNED)
BEGIN
		select idEvento, nombre, pApellido, sApellido, AREA_CONOCIMIENTO.descripcion as 'asignatura', fecha, horaIn, horaOut FROM PERIODO NATURAL JOIN EVENTO INNER JOIN CUENTA INNER JOIN AREA_CONOCIMIENTO WHERE CUENTA.idCuenta=EVENTO.idAprendiz AND AREA_CONOCIMIENTO.idArea=EVENTO.idArea AND idCuenta=alumno AND idEvento = tutorado; 
END$$
delimiter ;

#drop procedure if exists sp_registrarValoracion;
delimiter $$
CREATE PROCEDURE sp_registrarValoracion (IN tutorado INT UNSIGNED, IN alumno INT UNSIGNED, IN maestro INT UNSIGNED, IN detalle VARCHAR(500), IN puntitos DECIMAL(2,1))
BEGIN
		DECLARE aux DECIMAL(2,0) DEFAULT 0;
		DECLARE idCont INT UNSIGNED DEFAULT 0;
		
		set idCont=(select ifnull(MAX(idValoracion),0)+1 from VALORACION);
		INSERT INTO VALORACION(idValoracion,idAprendiz, idTutor, comentario, puntuacion,fecha)VALUES(idCont,alumno,maestro,detalle,puntitos,CURRENT_DATE);
		UPDATE TUTORIA SET idValoracion = idCont WHERE idTutoria=tutorado;
		set aux = AVG(puntuacion) as 'primedio' FROM VALORACION WHERE idTutor=maestro);
		UPDATE TUTOR SET valoracionTotal = aux WHERE idCuenta = maestro;
END$$
delimiter ;


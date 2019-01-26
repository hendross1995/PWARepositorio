-- VALIDAR INICIO DE SESIÓN---------------------------------
CREATE OR REPLACE FUNCTION ValidarLogin(
	_usuario TEXT,
	_contrasena TEXT
)RETURNS JSON AS $$
DECLARE verificador BOOL;
BEGIN
	IF(SELECT id FROM usuarios WHERE LOWER(TRIM(correo)) = LOWER(TRIM(_usuario)) AND estado IS TRUE LIMIT 1) IS NOT NULL THEN
		IF(SELECT recuperacion FROM usuarios WHERE LOWER(TRIM(correo)) = LOWER(TRIM(_usuario)) AND estado IS TRUE LIMIT 1) IS TRUE THEN
			IF(SELECT usuarios.id FROM usuarios INNER JOIN roles ON usuarios.roles_id = roles.id
				WHERE LOWER(TRIM(correo)) = LOWER(TRIM(_usuario)) AND
							pgp_sym_decrypt(contrasena::bytea,'archivos_cca') = _contrasena AND
							estado IS TRUE LIMIT 1) IS NOT NULL THEN
				RETURN  (SELECT json_agg(json_build_object(
					'estado',TRUE,
					'idpersona',personas.id,
					'idusuario',usuarios.id,
					'nombres_usuario',CONCAT(TRIM(apellidos),' ',TRIM(nombres)),
					'usuario',correo,
					'rol',roles.nombre,
					'recuperacion',recuperacion,
					'observacion',NULL
				))FROM usuarios INNER JOIN personas ON usuarios.personas_id = personas.id
												INNER JOIN roles ON usuarios.roles_id = roles.id
							WHERE _usuario = correo AND
									pgp_sym_decrypt(contrasena::bytea,'archivos_cca') = _contrasena AND
									usuarios.estado IS TRUE LIMIT 1);
			ELSE
				RETURN json_agg(json_build_object(
					'estado',FALSE,
					'observacion','Su contraseña no es correcta. Para acceder al sistema debe igresar la contraseña temporal enviada a su correo electrónico.')
				);
			END IF;
		ELSE
			IF(SELECT usuarios.id FROM usuarios INNER JOIN roles ON usuarios.roles_id = roles.id WHERE _usuario = correo AND
			  pgp_sym_decrypt(contrasena::bytea,'archivos_cca') = _contrasena AND
			  estado IS TRUE LIMIT 1) IS NOT NULL THEN
				RETURN  (SELECT json_agg(json_build_object(
							'estado',TRUE,
							'idpersona',personas.id,
							'idusuario',usuarios.id,
							'nombres_usuario',CONCAT(TRIM(apellidos),' ',TRIM(nombres)),
							'usuario',correo,
							'rol',roles.nombre,
							'recuperacion',recuperacion,
							'observacion',NULL
						))FROM usuarios INNER JOIN personas ON usuarios.personas_id = personas.id
														INNER JOIN roles ON usuarios.roles_id = roles.id
									WHERE _usuario = correo AND
											pgp_sym_decrypt(contrasena::bytea,'archivos_cca') = _contrasena AND
											usuarios.estado IS TRUE LIMIT 1);
			ELSE
				RETURN json_agg(json_build_object(
					'estado',FALSE,
					'observacion','Datos incorrectos. Por favor verifique.')
				);
			END IF;
		END IF;
	ELSE
		RETURN json_agg(json_build_object(
			'estado',FALSE,
			'observacion','Datos incorrectos. Por favor verifique.')
		);
	END IF;

EXCEPTION WHEN OTHERS THEN
	PERFORM InsertarError(NULL,SQLSTATE,SQLERRM,'Validación de inicio de sesión.','validar');
	RETURN (SELECT json_agg(
				json_build_object(
					'estado',FALSE,
					'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'
				)
			));
END;
$$ LANGUAGE plpgsql;

-- REGISTRAR USUARIO---------------------------------
CREATE OR REPLACE FUNCTION RegistrarUsuario(
	_apellidos TEXT,
	_nombres TEXT,
	_sexo TEXT,
	_ocupacion TEXT,
	_organizacion TEXT,
	_celular TEXT,
	_correo TEXT,
	_contrasena TEXT
)RETURNS JSON AS $$
DECLARE idpersona INT;
				idusuario INT;
BEGIN
	IF(SELECT id FROM usuarios WHERE _correo = correo LIMIT 1) IS NOT NULL THEN
		RETURN json_agg(json_build_object(
					'estado',FALSE,
					'observacion',CONCAT('El correo <b>',_correo,'</b> ya pertenece a un usuario. Ingrese uno diferente por favor.'))
				);
	ELSE
		INSERT INTO personas(apellidos,nombres,sexo,celular,ocupaciones_id,organizacion,fecha_creacion)
		VALUES(_apellidos,_nombres,(CASE WHEN _sexo = 'M' THEN 'M' WHEN _sexo = 'F' THEN 'F' WHEN _sexo = 'O' THEN 'O' ELSE 'N' END),
		_celular,_ocupacion::INT,_organizacion,NOW()::FEC)RETURNING id INTO idpersona;
		INSERT INTO usuarios VALUES(
			DEFAULT,idpersona,3,_correo,pgp_sym_encrypt(_contrasena,'archivos_cca', 'compress-algo=1, cipher-algo=aes256'),TRUE,NOW()::FEC
		)RETURNING id INTO idusuario;
		RETURN  (SELECT json_agg(json_build_object(
							'estado',TRUE,
							'idpersona',idpersona,
							'idusuario',idusuario,
							'nombres_usuario',CONCAT(TRIM(UPPER(_apellidos)),' ',TRIM(UPPER(_nombres))),
							'usuario',_correo,
							'rol','LECTOR',
							'observacion',NULL
						 )));
	END IF;
EXCEPTION WHEN OTHERS THEN
	PERFORM InsertarError(NULL,SQLSTATE,SQLERRM,'Registro de usuarios.','registrar');
	RETURN (SELECT json_agg(
				json_build_object(
					'estado',FALSE,
					'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'
				)
			));
END;
$$ LANGUAGE plpgsql;

---------ACTUALIZAR ACCESO-----------
CREATE OR REPLACE FUNCTION RestablecerContrasena(IN _correo TEXT, IN _contrasena TEXT)
RETURNS JSON AS $$
BEGIN
	IF(SELECT id FROM usuarios WHERE TRIM(correo) = TRIM(_correo) LIMIT 1) IS NOT NULL THEN
		UPDATE usuario SET recuperacion = TRUE,
						 contrasena_restablecer = pgp_sym_encrypt(_contrasena,'archivos_cca', 'compress-algo=1, cipher-algo=aes256'),
						 fecha_actualizacion = NOW()::FEC
							WHERE _correo = correo;
		RETURN json_agg(json_build_object(
			'estado',TRUE,
			'observacion','Se ha enviado la contraseña temporal a su correo electrónico. Ingrese al sistema con la contraseña temporal e inmediatamente realice el cambio de la misma.'));
	ELSE
		RETURN json_agg(json_build_object(
				'estado',FALSE,
				'observacion','Este usuario no existe en nuestros registros. Por favor regístrese.')
			);
	END IF;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario::TEXT,_institucion,SQLSTATE,SQLERRM,'Restablecimiento de contraseña.','restablecer');
		RETURN json_agg(json_build_object(
				'estado',FALSE,
				'observacion','Ocurrió un error inesperado. Recargue la página o contáctese con el administrador de la aplicación.')
			);
END;
$$ LANGUAGE plpgsql;

---------ACTUALIZAR ACCESO-----------
CREATE OR REPLACE FUNCTION ActualizarAcceso(
IN _correo TEXT,
IN _contrasena_temporal TEXT,
IN _contrasena TEXT,
IN _contrasena_repite TEXT,
IN _accion VARCHAR(15))
RETURNS TEXT AS $$
DECLARE resultado TEXT;
				idnuevo INT;
				_usuario INT;
BEGIN
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM usuarios WHERE LOWER(TRIM(correo)) = LOWER(TRIM(_correo)) LIMIT 1) IS NOT NULL THEN
				UPDATE usuarios SET recuperacion = TRUE,
								 contrasena = pgp_sym_encrypt(_contrasena_temporal,'archivos_cca', 'compress-algo=1, cipher-algo=aes256'),
								 fecha_actualizacion = NOW()::FEC
									WHERE LOWER(TRIM(correo)) = LOWER(TRIM(_correo));
				RETURN json_agg(json_build_object(
					'estado',TRUE,
					'observacion','Se ha enviado la contraseña temporal a su correo electrónico. Ingrese al sistema con la contraseña temporal e inmediatamente realice el cambio de la misma.'));
			ELSE
				RETURN json_agg(json_build_object(
						'estado',FALSE,
						'observacion','Este usuario no existe en nuestros registros. Por favor regístrese.')
					);
			END IF;
		WHEN 'modificar' THEN
			IF(SELECT id FROM usuarios WHERE LOWER(TRIM(correo)) = LOWER(TRIM(_correo)) AND
						  pgp_sym_decrypt(contrasena::bytea,'archivos_cca') = _contrasena_temporal AND
							estado IS TRUE LIMIT 1) IS NOT NULL THEN
					IF (_contrasena = _contrasena_repite) THEN
						UPDATE usuarios SET recuperacion = FALSE,
						 contrasena = pgp_sym_encrypt(_contrasena,'archivos_cca', 'compress-algo=1, cipher-algo=aes256'),
						 fecha_actualizacion = NOW()::FEC
							WHERE LOWER(TRIM(correo)) = LOWER(TRIM(_correo));
							RETURN json_agg(json_build_object(
								'estado',TRUE,
								'observacion','Contraseña actualizada correctamente.')
							);
					ELSE
						RETURN json_agg(json_build_object(
							'estado',FALSE,
							'observacion','Nueva contraseña incorrecta. Verifique la igualdad de la nueva contraseña.')
						);
					END IF;
			ELSE
				RETURN json_agg(json_build_object(
					'estado',FALSE,
					'observacion','Contraseña temporal actual incorrecta. Verifique los datos por favor.')
				);
			END IF;
		ELSE
			PERFORM InsertarError(NULL,NULL,'Alteración de petición.','Restablecimiento de contraseña..',_accion);
			RETURN json_agg(json_build_object(
				'estado',FALSE,
				'observacion','No se reconoció la petición. Los datos fueron alterados.')
			);
		END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(NULL,SQLSTATE,SQLERRM,'Restablecimiento de contraseña.',_accion);
		RETURN json_agg(json_build_object(
			'estado',FALSE,
			'observacion','Ocurrió un error inesperado. Recargue la página o contáctese con el administrador de la aplicación.')
		);
END;
$$ LANGUAGE plpgsql;

--------ACTUALIZAR FONDOS DOCUMENTALES---------
CREATE OR REPLACE FUNCTION ActualizarFondos(
	IN _id TEXT,
	IN _nombre TEXT,
	IN _descripcion TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM fondos_documentales WHERE traducir(nombre) = traducir(_nombre) LIMIT 1) IS NULL THEN
				INSERT INTO fondos_documentales VALUES (
					DEFAULT,_nombre,_descripcion,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				);
				RETURN json_agg(json_build_object('estado',TRUE,'observacion','Fondo documental registrado correctamente.'));
			ELSE
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el fondo documental <b>',_nombre,'</b>.')));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_nombre) = (SELECT traducir(nombre) FROM fondos_documentales WHERE traducir(nombre) = traducir(_nombre) AND
																																									 id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el fondo documental <b>',_nombre,'</b>.')));
				ELSE
					UPDATE fondos_documentales SET nombre = _nombre,
																				 descripcion = _descripcion,
																				 fecha_actualizacion = NOW()::FEC,
																				 estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
																				 WHERE id = idnuevo;
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Fondo documental modificado correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de fondos documentales.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de fondos documentales.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de fondos documentales.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;

--------ACTUALIZAR COLECCIONES---------
CREATE OR REPLACE FUNCTION ActualizarColecciones(
	IN _id TEXT,
	IN _nombre TEXT,
	IN _descripcion TEXT,
	IN _fecha_registro TEXT,
	IN _fondo TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM colecciones WHERE traducir(nombre) = traducir(_nombre) AND fondos_documentales_id = _fondo::INT LIMIT 1) IS NULL THEN
				INSERT INTO colecciones VALUES (
					DEFAULT,_nombre,_descripcion,
					(CASE WHEN CHAR_LENGTH(TRIM(_fecha_registro))>0 THEN _fecha_registro::TIMESTAMP::FEC ELSE NULL END),
					_fondo::INT,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				);
				RETURN json_agg(json_build_object('estado',TRUE,'observacion','Colección registrada correctamente.'));
			ELSE
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',
					CONCAT('Ya existe la colección <b>',_nombre,'</b> en el fondo documental <b>',(
						SELECT nombre FROM fondos_documentales WHERE id = _fondo::INT
					),'</b>.')));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_nombre) = (SELECT traducir(nombre) FROM colecciones WHERE traducir(nombre) = traducir(_nombre) AND
																																							 fondos_documentales_id = _fondo::INT AND
																																							 id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',
						CONCAT('Ya existe la colección <b>',_nombre,'</b> en el fondo documental <b>',(
							SELECT nombre FROM fondos_documentales WHERE id = _fondo::INT
						),'</b>.')));
				ELSE
					UPDATE colecciones SET
						nombre = _nombre,
					  descripcion = _descripcion,
					  fecha_registro = (CASE WHEN CHAR_LENGTH(TRIM(_fecha_registro))>0 THEN _fecha_registro::TIMESTAMP(0) ELSE NULL END),
					  fondos_documentales_id = _fondo::INT,
					  fecha_actualizacion = NOW()::FEC,
					  estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
							WHERE id = idnuevo;
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Colección modificada correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de colecciones.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de colecciones.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de colecciones.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;

--------ACTUALIZAR MATERIALES DOCUMENTALES---------
CREATE OR REPLACE FUNCTION ActualizarMaterialesDocumentales(
	IN _id TEXT,
	IN _nombre TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM materiales_documentos WHERE traducir(nombre) = traducir(_nombre) LIMIT 1) IS NULL THEN
				INSERT INTO materiales_documentos VALUES (
					DEFAULT,_nombre,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				);
				RETURN json_agg(json_build_object('estado',TRUE,'observacion','Material documental registrado correctamente.'));
			ELSE
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el material documental <b>',_nombre,'</b>.')));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_nombre) = (SELECT traducir(nombre) FROM materiales_documentos WHERE traducir(nombre) = traducir(_nombre) AND
																																									 id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el material documental <b>',_nombre,'</b>.')));
				ELSE
					UPDATE materiales_documentos SET nombre = _nombre,
																				 fecha_actualizacion = NOW()::FEC,
																				 estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
																				 WHERE id = idnuevo;
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Material documental modificado correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de materiales documentales.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de materiales documentales.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de materiales documentales.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;


--------ACTUALIZAR MATERIALES SOPORTES---------
CREATE OR REPLACE FUNCTION ActualizarMaterialesSoporte(
	IN _id TEXT,
	IN _nombre TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM material_soporte WHERE traducir(nombre) = traducir(_nombre) LIMIT 1) IS NULL THEN
				INSERT INTO material_soporte VALUES (
					DEFAULT,_nombre,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				);
				RETURN json_agg(json_build_object('estado',TRUE,'observacion','Material de soporte registrado correctamente.'));
			ELSE
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el material de soporte <b>',_nombre,'</b>.')));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_nombre) = (SELECT traducir(nombre) FROM material_soporte WHERE traducir(nombre) = traducir(_nombre) AND
																																									 id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el material de soporte <b>',_nombre,'</b>.')));
				ELSE
					UPDATE material_soporte SET nombre = _nombre,
																				 fecha_actualizacion = NOW()::FEC,
																				 estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
																				 WHERE id = idnuevo;
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Material de soporte modificado correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de materiales soporte.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de materiales soporte.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de materiales soporte.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;

--------ACTUALIZAR ARCHIVOS---------
CREATE OR REPLACE FUNCTION ActualizarArchivos(
	IN _id TEXT,
	IN _nombre TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM archivos WHERE traducir(nombre) = traducir(_nombre) LIMIT 1) IS NULL THEN
				INSERT INTO archivos VALUES (
					DEFAULT,_nombre,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				);
				RETURN json_agg(json_build_object('estado',TRUE,'observacion','Archivo registrado correctamente.'));
			ELSE
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el archivo <b>',_nombre,'</b>.')));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_nombre) = (SELECT traducir(nombre) FROM archivos WHERE traducir(nombre) = traducir(_nombre) AND
																																					  id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el archivo <b>',_nombre,'</b>.')));
				ELSE
					UPDATE archivos SET nombre = _nombre,
														  fecha_actualizacion = NOW()::FEC,
														  estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
														  WHERE id = idnuevo;
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Archivo modificado correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de archivos.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de archivos.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de archivos.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;


--------ACTUALIZAR SECCIONES---------
CREATE OR REPLACE FUNCTION ActualizarSecciones(
	IN _id TEXT,
	IN _archivo TEXT,
	IN _nombre TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM secciones WHERE traducir(nombre) = traducir(_nombre) AND archivos_id = _archivo::INT LIMIT 1) IS NULL THEN
				INSERT INTO secciones VALUES (
					DEFAULT,_archivo::INT,_nombre,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				);
				RETURN json_agg(json_build_object('estado',TRUE,'observacion','Sección registrada correctamente.'));
			ELSE
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',
					CONCAT('Ya existe la sección <b>',_nombre,'</b> en el archivo <b>',(
							SELECT nombre FROM archivos WHERE id = _archivo::INT
						),'</b>.')));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_nombre) = (SELECT traducir(nombre) FROM secciones WHERE traducir(nombre) = traducir(_nombre) AND
																																						 archivos_id = _archivo::INT AND
																																									 id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',
						CONCAT('Ya existe la sección <b>',_nombre,'</b> en el archivo <b>',(
							SELECT nombre FROM archivos WHERE id = _archivo::INT
						),'</b>.')));
				ELSE
					UPDATE secciones SET
						nombre = _nombre,
					  archivos_id = _archivo::INT,
					  fecha_actualizacion = NOW()::FEC,
					  estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
							WHERE id = idnuevo;
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Sección modificada correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de secciones.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de secciones.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de secciones.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;

--------ACTUALIZAR NIVELES---------
CREATE OR REPLACE FUNCTION ActualizarNiveles(
	IN _id TEXT,
	IN _seccion TEXT,
	IN _nombre TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM niveles WHERE traducir(nombre) = traducir(_nombre) AND secciones_id = _seccion::INT LIMIT 1) IS NULL THEN
				INSERT INTO niveles VALUES (
					DEFAULT,_seccion::INT,_nombre,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				);
				RETURN json_agg(json_build_object('estado',TRUE,'observacion','Nivel registrado correctamente.'));
			ELSE
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',
					CONCAT('Ya existe el nivel <b>',_nombre,'</b> en la sección <b>',(
							SELECT nombre FROM secciones WHERE id = _seccion::INT
						),'</b>.')));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_nombre) = (SELECT traducir(nombre) FROM niveles WHERE traducir(nombre) = traducir(_nombre) AND
																																						 secciones_id = _seccion::INT AND
																																									 id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',
						CONCAT('Ya existe el nivel <b>',_nombre,'</b> en la sección <b>',(
							SELECT nombre FROM secciones WHERE id = _seccion::INT
						),'</b>.')));
				ELSE
					UPDATE niveles SET
						nombre = _nombre,
					  secciones_id = _seccion::INT,
					  fecha_actualizacion = NOW()::FEC,
					  estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
							WHERE id = idnuevo;
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Nivel modificado correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de niveles.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de niveles.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de niveles.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;

--------ACTUALIZAR CONTENEDORES---------
CREATE OR REPLACE FUNCTION ActualizarContenedores(
	IN _id TEXT,
	IN _nivel TEXT,
	IN _codigo TEXT,
	IN _nombre TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM contenedores WHERE traducir(nombre) = traducir(_nombre) AND niveles_id = _nivel::INT LIMIT 1) IS NULL THEN
				IF traducir(_codigo) NOT IN (SELECT traducir(codigo) FROM contenedores) THEN
					INSERT INTO contenedores VALUES (
						DEFAULT,_nivel::INT,_codigo,_nombre,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
					);
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Contenedor registrado correctamente.'));
				ELSE
					RETURN json_agg(json_build_object('estado',FALSE,'observacion','El código ingresado ya existe. Ingrese uno diferente por favor.'));
				END IF;
			ELSE
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',
					CONCAT('Ya existe el contenedor <b>',_nombre,'</b> en el nivel <b>',(
							SELECT nombre FROM niveles WHERE id = _nivel::INT
						),'</b>.')));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_nombre) = (SELECT traducir(nombre) FROM contenedores WHERE traducir(nombre) = traducir(_nombre) AND
																																						 niveles_id = _nivel::INT AND
																																									 id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',
						CONCAT('Ya existe el contenedor <b>',_nombre,'</b> en el nivel<b>',(
							SELECT nombre FROM niveles WHERE id = _nivel::INT
						),'</b>.')));
				ELSE
					IF traducir(_codigo) = (SELECT traducir(codigo) FROM contenedores WHERE traducir(codigo) = traducir(_codigo) AND
																																						 		 id != idnuevo LIMIT 1) THEN
						RETURN json_agg(json_build_object('estado',FALSE,'observacion','El código ingresado ya existe. Ingrese uno diferente por favor.'));
					ELSE
						UPDATE contenedores SET
							nombre = _nombre,
							codigo = _codigo,
							niveles_id = _nivel::INT,
							fecha_actualizacion = NOW()::FEC,
							estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
								WHERE id = idnuevo;
						RETURN json_agg(json_build_object('estado',TRUE,'observacion','Contenedor modificado correctamente.'));
					END IF;
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de contenedores.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de contenedores.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de contenedores.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;


--------ACTUALIZAR IDIOMAS---------
CREATE OR REPLACE FUNCTION ActualizarIdiomas(
	IN _id TEXT,
	IN _nombre TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM idiomas WHERE traducir(nombre) = traducir(_nombre) LIMIT 1) IS NULL THEN
				INSERT INTO idiomas VALUES (
					DEFAULT,_nombre,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				);
				RETURN json_agg(json_build_object('estado',TRUE,'observacion','Idioma registrado correctamente.'));
			ELSE
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el idioma <b>',_nombre,'</b>.')));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_nombre) = (SELECT traducir(nombre) FROM idiomas WHERE traducir(nombre) = traducir(_nombre) AND
																																					 id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el idioma <b>',_nombre,'</b>.')));
				ELSE
					UPDATE idiomas SET nombre = _nombre,
														 fecha_actualizacion = NOW()::FEC,
														 estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
														 WHERE id = idnuevo;
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Idioma modificado correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de idiomas.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de idiomas.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de idiomas.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;


--------ACTUALIZAR TOPONIMIA---------
CREATE OR REPLACE FUNCTION ActualizarToponimia(
	IN _id TEXT,
	IN _nombre TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM toponimia WHERE traducir(nombre) = traducir(_nombre) LIMIT 1) IS NULL THEN
				INSERT INTO toponimia VALUES (
					DEFAULT,_nombre,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				);
				RETURN json_agg(json_build_object('estado',TRUE,'observacion','Toponimia registrado correctamente.'));
			ELSE
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe la toponimia <b>',_nombre,'</b>.')));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_nombre) = (SELECT traducir(nombre) FROM toponimia WHERE traducir(nombre) = traducir(_nombre) AND
																																					 id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe la toponimia <b>',_nombre,'</b>.')));
				ELSE
					UPDATE toponimia SET nombre = _nombre,
														 fecha_actualizacion = NOW()::FEC,
														 estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
														 WHERE id = idnuevo;
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Toponimia modificado correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de toponimia.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de toponimia.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de toponimia.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;

--------ACTUALIZAR LÍNEAS DE INTERÉS---------
CREATE OR REPLACE FUNCTION ActualizarLineasInteres(
	IN _id TEXT,
	IN _nombre TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM lineas_interes WHERE traducir(nombre) = traducir(_nombre) LIMIT 1) IS NULL THEN
				INSERT INTO lineas_interes VALUES (
					DEFAULT,_nombre,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				);
				RETURN json_agg(json_build_object('estado',TRUE,'observacion','Línea de interés registrada correctamente.'));
			ELSE
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe la línea de interés <b>',_nombre,'</b>.')));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_nombre) = (SELECT traducir(nombre) FROM lineas_interes WHERE traducir(nombre) = traducir(_nombre) AND
																																					 id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe la línea de interés <b>',_nombre,'</b>.')));
				ELSE
					UPDATE lineas_interes SET nombre = _nombre,
																	  fecha_actualizacion = NOW()::FEC,
																	  estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
																	  WHERE id = idnuevo;
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Lína de interés modificada correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de líneas de interés.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de líneas de interés.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de líneas de interés.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;


--------ACTUALIZAR PERSONAJES Y GENERADORES---------
CREATE OR REPLACE FUNCTION ActualizarPersonajesGeneradores(
	IN _id TEXT,
	IN _cedula TEXT,
	IN _apellidos TEXT,
	IN _nombres TEXT,
	IN _lugar_nacimiento TEXT,
	IN _fecha_nacimiento TEXT,
	IN _fecha_disfuncion TEXT,
	IN _sexo TEXT,
	IN _foto TEXT,
	IN _nacionalidad TEXT,
	IN _organizacion TEXT,
	IN _alias TEXT,
	IN _descripcion TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
				idpersona INT;
BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM personas WHERE traducir(apellidos) = traducir(_apellidos) AND
					traducir(nombres) = traducir(_nombres) LIMIT 1) IS NULL THEN
				INSERT INTO personas(cedula,apellidos,nombres,lugar_nacimiento,fecha_nacimiento,fecha_disfuncion,sexo,nacionalidad,organizacion,
				foto_carnet,alias,descripcion,fecha_creacion,estado) VALUES(
					_cedula,_apellidos,_nombres,_lugar_nacimiento,
					CASE WHEN CHAR_LENGTH(TRIM(_fecha_nacimiento)) > 0 THEN _fecha_nacimiento::TIMESTAMP(0) ELSE NULL END,
					CASE WHEN CHAR_LENGTH(TRIM(_fecha_disfuncion)) > 0 THEN _fecha_disfuncion::TIMESTAMP(0) ELSE NULL END,
					_sexo,_nacionalidad,_organizacion,_foto,_alias,_descripcion,NOW()::FEC,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				);
				RETURN json_agg(json_build_object('estado',TRUE,'observacion','Personaje o generador registrado correctamente.'));
			ELSE
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el personaje o generador <b>',_nombres,' ',_apellidos,'</b>.')));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF idnuevo = (SELECT id FROM personas WHERE traducir(apellidos) = traducir(_apellidos) AND
																										traducir(nombres) = traducir(_nombres) AND
																																					 id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el personaje o generador <b>',_nombres,' ',_apellidos,'</b>.')));
				ELSE
					UPDATE personas SET cedula = _cedula,
															apellidos = _apellidos,
															nombres = _nombres,
															lugar_nacimiento = _lugar_nacimiento,
															fecha_nacimiento = CASE WHEN CHAR_LENGTH(TRIM(_fecha_nacimiento))>0 THEN _fecha_nacimiento::TIMESTAMP(0) ELSE NULL END,
															fecha_disfuncion = CASE WHEN CHAR_LENGTH(TRIM(_fecha_disfuncion))>0 THEN _fecha_disfuncion::TIMESTAMP(0) ELSE NULL END,
															sexo = _sexo,
															nacionalidad = _nacionalidad,
															organizacion = _organizacion,
															foto_carnet = _foto,
															alias = _alias,
															descripcion = _descripcion,
															fecha_actualizacion = NOW()::FEC,
															estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
															WHERE id = idnuevo;

					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Personaje o generador modificado correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de personajes o generadores.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de personajes o generadores.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de personajes o generadores.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;


--------ACTUALIZAR USUARIOS---------
CREATE OR REPLACE FUNCTION ActualizarUsuarios(
	IN _id TEXT,
	IN _cedula TEXT,
	IN _apellidos TEXT,
	IN _nombres TEXT,
	IN _sexo TEXT,
	IN _convencional TEXT,
	IN _celular TEXT,
	IN _parroquia TEXT,
	IN _direccion TEXT,
	IN _rol TEXT,
	IN _correo TEXT,
	IN _contrasena TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
				idpersona INT;
BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT id FROM usuarios WHERE traducir(correo) = traducir(_correo) LIMIT 1) IS NULL THEN
				INSERT INTO personas(cedula,apellidos,nombres,sexo,convencional,celular,parroquias_id,direccion,fecha_creacion,estado)
				VALUES(_cedula,_apellidos,_nombres,_sexo,_convencional,_celular,_parroquia::INT,_direccion,
				NOW()::FEC,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				)RETURNING id INTO idpersona;
				INSERT INTO usuarios VALUES (
					DEFAULT,idpersona,_rol::INT,_correo,pgp_sym_encrypt(_contrasena,'archivos_cca', 'compress-algo=1, cipher-algo=aes256'),
					TRUE,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				);
				RETURN json_agg(json_build_object('estado',TRUE,'observacion','Usuario registrado correctamente.'));
			ELSE
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el usuario <b>',_correo,'</b>.')));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_correo) = (SELECT traducir(correo) FROM usuarios WHERE traducir(correo) = traducir(_correo) AND
																																					 id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el usuario <b>',_correo,'</b>.')));
				ELSE
					UPDATE personas SET cedula = _cedula,
															apellidos = _apellidos,
															nombres = _nombres,
															sexo = _sexo,
															convencional = _convencional,
															celular = _celular,
															parroquias_id = _parroquia::INT,
															direccion = _direccion,
															fecha_actualizacion = NOW()::FEC,
															estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
															WHERE id = (SELECT personas_id FROM usuarios WHERE id = idnuevo);
					UPDATE usuarios SET roles_id = _rol::INT,
															correo = _correo,
															fecha_actualizacion = NOW()::FEC,
															estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
															WHERE id = idnuevo;
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Usuario modificado correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de usuarios.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de usuarios.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de usuarios.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;

--------ACTUALIZAR DOCUMENTOS---------
CREATE OR REPLACE FUNCTION ActualizarDocumentos(
	IN _id TEXT,
	IN _numero_ficha TEXT,
	IN _codigo_institucional TEXT,
	IN _codigo_patrimonial TEXT,
	IN _codigo_digital TEXT,
	IN _nombre TEXT,
	IN _nombre_sugerido TEXT,
	IN _extension TEXT,
	IN _estado_conservacion TEXT,
	IN _portada TEXT,
	IN _coleccion TEXT,
	IN _asunto_tema TEXT,
	IN _lugar_emision TEXT,
	IN _toponimia TEXT,
	IN _generadores TEXT,
	IN _personajes TEXT,
	IN _idiomas TEXT,
	IN _anios_criticos TEXT,
	IN _palabras_claves TEXT,
	IN _descripcion TEXT,
	IN _transcripcion TEXT,
	IN _contenedor TEXT,
	IN _formatos TEXT,
	IN _tipo_material TEXT,
	IN _material_soporte TEXT,
	IN _material_documento TEXT,
	IN _largo TEXT,
	IN _ancho TEXT,
	IN _estado_verificacion TEXT,
	IN _archivos TEXT,
	IN _ruta TEXT,
	IN _observaciones TEXT,
	IN _estado VARCHAR(15),
	IN _accion VARCHAR(15),
	IN _usuario VARCHAR(15)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
				iddocumento INT;
				idfichas INT;
				idfichatecnica INT;

				i INT DEFAULT 0;
				personajes_ TEXT[];
				generadores_ TEXT[];
				formatos_ TEXT[];
				tipo_material_ TEXT[];
				material_soporte_ TEXT[];
				material_documento_ TEXT[];
				idiomas_ TEXT[];
				toponimia_ TEXT[];
				archivos_ TEXT[];

BEGIN
	idnuevo := (SELECT limpiador(_id));
	CASE _accion
		WHEN 'registrar' THEN
			IF(SELECT numero FROM fichas WHERE traducir(numero) = traducir(_numero_ficha) LIMIT 1) IS NOT NULL THEN
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el número de ficha <b>',_numero_ficha,'</b>.')));
			ELSEIF(SELECT codigo_institucional FROM fichas_tecnicas WHERE traducir(codigo_institucional) = traducir(_codigo_institucional) LIMIT 1) IS NOT NULL THEN
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el código institucional<b>',_codigo_institucional,'</b>.')));
			/*ELSEIF(SELECT codigo_digital FROM fichas_tecnicas WHERE traducir(codigo_digital) = traducir(_codigo_digital) LIMIT 1) IS NOT NULL THEN
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el código digital<b>',_codigo_digital,'</b>.')));
			*/ELSEIF(SELECT nombre FROM fichas_tecnicas WHERE traducir(nombre) = traducir(_nombre) LIMIT 1) IS NOT NULL THEN
				RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe un documento con el nombre <b>',_nombre,'</b>.')));
			ELSE
				INSERT INTO documentos VALUES(DEFAULT,
					(SELECT LPAD((SELECT COUNT(codigo)+1 FROM documentos)::TEXT, 9, '0')),
					'DOCUMÉNTO HISTÓRICO')RETURNING id INTO iddocumento;
				INSERT INTO fichas VALUES (
					DEFAULT,(SELECT id FROM tipos_fichas WHERE nombre = 'FICHA TÉCNICA' LIMIT 1),
					iddocumento,_numero_ficha,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				)RETURNING id INTO idfichas;

				INSERT INTO fichas_tecnicas VALUES(
					DEFAULT,idfichas,_codigo_institucional,_codigo_patrimonial,_codigo_digital,_nombre,_nombre_sugerido,
					_extension,_estado_conservacion::INT,_estado_verificacion::INT,_portada,(CASE WHEN CHAR_LENGTH(TRIM(_coleccion))>0 THEN _coleccion::INT ELSE NULL END),_asunto_tema,_lugar_emision,
					_anios_criticos::JSON,_palabras_claves::JSON,_descripcion,_transcripcion,(CASE WHEN CHAR_LENGTH(TRIM(_contenedor))>0 THEN _contenedor::INT ELSE NULL END),
					_largo,_ancho,_observaciones,_usuario::INT,NULL,NOW()::FEC,NULL,CAST(COALESCE(_estado, 'TRUE') AS BOOL)
				)RETURNING id INTO idfichatecnica;

				IF CHAR_LENGTH(TRIM(_archivos)) > 0 THEN
					SELECT string_to_array(_archivos, ',') INTO archivos_; i := 0;
					LOOP IF i >= array_upper(archivos_, 1) THEN EXIT;
							 ELSE i := i + 1; INSERT INTO archivos_documentos VALUES(iddocumento,archivos_[i],_ruta,NULL,NULL,NOW()::FEC); END IF;
					END LOOP;
				END IF;
				IF CHAR_LENGTH(TRIM(_personajes)) > 0 THEN
					SELECT string_to_array(_personajes, ',') INTO personajes_; i := 0;
					LOOP IF i >= array_upper(personajes_, 1) THEN EXIT;
							 ELSE i := i + 1; INSERT INTO personajes_fichas_tecnicas VALUES(idfichatecnica,personajes_[i]::INT); END IF;
					END LOOP;
				END IF;

				IF CHAR_LENGTH(TRIM(_generadores)) > 0 THEN
					SELECT string_to_array(_generadores, ',') INTO generadores_; i := 0;
					LOOP IF i >= array_upper(generadores_, 1) THEN EXIT;
							 ELSE i := i + 1; INSERT INTO generadores_fichas_tecnicas VALUES(idfichatecnica,generadores_[i]::INT); END IF;
					END LOOP;
				END IF;

				IF CHAR_LENGTH(TRIM(_formatos)) > 0 THEN
					SELECT string_to_array(_formatos, ',') INTO formatos_; i := 0;
					LOOP IF i >= array_upper(formatos_, 1) THEN EXIT;
							 ELSE i := i + 1; INSERT INTO formatos_fichas_tecnicas VALUES(idfichatecnica,formatos_[i]::INT); END IF;
					END LOOP;
				END IF;

				IF CHAR_LENGTH(TRIM(_tipo_material)) > 0 THEN
					SELECT string_to_array(_tipo_material, ',') INTO tipo_material_; i := 0;
					LOOP IF i >= array_upper(tipo_material_, 1) THEN EXIT;
							 ELSE i := i + 1;INSERT INTO tipo_material_soporte_fichas_tecnicas VALUES(idfichatecnica,tipo_material_[i]::INT); END IF;
					END LOOP;
				END IF;

				IF CHAR_LENGTH(TRIM(_material_soporte)) > 0 THEN
					SELECT string_to_array(_material_soporte, ',') INTO material_soporte_; i := 0;
					LOOP IF i >= array_upper(material_soporte_, 1) THEN EXIT;
							 ELSE i := i + 1; INSERT INTO material_soporte_fichas_tecnicas VALUES(idfichatecnica,material_soporte_[i]::INT); END IF;
					END LOOP;
				END IF;

				IF CHAR_LENGTH(TRIM(_material_documento)) > 0 THEN
					SELECT string_to_array(_material_documento, ',') INTO material_documento_; i := 0;
					LOOP IF i >= array_upper(material_documento_, 1) THEN EXIT;
							 ELSE i := i + 1; INSERT INTO materiales_documentos_fichas_tecnicas VALUES(idfichatecnica,material_documento_[i]::INT); END IF;
					END LOOP;
				END IF;

				IF CHAR_LENGTH(TRIM(_idiomas)) > 0 THEN
					SELECT string_to_array(_idiomas, ',') INTO idiomas_; i := 0;
					LOOP IF i >= array_upper(idiomas_, 1) THEN EXIT;
							 ELSE i := i + 1; INSERT INTO idiomas_fichas_tecnicas VALUES(idfichatecnica,idiomas_[i]::INT); END IF;
					END LOOP;
				END IF;

				IF CHAR_LENGTH(TRIM(_toponimia)) > 0 THEN
					SELECT string_to_array(_toponimia, ',') INTO toponimia_; i := 0;
					LOOP IF i >= array_upper(toponimia_, 1) THEN EXIT;
							 ELSE i := i + 1; INSERT INTO toponimia_fichas_tecnicas VALUES(idfichatecnica,toponimia_[i]::INT); END IF;
					END LOOP;
				END IF;
				RETURN json_agg(json_build_object('estado',TRUE,'observacion','Documento registrado correctamente.'));
			END IF;
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_numero_ficha) = (SELECT traducir(numero) FROM fichas WHERE traducir(numero) = traducir(_numero_ficha) AND
																																					 id != (SELECT fichas_id FROM fichas_tecnicas WHERE id = idnuevo) LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el número de ficha <b>',_numero_ficha,'</b> en otro documento.')));
				ELSEIF traducir(_codigo_institucional) = (SELECT traducir(codigo_institucional) FROM fichas_tecnicas
					WHERE traducir(codigo_institucional) = traducir(_codigo_institucional) AND id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el código institucional <b>',_nombre,'</b> en otro documento.')));
				ELSEIF traducir(_nombre) = (SELECT traducir(nombre) FROM fichas_tecnicas WHERE traducir(nombre) = traducir(_nombre) AND id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe un documento con el nombre <b>',_nombre,'</b>.')));
				ELSE
					UPDATE fichas SET numero = _numero_ficha,
														fecha_actualizacion = NOW()::FEC,
														estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
														WHERE id = (SELECT fichas_id FROM fichas_tecnicas WHERE id = idnuevo);
					UPDATE fichas_tecnicas
						SET codigo_institucional = _codigo_institucional,
								codigo_patrimonial = _codigo_patrimonial,
								codigo_digital = _codigo_digital,
								nombre = _nombre,
								nombre_sugerido = _nombre_sugerido,
								estado_conservacion_id = _estado_conservacion::INT,
								estado_verificacion_id = _estado_verificacion::INT,
								portada = _portada,
								colecciones_id = (CASE WHEN CHAR_LENGTH(TRIM(_coleccion))>0 THEN _coleccion::INT ELSE NULL END),
								asunto_tema = _asunto_tema,
								lugar_emision = _lugar_emision,
								anios_criticos = _anios_criticos::JSONB,
								palabras_claves = _palabras_claves::JSONB,
								descripcion = _descripcion,
								transcripcion = _transcripcion,
								contenedores_id = (CASE WHEN CHAR_LENGTH(TRIM(_contenedor))>0 THEN _contenedor::INT ELSE NULL END),
								largo = _largo,
								ancho = _ancho,
								observaciones = _observaciones,
								fecha_actualizacion = NOW()::FEC,
								estado = CAST(COALESCE(_estado, 'TRUE') AS BOOL)
									WHERE id = idnuevo;
						DELETE FROM toponimia_fichas_tecnicas WHERE fichas_tecnicas_id = idnuevo;
						DELETE FROM personajes_fichas_tecnicas WHERE fichas_tecnicas_id = idnuevo;
						DELETE FROM generadores_fichas_tecnicas WHERE fichas_tecnicas_id = idnuevo;
						DELETE FROM formatos_fichas_tecnicas WHERE fichas_tecnicas_id = idnuevo;
						DELETE FROM tipo_material_soporte_fichas_tecnicas WHERE fichas_tecnicas_id = idnuevo;
						DELETE FROM material_soporte_fichas_tecnicas WHERE fichas_tecnicas_id = idnuevo;
						DELETE FROM materiales_documentos_fichas_tecnicas WHERE fichas_tecnicas_id = idnuevo;
						DELETE FROM idiomas_fichas_tecnicas WHERE fichas_tecnicas_id = idnuevo;

						IF CHAR_LENGTH(TRIM(_archivos)) > 0 THEN
							SELECT string_to_array(_archivos, ',') INTO archivos_; i := 0;
							LOOP IF i >= array_upper(archivos_, 1) THEN EXIT;
									 ELSE i := i + 1; INSERT INTO archivos_documentos VALUES(
										(SELECT documentos_id FROM fichas WHERE id = (SELECT fichas_id FROM fichas_tecnicas WHERE id = idnuevo))
									 ,archivos_[i],_ruta,NULL,NULL,NOW()::FEC); END IF;
							END LOOP;
						END IF;
						IF CHAR_LENGTH(TRIM(_personajes)) > 0 THEN
							SELECT string_to_array(_personajes, ',') INTO personajes_; i := 0;
							LOOP IF i >= array_upper(personajes_, 1) THEN EXIT;
									 ELSE i := i + 1; INSERT INTO personajes_fichas_tecnicas VALUES(idnuevo,personajes_[i]::INT); END IF;
							END LOOP;
						END IF;

						IF CHAR_LENGTH(TRIM(_generadores)) > 0 THEN
							SELECT string_to_array(_generadores, ',') INTO generadores_; i := 0;
							LOOP IF i >= array_upper(generadores_, 1) THEN EXIT;
									 ELSE i := i + 1; INSERT INTO generadores_fichas_tecnicas VALUES(idnuevo,generadores_[i]::INT); END IF;
							END LOOP;
						END IF;

						IF CHAR_LENGTH(TRIM(_formatos)) > 0 THEN
							SELECT string_to_array(_formatos, ',') INTO formatos_; i := 0;
							LOOP IF i >= array_upper(formatos_, 1) THEN EXIT;
									 ELSE i := i + 1; INSERT INTO formatos_fichas_tecnicas VALUES(idnuevo,formatos_[i]::INT); END IF;
							END LOOP;
						END IF;

						IF CHAR_LENGTH(TRIM(_tipo_material)) > 0 THEN
							SELECT string_to_array(_tipo_material, ',') INTO tipo_material_; i := 0;
							LOOP IF i >= array_upper(tipo_material_, 1) THEN EXIT;
									 ELSE i := i + 1;INSERT INTO tipo_material_soporte_fichas_tecnicas VALUES(idnuevo,tipo_material_[i]::INT); END IF;
							END LOOP;
						END IF;

						IF CHAR_LENGTH(TRIM(_material_soporte)) > 0 THEN
							SELECT string_to_array(_material_soporte, ',') INTO material_soporte_; i := 0;
							LOOP IF i >= array_upper(material_soporte_, 1) THEN EXIT;
									 ELSE i := i + 1; INSERT INTO material_soporte_fichas_tecnicas VALUES(idnuevo,material_soporte_[i]::INT); END IF;
							END LOOP;
						END IF;

						IF CHAR_LENGTH(TRIM(_material_documento)) > 0 THEN
							SELECT string_to_array(_material_documento, ',') INTO material_documento_; i := 0;
							LOOP IF i >= array_upper(material_documento_, 1) THEN EXIT;
									 ELSE i := i + 1; INSERT INTO materiales_documentos_fichas_tecnicas VALUES(idnuevo,material_documento_[i]::INT); END IF;
							END LOOP;
						END IF;

						IF CHAR_LENGTH(TRIM(_idiomas)) > 0 THEN
							SELECT string_to_array(_idiomas, ',') INTO idiomas_; i := 0;
							LOOP IF i >= array_upper(idiomas_, 1) THEN EXIT;
									 ELSE i := i + 1; INSERT INTO idiomas_fichas_tecnicas VALUES(idnuevo,idiomas_[i]::INT); END IF;
							END LOOP;
						END IF;

						IF CHAR_LENGTH(TRIM(_toponimia)) > 0 THEN
							SELECT string_to_array(_toponimia, ',') INTO toponimia_; i := 0;
							LOOP IF i >= array_upper(toponimia_, 1) THEN EXIT;
									 ELSE i := i + 1; INSERT INTO toponimia_fichas_tecnicas VALUES(idnuevo,toponimia_[i]::INT); END IF;
							END LOOP;
						END IF;
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Documento modificado correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de documentos.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de documentos.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de documentos.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;

---------ELIMINAR ARCHIVOS-----------
CREATE OR REPLACE FUNCTION EliminarArchivos(IN _iddocumento TEXT, IN _nombre TEXT, IN _usuario TEXT)
RETURNS JSON AS $$
BEGIN
	IF (SELECT documentos_id FROM archivos_documentos WHERE traducir(nombre) = traducir(_nombre)
		AND documentos_id = _iddocumento::INT LIMIT 1) IS NOT NULL THEN
		DELETE FROM archivos_documentos WHERE traducir(nombre) = traducir(_nombre) AND documentos_id = _iddocumento::INT;
		RETURN json_agg(json_build_object('estado',TRUE,'observacion','Archivo eliminado correctamente.'));
	ELSE
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','El archivo que desea eliminar no existe.'));
	END IF;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario::TEXT,SQLSTATE,SQLERRM,'Eliminación de archivos.','eliminar');
		RETURN json_agg(json_build_object(
				'estado',FALSE,
				'observacion','Ocurrió un error inesperado. Recargue la página o contáctese con el administrador de la aplicación.')
			);
END;
$$ LANGUAGE plpgsql;

--------APROBAR FICHA TÉCNICA---------
CREATE OR REPLACE FUNCTION AprobarDocumentos(
	IN _revisor TEXT,
	IN _ficha_tecnica TEXT,
	IN _usuario VARCHAR(45)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
BEGIN
	idnuevo := (SELECT limpiador(_ficha_tecnica));
		IF(SELECT id FROM fichas_tecnicas WHERE id = idnuevo LIMIT 1) IS NOT NULL THEN
			UPDATE fichas_tecnicas SET estado_verificacion_id = (SELECT id FROM estado_verificacion WHERE nombre = 'APROBADO'),
				fecha_actualizacion = NOW()::FEC
				WHERE id = idnuevo;
			RETURN json_agg(json_build_object('estado',TRUE,'observacion','Documento aprobado correctamente.'));
		ELSE
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','El documento que desea aprobar, ¡no existe!.'));
		END IF;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Aprobación de documentos.','aprobar');
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;

--------REPROBAR FICHA TÉCNICA---------
CREATE OR REPLACE FUNCTION ReprobarDocumentos(
	IN _revisor TEXT,
	IN _ficha_tecnica TEXT,
	IN _observaciones TEXT,
	IN _usuario VARCHAR(45)
)
RETURNS JSON AS $$
DECLARE idnuevo INT;
BEGIN
	idnuevo := (SELECT limpiador(_ficha_tecnica));
		IF(SELECT id FROM fichas_tecnicas WHERE id = idnuevo LIMIT 1) IS NOT NULL THEN
			UPDATE fichas_tecnicas SET estado_verificacion_id = (SELECT id FROM estado_verificacion WHERE nombre = 'BORRADOR'),
				observaciones = _observaciones,
				fecha_actualizacion = NOW()::FEC
				WHERE id = idnuevo;
			RETURN json_agg(json_build_object('estado',TRUE,'observacion','Documento reprobado correctamente.'));
		ELSE
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','El documento que desea reprobar, ¡no existe!.'));
		END IF;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Reprobación de documentos.','reprobar');
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;

--------ACTUALIZAR FAVORITOS---------
CREATE OR REPLACE FUNCTION ActualizarFavoritos(
	IN _usuario TEXT,
	IN _documento TEXT
)
RETURNS JSON AS $$
BEGIN
			IF(SELECT usuarios_id FROM favoritos WHERE usuarios_id = _usuario::INT AND documentos_id = _documento::INT LIMIT 1) IS NULL THEN
				INSERT INTO favoritos VALUES(_usuario::INT,_documento::INT,NOW()::FEC);
				RETURN json_agg(json_build_object('estado',TRUE,'accion','agregado','observacion','Documento agregado a favoritos.'));
			ELSE
				DELETE FROM favoritos WHERE usuarios_id = _usuario::INT AND documentos_id = _documento::INT;
				RETURN json_agg(json_build_object('estado',TRUE,'accion','quitado','observacion','Documento quitado de favoritos.'));
			END IF;

EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de favoritos.','actualizar');
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;


--------ACTUALIZAR DOCUMENTOS VISTOS---------
CREATE OR REPLACE FUNCTION ActualizarDocumentosVistos(
	IN _documento TEXT,
	IN _usuario TEXT
)
RETURNS JSON AS $$
BEGIN
	INSERT INTO documentos_vistos VALUES(DEFAULT,_documento::INT,_usuario::INT,NOW()::FEC);
	RETURN json_agg(json_build_object('estado',TRUE));
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de documentos vistos.','actualizar');
		RETURN json_agg(json_build_object('estado',FALSE));
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION actualizarperfil(
	_cedula TEXT,
	_apellidos TEXT,
	_nombres TEXT,
	_sexo TEXT,
	_convencional TEXT,
	_celular TEXT,
	_parroquia TEXT,
	_direccion TEXT,
	_correo TEXT,
	_usuario TEXT,
	_accion varchar
) RETURNS JSON AS $$
DECLARE idnuevo INT;
				idpersona INT;
BEGIN
	idnuevo := (SELECT limpiador(_usuario));
	CASE _accion
		WHEN 'modificar' THEN
			IF idnuevo IS NOT NULL THEN
				IF traducir(_correo) = (SELECT traducir(correo) FROM usuarios WHERE traducir(correo) = traducir(_correo) AND
																																					 id != idnuevo LIMIT 1) THEN
					RETURN json_agg(json_build_object('estado',FALSE,'observacion',CONCAT('Ya existe el usuario <b>',_correo,'</b>.')));
				ELSE
					UPDATE personas SET cedula = _cedula,
															apellidos = _apellidos,
															nombres = _nombres,
															sexo = _sexo,
															convencional = _convencional,
															celular = _celular,
															parroquias_id = _parroquia::INT,
															direccion = _direccion,
															fecha_actualizacion = NOW()::FEC
															WHERE id = (SELECT personas_id FROM usuarios WHERE id = idnuevo);
					UPDATE usuarios SET 
															correo = _correo,
															fecha_actualizacion = NOW()::FEC
															WHERE id = idnuevo;
					RETURN json_agg(json_build_object('estado',TRUE,'observacion','Usuario actualizado correctamente.'));
				END IF;
			ELSE
				PERFORM InsertarError(_usuario,NULL,'Alteración clave primaria.','Actualización de usuarios.',_accion);
				RETURN json_agg(json_build_object('estado',FALSE,'observacion','Alguna información fue alterada. Verifique por favor.'));
			END IF;
		ELSE
			PERFORM InsertarError(_usuario,NULL,'Alteración de petición.','Actualización de usuarios.',_accion);
			RETURN json_agg(json_build_object('estado',FALSE,'observacion','No se reconoció la petición. Los datos fueron alterados.'));
	END CASE;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(_usuario,SQLSTATE,SQLERRM,'Actualización de usuarios.',_accion);
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION cargarInicio() RETURNS JSON AS $$
BEGIN
	RETURN json_agg(json_build_object(
		'estado',TRUE,
		'usuarios',(SELECT COUNT(*) FROM usuarios WHERE roles_id = 3),
		'favoritos',(SELECT COUNT(*) FROM favoritos),
		'documentos',(SELECT COUNT(*) FROM fichas_tecnicas where estado_verificacion_id = 3),
		'pendientes',(SELECT COUNT(*) FROM fichas_tecnicas where estado_verificacion_id = 2)));			
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(NULL,SQLSTATE,SQLERRM,'Cargar contadores de inicio.','visualizar');
		RETURN json_agg(json_build_object('estado',FALSE,'observacion','Ocurrió un error inesperado. Por favor contáctese con el administrador de la aplicación.'));
END;
$$ LANGUAGE plpgsql;
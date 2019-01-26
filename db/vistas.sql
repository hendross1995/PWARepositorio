-- -----------------------------------------------------
/*CREATE OR REPLACE VIEW vmostrarusuarios AS
	SELECT id,
				 (SELECT CONCAT(apellidos,' ',nombres) FROM personas WHERE A.personas_id = id) AS nombres_usuario,
				 (SELECT nombre FROM roles WHERE A.roles_id = id) AS rol,
				 correo AS usuario,
				 fecha_creacion,
				 fecha_actualizacion,
				 estado
				 FROM usuarios A ORDER BY nombres_usuario;*/
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarfondos AS
	SELECT id,
				 nombre,
				 descripcion,
				 fecha_creacion,
				 fecha_actualizacion,
				 estado
				 FROM fondos_documentales A ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarocupaciones AS
	SELECT id,
		   nombre FROM ocupaciones ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarcolecciones AS
	SELECT id,
				 nombre,
				 descripcion,
				 fecha_registro::DATE,
				 (SELECT id FROM fondos_documentales WHERE id = A.fondos_documentales_id) AS idfondo,
				 (SELECT nombre FROM fondos_documentales WHERE id = A.fondos_documentales_id) AS fondo,
				 fecha_creacion,
				 fecha_actualizacion,
				 estado
 				 FROM colecciones A ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarmaterialesdocumentales AS
	SELECT id,
				 nombre,
				 fecha_creacion,
				 fecha_actualizacion,
				 estado
				 FROM materiales_documentos A ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarmaterialessoporte AS
	SELECT id,
				 nombre,
				 fecha_creacion,
				 fecha_actualizacion,
				 estado
				 FROM material_soporte A ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrararchivos AS
	SELECT id,
				 nombre,
				 fecha_creacion,
				 fecha_actualizacion,
				 estado
				 FROM archivos A ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarsecciones AS
	SELECT id,
				 nombre,
				 (SELECT id FROM archivos WHERE id = A.archivos_id) AS idarchivo,
				 (SELECT nombre FROM archivos WHERE id = A.archivos_id) AS archivo,
				 fecha_creacion,
				 fecha_actualizacion,
				 estado
 				 FROM secciones A ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarniveles AS
	SELECT id,
				 nombre,
				 (SELECT id FROM secciones WHERE id = A.secciones_id) AS idseccion,
				 (SELECT nombre FROM secciones WHERE id = A.secciones_id) AS seccion,
				 (SELECT id FROM archivos WHERE id = (SELECT archivos_id FROM secciones WHERE id = A.secciones_id)) AS idarchivo,
				 (SELECT nombre FROM archivos WHERE id = (SELECT archivos_id FROM secciones WHERE id = A.secciones_id)) AS archivo,
				 fecha_creacion,
				 fecha_actualizacion,
				 estado
 				 FROM niveles A ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarcontenedores AS
	SELECT id,
				 codigo,
				 nombre,
				 (SELECT id FROM niveles WHERE id = A.niveles_id) AS idnivel,
				 (SELECT nombre FROM niveles WHERE id = A.niveles_id) AS nivel,
				 (SELECT id FROM secciones WHERE id = (SELECT secciones_id FROM niveles WHERE id = A.niveles_id)) AS idseccion,
				 (SELECT nombre FROM secciones WHERE id = (SELECT secciones_id FROM niveles WHERE id = A.niveles_id)) AS seccion,
				 (SELECT id FROM archivos WHERE id = (
						SELECT archivos_id FROM secciones WHERe id = (
							SELECT id FROM secciones WHERE id = (SELECT secciones_id FROM niveles WHERE id = A.niveles_id)
						))) AS idarchivo,
				 (SELECT nombre FROM archivos WHERE id = (
						SELECT archivos_id FROM secciones WHERe id = (
							SELECT id FROM secciones WHERE id = (SELECT secciones_id FROM niveles WHERE id = A.niveles_id)
						))) AS archivo,
				 fecha_creacion,
				 fecha_actualizacion,
				 estado
 				 FROM contenedores A ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostraridiomas AS
	SELECT id,
				 nombre,
				 fecha_creacion,
				 fecha_actualizacion,
				 estado
				 FROM idiomas A ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrartoponimia AS
	SELECT id,
				 nombre,
				 fecha_creacion,
				 fecha_actualizacion,
				 estado
				 FROM toponimia A ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarlineasinteres AS
	SELECT id,
				 nombre,
				 fecha_creacion,
				 fecha_actualizacion,
				 estado
				 FROM lineas_interes A ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarpersonajesgeneradores AS
	SELECT id AS idpersona,
				 cedula,
				 apellidos,
				 nombres,
				 lugar_nacimiento,
				 fecha_nacimiento,
				 fecha_disfuncion,
				 sexo,
				 foto_carnet,
				 nacionalidad,
				 organizacion,
				 alias,
				 descripcion,
				 estado
		FROM personas WHERE id NOT IN (SELECT personas_id FROM usuarios)
		ORDER BY apellidos, nombres;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarroles AS
	SELECT id,
				 nombre
				 FROM roles A ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarusuarios AS
	SELECT B.id AS idusuario,
				 A.id AS idpersona,
				 A.cedula,
				 A.apellidos,
				 A.nombres,
				 C.id AS idrol,
				 C.nombre AS rol,
				 correo AS usuario,
				 pgp_sym_decrypt(B.contrasena::bytea,'archivos_cca') AS contrasena,
				 A.sexo,
				 A.convencional,
				 A.celular,
				 (SELECT id FROM provincias WHERE id = (
					SELECT provincias_id FROM cantones WHERE id = (
						SELECT cantones_id FROM parroquias WHERE id = A.parroquias_id
					))) AS idprovincia,
				 (SELECT nombre FROM provincias WHERE id = (
					SELECT provincias_id FROM cantones WHERE id = (
						SELECT cantones_id FROM parroquias WHERE id = A.parroquias_id
					))) AS provincia,
				 (SELECT id FROM cantones WHERE id = (
					SELECT cantones_id FROM parroquias WHERE id = A.parroquias_id
				 )) AS idcanton,
				 (SELECT nombre FROM cantones WHERE id = (
					SELECT cantones_id FROM parroquias WHERE id = A.parroquias_id
				 )) AS canton,
				 A.parroquias_id AS idparroquia,
				 (SELECT nombre FROM parroquias WHERE id = A.parroquias_id) AS parroquia,
				 A.direccion,
				 B.fecha_creacion,
				 B.fecha_actualizacion,
				 B.estado
		FROM personas A INNER JOIN usuarios B ON A.id = B.personas_id
										INNER JOIN roles C ON C.id = B.roles_id
		ORDER BY B.correo, A.apellidos, A.nombres;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarestadosconservacion AS
	SELECT id,
		   nombre FROM estado_conservacion ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarestadosverificacion AS
	SELECT id,
		   nombre FROM estado_verificacion ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarformatos AS
	SELECT id,
		   nombre,
		   fecha_creacion,
		   fecha_actualizacion,
		   estado FROM formatos ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrartipomaterialsoporte AS
	SELECT id,
		   nombre,
		   fecha_creacion,
		   fecha_actualizacion,
		   estado FROM tipo_material_soporte ORDER BY nombre;
-- -----------------------------------------------------

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarfichastecnicas AS
SELECT A.id AS iddocumento,
				 B.id AS idficha,
				 C.id AS idfichatecnica,
				 A.codigo,
				 B.numero,
				 C.codigo_institucional,
				 C.codigo_patrimonial,
				 C.codigo_digital,
				 C.nombre,
				 C.nombre_sugerido,
				 C.numero_extension,
				 C.estado_conservacion_id AS idestado_conservacion,
				 (SELECT nombre FROM estado_conservacion WHERE id = C.estado_conservacion_id) AS estado_conservacion,
				 C.portada,
				 (SELECT fondos_documentales_id FROM colecciones WHERE id = C.colecciones_id) AS idfondo,
				 (SELECT nombre FROM fondos_documentales WHERE id = (SELECT fondos_documentales_id FROM colecciones WHERE id = C.colecciones_id)) AS fondo,
				 colecciones_id AS idcoleccion,
				 (SELECT nombre FROM colecciones WHERE id = C.colecciones_id) AS coleccion,
				 C.asunto_tema,
				 C.lugar_emision,
				 (SELECT json_agg(json_build_object(
						'id',X.id::TEXT,
						'nombre',X.nombre,
						'estado',X.estado
					)ORDER BY X.nombre)
					FROM toponimia X INNER JOIN toponimia_fichas_tecnicas Y ON X.id = Y.toponimia_id WHERE Y.fichas_tecnicas_id = C.id)::JSONB AS toponimia,
				 (SELECT json_agg(json_build_object(
						'id',X.id::TEXT,
						'nombres',CONCAT(X.nombres,' ',X.apellidos),
						'estado',X.estado
					)ORDER BY CONCAT(X.nombres,' ',X.apellidos))
					FROM personas X INNER JOIN generadores_fichas_tecnicas Y ON X.id = Y.personas_id WHERE Y.fichas_tecnicas_id = C.id)::JSONB AS generadores,
					(SELECT json_agg(json_build_object(
						'id',X.id::TEXT,
						'nombres',CONCAT(X.nombres,' ',X.apellidos),
						'estado',X.estado
					)ORDER BY CONCAT(X.nombres,' ',X.apellidos))
					FROM personas X INNER JOIN personajes_fichas_tecnicas Y ON X.id = Y.personas_id WHERE Y.fichas_tecnicas_id = C.id)::JSONB AS personajes,
					(SELECT json_agg(json_build_object(
						'id',X.id::TEXT,
						'nombre',X.nombre,
						'estado',X.estado
					)ORDER BY X.nombre)
					FROM idiomas X INNER JOIN idiomas_fichas_tecnicas Y ON X.id = Y.idiomas_id WHERE Y.fichas_tecnicas_id = C.id)::JSONB AS idiomas,
					C.anios_criticos,
					C.palabras_claves,
					C.descripcion,
					C.transcripcion,
					(SELECT id FROM archivos WHERE id = (
						SELECT archivos_id FROM secciones WHERe id = (
							SELECT secciones_id FROM niveles WHERE id = (
								SELECT niveles_id FROM contenedores WHERE id = C.contenedores_id
							)))) AS idarchivo,
					(SELECT nombre FROM archivos WHERE id = (
						SELECT archivos_id FROM secciones WHERe id = (
							SELECT secciones_id FROM niveles WHERE id = (
								SELECT niveles_id FROM contenedores WHERE id = C.contenedores_id
							)))) AS archivo,
					(SELECT id FROM secciones WHERE id = (SELECT secciones_id FROM niveles WHERE id = (
						SELECT niveles_id FROM contenedores WHERE id = C.contenedores_id
					))) AS idseccion,
					(SELECT nombre FROM secciones WHERE id = (SELECT secciones_id FROM niveles WHERE id = (
						SELECT niveles_id FROM contenedores WHERE id = C.contenedores_id
					))) AS seccion,
					(SELECT id FROM niveles WHERE id = (SELECT niveles_id FROM contenedores WHERE id = C.contenedores_id)) AS idnivel,
					(SELECT nombre FROM niveles WHERE id = (SELECT niveles_id FROM contenedores WHERE id = C.contenedores_id)) AS nivel,
					C.contenedores_id AS idcontenedor,
					(SELECT nombre FROM contenedores WHERE id = C.contenedores_id) AS contenedor,
					(SELECT json_agg(json_build_object(
						'id',X.id::TEXT,
						'nombre',X.nombre,
						'estado',X.estado
					)ORDER BY X.nombre)
					FROM formatos X INNER JOIN formatos_fichas_tecnicas Y ON X.id = Y.formatos_id WHERE Y.fichas_tecnicas_id = C.id)::JSONB AS formatos,
					(SELECT json_agg(json_build_object(
						'id',X.id::TEXT,
						'nombre',X.nombre,
						'estado',X.estado
					)ORDER BY X.nombre)
					FROM tipo_material_soporte X INNER JOIN tipo_material_soporte_fichas_tecnicas Y ON X.id = Y.tipo_material_soporte_id
						WHERE Y.fichas_tecnicas_id = C.id)::JSONB AS tipo_material_soporte,
					(SELECT json_agg(json_build_object(
						'id',X.id::TEXT,
						'nombre',X.nombre,
						'estado',X.estado
					)ORDER BY X.nombre)
					FROM material_soporte X INNER JOIN material_soporte_fichas_tecnicas Y ON X.id = Y.material_soporte_id
						WHERE Y.fichas_tecnicas_id = C.id)::JSONB AS material_soporte,
					(SELECT json_agg(json_build_object(
						'id',X.id::TEXT,
						'nombre',X.nombre,
						'estado',X.estado
					)ORDER BY X.nombre)
					FROM materiales_documentos X INNER JOIN materiales_documentos_fichas_tecnicas Y ON X.id = Y.materiales_documentos_id
						WHERE Y.fichas_tecnicas_id = C.id)::JSONB AS materiales_documentos,
					C.largo,
					C.ancho,
					(SELECT json_agg(json_build_object(
						'iddocumento',X.documentos_id::TEXT,
						'nombre',X.nombre,
						'ruta',X.ruta,
						'estado',X.estado
					)ORDER BY X.nombre)
					FROM archivos_documentos X WHERE X.documentos_id = A.id)::JSONB AS archivos_documentos,
					C.observaciones,
					(SELECT id FROM usuarios WHERE id = C.usuario_creador) AS idcreador,
					(SELECT correo FROM usuarios WHERE id = C.usuario_creador) AS creador,
					(SELECT id FROM usuarios WHERE id = C.usuario_revisor) AS idrevisor,
					(SELECT correo FROM usuarios WHERE id = C.usuario_revisor) AS revisor,
					(SELECT id FROM estado_verificacion WHERE id = C.estado_verificacion_id) AS idestado_verificacion,
					(SELECT nombre FROM estado_verificacion WHERE id = C.estado_verificacion_id) AS estado_verificacion,
					C.fecha_creacion,
					C.fecha_actualizacion,
					C.estado
						FROM documentos A INNER JOIN fichas B ON A.id = B.documentos_id
															INNER JOIN fichas_tecnicas C ON B.id = C.fichas_id
			 ORDER BY C.nombre;
-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrardocumentosmasvistos AS
SELECT (COUNT(B.documentos_id))AS total,
			 B.documentos_id AS iddocumento,
			 A.codigo,
			 A.numero,
			 A.codigo_institucional,
			 A.codigo_patrimonial,
			 A.codigo_digital,
			 A.nombre,
			 A.nombre_sugerido,
			 A.numero_extension,
			 A.estado_conservacion,
			 A.portada,
			 A.fondo,
			 A.coleccion,
			 A.asunto_tema,
			 A.lugar_emision,
			 A.toponimia,
			 A.generadores,
			 A.personajes,
			 A.idiomas,
			 A.anios_criticos,
			 A.palabras_claves,
			 A.descripcion,
			 A.transcripcion,
			 A.archivo,
			 A.seccion,
			 A.nivel,
			 A.contenedor,
			 A.formatos,
			 A.material_soporte,
			 A.materiales_documentos,
			 A.largo,
			 A.ancho,
			 A.archivos_documentos,
			 A.observaciones,
			 A.estado_verificacion,
			 A.estado
	FROM vmostrarfichastecnicas A INNER JOIN documentos_vistos B ON A.iddocumento = B.documentos_id
		GROUP BY b.documentos_id,
						 A.codigo,
						 A.numero,
						 A.codigo_institucional,
						 A.codigo_patrimonial,
						 A.codigo_digital,
						 A.nombre,
						 A.nombre_sugerido,
						 A.numero_extension,
						 A.estado_conservacion,
						 A.portada,
						 A.fondo,
						 A.coleccion,
						 A.asunto_tema,
						 A.lugar_emision,
						 A.toponimia,
						 A.generadores,
						 A.personajes,
						 A.idiomas,
						 A.anios_criticos,
						 A.palabras_claves,
						 A.descripcion,
						 A.transcripcion,
						 A.archivo,
						 A.seccion,
						 A.nivel,
						 A.contenedor,
						 A.formatos,
						 A.material_soporte,
						 A.materiales_documentos,
						 A.largo,
						 A.ancho,
						 A.archivos_documentos,
						 A.observaciones,
						 A.estado_verificacion,
			 			 A.estado
						 
	 ORDER BY total DESC;

-- -----------------------------------------------------
CREATE OR REPLACE VIEW vmostrarfavoritos AS
	SELECT *,
	(SELECT json_agg(json_build_object('idusuario',X.usuarios_id::TEXT)ORDER BY X.fecha_creacion)
											FROM favoritos X WHERE X.documentos_id = iddocumento)::JSONB AS usuarios_favoritos
	FROM vmostrarfichastecnicas ORDER BY nombre;

-- -----------------------------------------------------

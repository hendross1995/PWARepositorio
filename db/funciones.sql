CREATE EXTENSION pgcrypto;

--------INSERTAR ERRORES----------
CREATE OR REPLACE FUNCTION InsertarError(
_usuario TEXT,
_error TEXT, 
_mensaje TEXT,
_proceso TEXT,
_accion TEXT)
RETURNS BOOL AS $$
BEGIN
	INSERT INTO errores VALUES(DEFAULT,limpiador(_usuario),_error,_mensaje,_proceso,_accion,NOW()::FEC);
	RETURN TRUE;
EXCEPTION
   WHEN OTHERS THEN
		INSERT INTO errores VALUES(DEFAULT,NULL,SQLSTATE,SQLERRM,'Error desconocido','desconocido',NOW()::FEC);
		RETURN FALSE;
END;
$$ LANGUAGE plpgsql;

--------Ver estado del usuario----------
CREATE OR REPLACE FUNCTION VerEstadoUsuario(_usuario text)
  RETURNS JSON AS $$
	DECLARE _estado BOOL;
BEGIN
	SELECT estado INTO _estado FROM usuarios WHERE id = _usuario::INT;
	IF _estado THEN
		RETURN json_agg(json_build_object('estado',_estado));
	ELSE
		RETURN json_agg(json_build_object(
					'estado',FALSE,
					'observacion','Este usuario no existe.')
				);
	END IF;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(NULL,SQLSTATE,SQLERRM,'Verificación estado usuario.','general');
		RETURN json_agg(json_build_object(
					'estado',FALSE,
					'observacion','Sucedió un error insperedo al verificar el estado del usuario.')
				);
END;
$$ LANGUAGE plpgsql;


-----------VER ESTADO DEL CORREO-------------------
CREATE OR REPLACE FUNCTION VerExisteCorreo(_correo TEXT)
  RETURNS JSON AS $$
	DECLARE _estado BOOL;
BEGIN
	SELECT estado INTO _estado FROM usuarios WHERE LOWER(TRIM(correo)) = LOWER(TRIM(_correo)) LIMIT 1;
	IF _estado THEN
		RETURN json_agg(json_build_object('estado',_estado));
	ELSE
		RETURN json_agg(json_build_object(
					'estado',FALSE,
					'observacion','Este correo no existe.')
				);
	END IF;
EXCEPTION
   WHEN OTHERS THEN
		PERFORM InsertarError(NULL,SQLSTATE,SQLERRM,'Verificación correo usuario.','general');
		RETURN json_agg(json_build_object(
					'estado',FALSE,
					'observacion','Sucedió un error insperedo al verificar si existe el correo del usuario.')
				);
END;
$$ LANGUAGE plpgsql;

--------LIMPIADOR DE ID----------
CREATE OR REPLACE FUNCTION limpiador(IN _valor TEXT)
RETURNS BIGINT AS $$
BEGIN
	RETURN (
			CASE WHEN regexp_replace(_valor, '[^\w],.-+\?/', '') ~ '^[0-9]+$'
           THEN regexp_replace(_valor, '[^\w],.-+\?/', '')::BIGINT
      END); 
END;
$$ LANGUAGE plpgsql;

--------TRADUCTOR----------
CREATE OR REPLACE FUNCTION traducir(IN _valor TEXT)
RETURNS TEXT AS $$
BEGIN
	RETURN (SELECT TRIM(regexp_replace(TRANSLATE(LOWER(_valor),'àèìòùñÀÈÌÒÙáéíóúÁÉÍÓÚäëïöüÄËÏÖÜÑ','aeiounAEIOUaeiouAEIOUaeiouAEIOUN'), '\s+', ' ', 'g'))); 
END;
$$ LANGUAGE plpgsql;


--------VER RUTA DOCUMENTOS----------
CREATE OR REPLACE FUNCTION VerRutaDocumentos(IN idficha_tecnica TEXT , IN _accion TEXT)
RETURNS JSON AS $$
BEGIN
	IF _accion = 'registrar' THEN
		RETURN
			(json_agg(json_build_object(
				'estado',TRUE,
				'observacion',(SELECT LPAD((SELECT COUNT(codigo)+1 FROM documentos)::TEXT, 9, '0')))
			));
	ELSEIF _accion = 'modificar' THEN
		RETURN (
			json_agg(json_build_object(
				'estado',TRUE,
				'observacion',(SELECT codigo FROM documentos WHERE id = (SELECT documentos_id FROM fichas WHERE id = (
			SELECT fichas_id FROM fichas_tecnicas WHERE id = idficha_tecnica::INT LIMIT 1
		))))));
	ELSE
		RETURN
			(json_agg(json_build_object(
				'estado',TRUE,
				'observacion',(SELECT codigo FROM documentos WHERE id = idficha_tecnica::INT))
			));
	END IF;
END;
$$ LANGUAGE plpgsql;
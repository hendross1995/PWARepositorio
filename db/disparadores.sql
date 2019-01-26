--VALIDAR TABLA FONDOS--
CREATE OR REPLACE FUNCTION fvalidar_fondos() RETURNS TRIGGER AS $$ BEGIN
  IF CHAR_LENGTH(TRIM(NEW.nombre)) < 1 THEN
		NEW.nombre = 'No registrado';
	END IF;
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	NEW.nombre := LEFT(UPPER(TRIM(regexp_replace(NEW.nombre, '\s+', ' ', 'g'))),100);
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_fondos ON fondos_documentales;
CREATE TRIGGER validar_fondos BEFORE INSERT OR UPDATE ON fondos_documentales FOR EACH ROW EXECUTE PROCEDURE fvalidar_fondos();

--VALIDAR TABLA COLECCIONES--
CREATE OR REPLACE FUNCTION fvalidar_colecciones() RETURNS TRIGGER AS $$ BEGIN
  IF CHAR_LENGTH(TRIM(NEW.nombre)) < 1 THEN
		NEW.nombre = 'No registrado';
	END IF;
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	NEW.nombre := LEFT(UPPER(TRIM(regexp_replace(NEW.nombre, '\s+', ' ', 'g'))),100);
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_colecciones ON colecciones;
CREATE TRIGGER validar_colecciones BEFORE INSERT OR UPDATE ON colecciones FOR EACH ROW EXECUTE PROCEDURE fvalidar_colecciones();

--VALIDAR TABLA MATERIALES DOCUMENTALES--
CREATE OR REPLACE FUNCTION fvalidar_materiales_documentales() RETURNS TRIGGER AS $$ BEGIN
  IF CHAR_LENGTH(TRIM(NEW.nombre)) < 1 THEN
		NEW.nombre = 'No registrado';
	END IF;
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	NEW.nombre := LEFT(UPPER(TRIM(regexp_replace(NEW.nombre, '\s+', ' ', 'g'))),100);
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_materiales_documentales ON materiales_documentos;
CREATE TRIGGER validar_materiales_documentales BEFORE INSERT OR UPDATE ON materiales_documentos FOR EACH ROW EXECUTE PROCEDURE fvalidar_materiales_documentales();


--VALIDAR TABLA MATERIALES SOPORTE--
CREATE OR REPLACE FUNCTION fvalidar_material_soporte() RETURNS TRIGGER AS $$ BEGIN
  IF CHAR_LENGTH(TRIM(NEW.nombre)) < 1 THEN
		NEW.nombre = 'No registrado';
	END IF;
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	NEW.nombre := LEFT(UPPER(TRIM(regexp_replace(NEW.nombre, '\s+', ' ', 'g'))),100);
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_material_soporte ON material_soporte;
CREATE TRIGGER validar_material_soporte BEFORE INSERT OR UPDATE ON material_soporte FOR EACH ROW EXECUTE PROCEDURE fvalidar_material_soporte();


--VALIDAR TABLA ARCHIVOS--
CREATE OR REPLACE FUNCTION fvalidar_archivos() RETURNS TRIGGER AS $$ BEGIN
  IF CHAR_LENGTH(TRIM(NEW.nombre)) < 1 THEN
		NEW.nombre = 'No registrado';
	END IF;
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	NEW.nombre := LEFT(UPPER(TRIM(regexp_replace(NEW.nombre, '\s+', ' ', 'g'))),100);
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_archivos ON archivos;
CREATE TRIGGER validar_archivos BEFORE INSERT OR UPDATE ON archivos FOR EACH ROW EXECUTE PROCEDURE fvalidar_archivos();


--VALIDAR TABLA SECCIONES--
CREATE OR REPLACE FUNCTION fvalidar_secciones() RETURNS TRIGGER AS $$ BEGIN
  IF CHAR_LENGTH(TRIM(NEW.nombre)) < 1 THEN
		NEW.nombre = 'No registrado';
	END IF;
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	NEW.nombre := LEFT(UPPER(TRIM(regexp_replace(NEW.nombre, '\s+', ' ', 'g'))),100);
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_secciones ON secciones;
CREATE TRIGGER validar_secciones BEFORE INSERT OR UPDATE ON secciones FOR EACH ROW EXECUTE PROCEDURE fvalidar_secciones();

--VALIDAR TABLA NIVELES--
CREATE OR REPLACE FUNCTION fvalidar_niveles() RETURNS TRIGGER AS $$ BEGIN
  IF CHAR_LENGTH(TRIM(NEW.nombre)) < 1 THEN
		NEW.nombre = 'No registrado';
	END IF;
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	NEW.nombre := LEFT(UPPER(TRIM(regexp_replace(NEW.nombre, '\s+', ' ', 'g'))),100);
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_niveles ON niveles;
CREATE TRIGGER validar_niveles BEFORE INSERT OR UPDATE ON niveles FOR EACH ROW EXECUTE PROCEDURE fvalidar_niveles();

--VALIDAR TABLA CONTENEDORES--
CREATE OR REPLACE FUNCTION fvalidar_contenedores() RETURNS TRIGGER AS $$ BEGIN
  IF CHAR_LENGTH(TRIM(NEW.codigo)) < 1 THEN
		NEW.codigo = 'No registrado';
	END IF;
	IF CHAR_LENGTH(TRIM(NEW.nombre)) < 1 THEN
		NEW.nombre = 'No registrado';
	END IF;
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	NEW.codigo := LEFT(UPPER(TRIM(regexp_replace(NEW.codigo, '\s+', ' ', 'g'))),20);
	NEW.nombre := LEFT(UPPER(TRIM(regexp_replace(NEW.nombre, '\s+', ' ', 'g'))),100);
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_contenedores ON contenedores;
CREATE TRIGGER validar_contenedores BEFORE INSERT OR UPDATE ON contenedores FOR EACH ROW EXECUTE PROCEDURE fvalidar_contenedores();


--VALIDAR TABLA IDIOMAS--
CREATE OR REPLACE FUNCTION fvalidar_idiomas() RETURNS TRIGGER AS $$ BEGIN
  IF CHAR_LENGTH(TRIM(NEW.nombre)) < 1 THEN
		NEW.nombre = 'No registrado';
	END IF;
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	NEW.nombre := LEFT(UPPER(TRIM(regexp_replace(NEW.nombre, '\s+', ' ', 'g'))),100);
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_idiomas ON idiomas;
CREATE TRIGGER validar_idiomas BEFORE INSERT OR UPDATE ON idiomas FOR EACH ROW EXECUTE PROCEDURE fvalidar_idiomas();


--VALIDAR TABLA TOPONIMIA--
CREATE OR REPLACE FUNCTION fvalidar_toponimia() RETURNS TRIGGER AS $$ BEGIN
  IF CHAR_LENGTH(TRIM(NEW.nombre)) < 1 THEN
		NEW.nombre = 'No registrado';
	END IF;
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	NEW.nombre := LEFT(UPPER(TRIM(regexp_replace(NEW.nombre, '\s+', ' ', 'g'))),500);
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_toponimia ON toponimia;
CREATE TRIGGER validar_toponimia BEFORE INSERT OR UPDATE ON toponimia FOR EACH ROW EXECUTE PROCEDURE fvalidar_toponimia();


--VALIDAR TABLA LÍNEAS DE INTERÉS--
CREATE OR REPLACE FUNCTION fvalidar_lineas_interes() RETURNS TRIGGER AS $$ BEGIN
  IF CHAR_LENGTH(TRIM(NEW.nombre)) < 1 THEN
		NEW.nombre = 'No registrado';
	END IF;
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	NEW.nombre := LEFT(UPPER(TRIM(regexp_replace(NEW.nombre, '\s+', ' ', 'g'))),100);
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_lineas_interes ON lineas_interes;
CREATE TRIGGER validar_lineas_interes BEFORE INSERT OR UPDATE ON lineas_interes FOR EACH ROW EXECUTE PROCEDURE fvalidar_lineas_interes();

--VALIDAR TABLA PERSONAS--
CREATE OR REPLACE FUNCTION fvalidar_personas() RETURNS TRIGGER AS $$ BEGIN
  NEW.cedula := LEFT(UPPER(TRIM(regexp_replace(NEW.cedula, '\s+', ' ', 'g'))),13);
	NEW.apellidos := LEFT(UPPER(TRIM(regexp_replace(NEW.apellidos, '\s+', ' ', 'g'))),100);
	NEW.nombres := LEFT(UPPER(TRIM(regexp_replace(NEW.nombres, '\s+', ' ', 'g'))),100);
	NEW.alias := LEFT(UPPER(TRIM(regexp_replace(NEW.alias, '\s+', ' ', 'g'))),200);
	NEW.lugar_nacimiento := LEFT(UPPER(TRIM(regexp_replace(NEW.lugar_nacimiento, '\s+', ' ', 'g'))),100);
	--IF CHAR_LENGTH(TRIM(NEW.fecha_nacimiento::TEXT)) <= 0 THEN NEW.fecha_nacimiento = NULL; END IF;
	--IF CHAR_LENGTH(TRIM(NEW.fecha_disfuncion::TEXT)) <= 0 THEN NEW.fecha_disfuncion = NULL; END IF;
	IF NEW.sexo != 'M' AND NEW.sexo != 'F' AND NEW.sexo != 'O' THEN NEW.sexo = 'N'; END IF;
	NEW.nacionalidad := LEFT(UPPER(TRIM(regexp_replace(NEW.nacionalidad, '\s+', ' ', 'g'))),45);
	NEW.direccion := LEFT(UPPER(TRIM(regexp_replace(NEW.direccion, '\s+', ' ', 'g'))),200);
	NEW.organizacion := LEFT(UPPER(TRIM(regexp_replace(NEW.organizacion, '\s+', ' ', 'g'))),100);
	NEW.acercademi := UPPER(TRIM(regexp_replace(NEW.acercademi, '\s+', ' ', 'g')));
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_personas ON personas;
CREATE TRIGGER validar_personas BEFORE INSERT OR UPDATE ON personas FOR EACH ROW EXECUTE PROCEDURE fvalidar_personas();

--VALIDAR TABLA FICHAS--
CREATE OR REPLACE FUNCTION fvalidar_fichas() RETURNS TRIGGER AS $$ BEGIN
  IF CHAR_LENGTH(TRIM(NEW.numero)) < 1 THEN
		NEW.numero = 'No registrado';
	END IF;
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	NEW.numero := LEFT(UPPER(TRIM(regexp_replace(NEW.numero, '\s+', ' ', 'g'))),45);
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_fichas ON fichas;
CREATE TRIGGER validar_fichas BEFORE INSERT OR UPDATE ON fichas FOR EACH ROW EXECUTE PROCEDURE fvalidar_fichas();


--VALIDAR TABLA FICHAS TÉCNICAS--
CREATE OR REPLACE FUNCTION fvalidar_fichas_tecnicas() RETURNS TRIGGER AS $$ BEGIN
  IF CHAR_LENGTH(TRIM(NEW.codigo_institucional)) < 1 THEN
		NEW.codigo_institucional = 'No registrado';
	END IF;
	IF CHAR_LENGTH(TRIM(NEW.codigo_patrimonial)) < 1 THEN
		NEW.codigo_patrimonial = 'No registrado';
	END IF;
	IF CHAR_LENGTH(TRIM(NEW.codigo_digital)) < 1 THEN
		NEW.codigo_digital = 'No registrado';
	END IF;
	IF CHAR_LENGTH(TRIM(NEW.nombre)) < 1 THEN
		NEW.nombre = 'No registrado';
	END IF;
	IF CHAR_LENGTH(TRIM(NEW.nombre_sugerido)) < 1 THEN
		NEW.nombre_sugerido = 'No registrado';
	END IF;
	IF CHAR_LENGTH(TRIM(NEW.numero_extension)) < 1 THEN
		NEW.numero_extension = 'No registrado';
	END IF;
	IF CHAR_LENGTH(TRIM(NEW.asunto_tema)) < 1 THEN
		NEW.asunto_tema = 'No registrado';
	END IF;
	IF CHAR_LENGTH(TRIM(NEW.lugar_emision)) < 1 THEN
		NEW.lugar_emision = 'No registrado';
	END IF;
	IF NEW.estado IS NOT TRUE AND NEW.estado IS NOT FALSE THEN NEW.estado = FALSE; END IF;
	NEW.codigo_institucional := LEFT(UPPER(TRIM(regexp_replace(NEW.codigo_institucional, '\s+', ' ', 'g'))),100);
	NEW.codigo_patrimonial := LEFT(UPPER(TRIM(regexp_replace(NEW.codigo_patrimonial, '\s+', ' ', 'g'))),100);
	NEW.codigo_digital := LEFT(UPPER(TRIM(regexp_replace(NEW.codigo_digital, '\s+', ' ', 'g'))),100);
	NEW.nombre := LEFT(UPPER(TRIM(regexp_replace(NEW.nombre, '\s+', ' ', 'g'))),200);
	NEW.nombre_sugerido := LEFT(UPPER(TRIM(regexp_replace(NEW.nombre_sugerido, '\s+', ' ', 'g'))),500);
	NEW.numero_extension := LEFT(UPPER(TRIM(regexp_replace(NEW.numero_extension, '\s+', ' ', 'g'))),100);
	NEW.asunto_tema := UPPER(TRIM(regexp_replace(NEW.asunto_tema, '\s+', ' ', 'g')));
	NEW.lugar_emision := LEFT(UPPER(TRIM(regexp_replace(NEW.lugar_emision, '\s+', ' ', 'g'))),200);
	RETURN NEW; END$$
LANGUAGE plpgsql;
DROP TRIGGER IF EXISTS validar_fichas_tecnicas ON fichas_tecnicas;
CREATE TRIGGER validar_fichas_tecnicas BEFORE INSERT OR UPDATE ON fichas_tecnicas FOR EACH ROW EXECUTE PROCEDURE fvalidar_fichas_tecnicas();

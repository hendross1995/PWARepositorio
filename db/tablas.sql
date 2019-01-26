--DROP SCHEMA IF EXISTS archivos_ca ;
--CREATE SCHEMA IF NOT EXISTS archivos_ca DEFAULT CHARACTER SET utf8 ;
--USE archivos_ca ;

-- -----------------------------------------------------
-- Table paises
-- -----------------------------------------------------
DROP TABLE IF EXISTS paises ;
CREATE TABLE IF NOT EXISTS paises (
  id SERIAL PRIMARY KEY NOT NULL,
  codigo VARCHAR(10) NOT NULL,
  iso3166a1 CHAR(2) NOT NULL,
  iso3166a2 CHAR(3) NOT NULL,
  nombre VARCHAR(45) NOT NULL
);

-- -----------------------------------------------------
-- Table provincias
-- -----------------------------------------------------
DROP TABLE IF EXISTS provincias ;
CREATE TABLE IF NOT EXISTS provincias (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(100) NULL,
  paises_id INT NOT NULL,
  CONSTRAINT fk_provincias_paises1
    FOREIGN KEY (paises_id)
    REFERENCES paises (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_provincias_paises1_idx ON provincias (paises_id ASC);

-- -----------------------------------------------------
-- Table cantones
-- -----------------------------------------------------
DROP TABLE IF EXISTS cantones ;
CREATE TABLE IF NOT EXISTS cantones (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(100) NULL,
  provincias_id INT NOT NULL,
  CONSTRAINT fk_cantones_provincias1
    FOREIGN KEY (provincias_id)
    REFERENCES provincias (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_cantones_provincias1_idx ON cantones (provincias_id ASC);

-- -----------------------------------------------------
-- Table parroquias
-- -----------------------------------------------------
DROP TABLE IF EXISTS parroquias ;
CREATE TABLE IF NOT EXISTS parroquias (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(100) NULL,
  cantones_id INT NOT NULL,
  CONSTRAINT fk_parroquias_cantones1
    FOREIGN KEY (cantones_id)
    REFERENCES cantones (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_parroquias_cantones1_idx ON parroquias (cantones_id ASC);

-- -----------------------------------------------------
-- Table ocupaciones
-- -----------------------------------------------------
DROP TABLE IF EXISTS ocupaciones ;
CREATE TABLE IF NOT EXISTS ocupaciones (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(100) NOT NULL);

-- -----------------------------------------------------
-- Table personas
-- -----------------------------------------------------
DROP TABLE IF EXISTS personas ;
CREATE TABLE IF NOT EXISTS personas (
  id SERIAL PRIMARY KEY NOT NULL,
  cedula VARCHAR(13) NULL,
  apellidos VARCHAR(100) NULL,
  nombres VARCHAR(100) NULL,
  lugar_nacimiento VARCHAR(100) NULL,
  fecha_nacimiento DATE NULL DEFAULT NULL,
  fecha_disfuncion DATE NULL DEFAULT NULL,
  sexo CHAR(1) NULL,
  nacionalidad VARCHAR(45) NULL,
  celular VARCHAR(15) NULL,
  convencional VARCHAR(15) NULL,
  paises_id INT NULL,
  parroquias_id INT NULL,
  ocupaciones_id INT NULL,
  direccion TEXT NULL,
  organizacion VARCHAR(200) NULL,
  acercademi TEXT NULL,
  foto_carnet TEXT NULL,
  alias VARCHAR(100) NULL DEFAULT NULL,
  descripcion TEXT NULL DEFAULT NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE,
  CONSTRAINT fk_personas_paises1
    FOREIGN KEY (paises_id)
    REFERENCES paises (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_personas_parroquias1
    FOREIGN KEY (parroquias_id)
    REFERENCES parroquias (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_personas_ocupaciones1
    FOREIGN KEY (ocupaciones_id)
    REFERENCES ocupaciones (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_personas_paises1_idx ON personas (paises_id ASC);
CREATE INDEX fk_personas_parroquias1_idx ON personas (parroquias_id ASC);
CREATE INDEX fk_personas_ocupaciones1_idx ON personas (ocupaciones_id ASC);

-- -----------------------------------------------------
-- Table roles
-- -----------------------------------------------------
DROP TABLE IF EXISTS roles ;
CREATE TABLE IF NOT EXISTS roles (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(45) NOT NULL);

-- -----------------------------------------------------
-- Table usuarios
-- -----------------------------------------------------
DROP TABLE IF EXISTS usuarios ;
CREATE TABLE IF NOT EXISTS usuarios (
  id SERIAL PRIMARY KEY NOT NULL,
  personas_id INT NOT NULL,
  roles_id INT NOT NULL,
  correo VARCHAR(100) NOT NULL,
  contrasena TEXT NOT NULL,
  recuperacion BOOL NULL DEFAULT FALSE,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE,
  CONSTRAINT fk_usuarios_personas1
    FOREIGN KEY (personas_id)
    REFERENCES personas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_usuarios_roles1
    FOREIGN KEY (roles_id)
    REFERENCES roles (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_usuarios_personas1_idx ON usuarios (personas_id ASC);
CREATE INDEX fk_usuarios_roles1_idx ON usuarios (roles_id ASC);

-- -----------------------------------------------------
-- Table lineas_interes
-- -----------------------------------------------------
DROP TABLE IF EXISTS lineas_interes ;
CREATE TABLE IF NOT EXISTS lineas_interes (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE);

-- -----------------------------------------------------
-- Table documentos
-- -----------------------------------------------------
DROP TABLE IF EXISTS documentos ;
CREATE TABLE IF NOT EXISTS documentos (
  id SERIAL PRIMARY KEY NOT NULL,
  codigo VARCHAR(45) NULL DEFAULT NULL,
  nombre TEXT NOT NULL);

-- -----------------------------------------------------
-- Table tipos_fichas
-- -----------------------------------------------------
DROP TABLE IF EXISTS tipos_fichas ;
CREATE TABLE IF NOT EXISTS tipos_fichas (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(45) NULL);

-- -----------------------------------------------------
-- Table fichas
-- -----------------------------------------------------
DROP TABLE IF EXISTS fichas ;
CREATE TABLE IF NOT EXISTS fichas (
  id SERIAL PRIMARY KEY NOT NULL,
  tipos_fichas_id INT NOT NULL,
  documentos_id INT NOT NULL,
  numero VARCHAR(45) NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE,
  CONSTRAINT fk_fichas_tipos_fichas1
    FOREIGN KEY (tipos_fichas_id)
    REFERENCES tipos_fichas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_fichas_documentos1
    FOREIGN KEY (documentos_id)
    REFERENCES documentos (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_fichas_tipos_fichas1_idx ON fichas (tipos_fichas_id ASC);
CREATE INDEX fk_fichas_documentos1_idx ON fichas (documentos_id ASC);

-- -----------------------------------------------------
-- Table material_soporte
-- -----------------------------------------------------
DROP TABLE IF EXISTS material_soporte ;
CREATE TABLE IF NOT EXISTS material_soporte (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(45) NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE);

-- -----------------------------------------------------
-- Table fondos_documentales
-- -----------------------------------------------------
DROP TABLE IF EXISTS fondos_documentales ;
CREATE TABLE IF NOT EXISTS fondos_documentales (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT NULL DEFAULT NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE);

-- -----------------------------------------------------
-- Table colecciones
-- -----------------------------------------------------
DROP TABLE IF EXISTS colecciones ;
CREATE TABLE IF NOT EXISTS colecciones (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT NULL DEFAULT NULL,
  fecha_registro TIMESTAMP NULL DEFAULT NULL,
  fondos_documentales_id INT NOT NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE,
  CONSTRAINT fk_colecciones_fondos_documentales1
    FOREIGN KEY (fondos_documentales_id)
    REFERENCES fondos_documentales (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_colecciones_fondos_documentales1_idx ON colecciones (fondos_documentales_id ASC);

-- -----------------------------------------------------
-- Table archivos
-- -----------------------------------------------------
DROP TABLE IF EXISTS archivos ;
CREATE TABLE IF NOT EXISTS archivos (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(45) NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE);

-- -----------------------------------------------------
-- Table secciones
-- -----------------------------------------------------
DROP TABLE IF EXISTS secciones ;
CREATE TABLE IF NOT EXISTS secciones (
  id SERIAL PRIMARY KEY NOT NULL,
  archivos_id INT NOT NULL,
  nombre VARCHAR(45) NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE,
  CONSTRAINT fk_secciones_archivos1
    FOREIGN KEY (archivos_id)
    REFERENCES archivos (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_secciones_archivos1_idx ON secciones (archivos_id ASC);

-- -----------------------------------------------------
-- Table niveles
-- -----------------------------------------------------
DROP TABLE IF EXISTS niveles ;
CREATE TABLE IF NOT EXISTS niveles (
  id SERIAL PRIMARY KEY NOT NULL,
  secciones_id INT NOT NULL,
  nombre VARCHAR(45) NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE,
  CONSTRAINT fk_niveles_secciones1
    FOREIGN KEY (secciones_id)
    REFERENCES secciones (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_niveles_secciones1_idx ON niveles (secciones_id ASC);

-- -----------------------------------------------------
-- Table contenedores
-- -----------------------------------------------------
DROP TABLE IF EXISTS contenedores ;
CREATE TABLE IF NOT EXISTS contenedores (
  id SERIAL PRIMARY KEY NOT NULL,
  niveles_id INT NOT NULL,
  codigo VARCHAR(45) NULL,
  nombre VARCHAR(45) NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE,
  CONSTRAINT fk_contenedores_niveles1
    FOREIGN KEY (niveles_id)
    REFERENCES niveles (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_contenedores_niveles1_idx ON contenedores (niveles_id ASC);

-- -----------------------------------------------------
-- Table estado_conservacion
-- -----------------------------------------------------
DROP TABLE IF EXISTS estado_conservacion ;
CREATE TABLE IF NOT EXISTS estado_conservacion (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(45) NULL);

-- -----------------------------------------------------
-- Table estado_verificacion
-- -----------------------------------------------------
DROP TABLE IF EXISTS estado_verificacion ;
CREATE TABLE IF NOT EXISTS estado_verificacion (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(45) NULL);

-- -----------------------------------------------------
-- Table tipo_material_soporte
-- -----------------------------------------------------
DROP TABLE IF EXISTS tipo_material_soporte ;
CREATE TABLE IF NOT EXISTS tipo_material_soporte (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(45) NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE);

-- -----------------------------------------------------
-- Table formatos
-- -----------------------------------------------------
DROP TABLE IF EXISTS formatos ;
CREATE TABLE IF NOT EXISTS formatos (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(45) NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE);

-- -----------------------------------------------------
-- Table toponimia
-- -----------------------------------------------------
DROP TABLE IF EXISTS toponimia ;
CREATE TABLE IF NOT EXISTS toponimia (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE);

-- -----------------------------------------------------
-- Table idiomas
-- -----------------------------------------------------
DROP TABLE IF EXISTS idiomas ;
CREATE TABLE IF NOT EXISTS idiomas (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE);

-- -----------------------------------------------------
-- Table materiales_documentos
-- -----------------------------------------------------
DROP TABLE IF EXISTS materiales_documentos ;
CREATE TABLE IF NOT EXISTS materiales_documentos (
  id SERIAL PRIMARY KEY NOT NULL,
  nombre VARCHAR(45) NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE);

-- -----------------------------------------------------
-- Table fichas_tecnicas
-- -----------------------------------------------------
DROP TABLE IF EXISTS fichas_tecnicas ;
CREATE TABLE IF NOT EXISTS fichas_tecnicas (
  id SERIAL PRIMARY KEY NOT NULL,
  fichas_id INT NOT NULL,
  codigo_institucional VARCHAR(100) NULL,
  codigo_patrimonial VARCHAR(100) NULL,
  codigo_digital VARCHAR(100) NULL,
  nombre TEXT NULL,
  nombre_sugerido TEXT NULL,
  numero_extension TEXT NULL DEFAULT NULL,
  estado_conservacion_id INT NOT NULL,
  estado_verificacion_id INT NOT NULL,
  portada TEXT NULL,
  colecciones_id INT NULL DEFAULT NULL,
  asunto_tema TEXT NULL,
  lugar_emision TEXT NULL,
  anios_criticos JSONB NULL,
  palabras_claves JSONB NULL,
  descripcion TEXT NULL,
  transcripcion TEXT NULL,
  contenedores_id INT NULL DEFAULT NULL,
  largo VARCHAR(45) NULL,
  ancho VARCHAR(45) NULL,
  observaciones TEXT NULL,
  usuario_creador INT NOT NULL,
  usuario_revisor INT NULL DEFAULT NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE,
  CONSTRAINT fk_fichas_tecnicas_fichas1
    FOREIGN KEY (fichas_id)
    REFERENCES fichas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_fichas_tecnicas_colecciones1
    FOREIGN KEY (colecciones_id)
    REFERENCES colecciones (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_fichas_tecnicas_contenedores1
    FOREIGN KEY (contenedores_id)
    REFERENCES contenedores (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_fichas_tecnicas_estado_conservacion1
    FOREIGN KEY (estado_conservacion_id)
    REFERENCES estado_conservacion (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_fichas_tecnicas_estado_verificacion1
    FOREIGN KEY (estado_verificacion_id)
    REFERENCES estado_verificacion (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_fichas_tecnicas_usuario_creador1
    FOREIGN KEY (usuario_creador)
    REFERENCES usuarios (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_fichas_tecnicas_usuario_revisor1
    FOREIGN KEY (usuario_revisor)
    REFERENCES usuarios (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
  );
CREATE INDEX fk_fichas_tecnicas_fichas1_idx ON fichas_tecnicas (fichas_id ASC);
CREATE INDEX fk_fichas_tecnicas_colecciones1_idx ON fichas_tecnicas (colecciones_id ASC);
CREATE INDEX fk_fichas_tecnicas_contenedores1_idx ON fichas_tecnicas (contenedores_id ASC);
CREATE INDEX fk_fichas_tecnicas_estado_conservacion1_idx ON fichas_tecnicas (estado_conservacion_id ASC);
CREATE INDEX fk_fichas_tecnicas_estado_verificacion1_idx ON fichas_tecnicas (estado_verificacion_id ASC);
CREATE INDEX fk_fichas_tecnicas_usuario_creador1_idx ON fichas_tecnicas (usuario_creador ASC);
CREATE INDEX fk_fichas_tecnicas_usuario_revisor1x ON fichas_tecnicas (usuario_revisor ASC);

-- -----------------------------------------------------
-- Table movimiento_contenedores
-- -----------------------------------------------------
DROP TABLE IF EXISTS movimiento_contenedores ;
CREATE TABLE IF NOT EXISTS movimiento_contenedores (
  id SERIAL PRIMARY KEY NOT NULL,
  contenedores_id INT NOT NULL,
  niveles_id INT NOT NULL,
  secciones_id INT NOT NULL,
  archivos_id INT NOT NULL,
  descripcion VARCHAR(45) NULL,
  fecha_registro TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_movimiento_contenedores_contenedores1
    FOREIGN KEY (contenedores_id)
    REFERENCES contenedores (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_movimiento_contenedores_niveles1
    FOREIGN KEY (niveles_id)
    REFERENCES niveles (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_movimiento_contenedores_secciones1
    FOREIGN KEY (secciones_id)
    REFERENCES secciones (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_movimiento_contenedores_archivos1
    FOREIGN KEY (archivos_id)
    REFERENCES archivos (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_movimiento_contenedores_contenedores1_idx ON movimiento_contenedores (contenedores_id ASC);
CREATE INDEX fk_movimiento_contenedores_niveles1_idx ON movimiento_contenedores (niveles_id ASC);
CREATE INDEX fk_movimiento_contenedores_secciones1_idx ON movimiento_contenedores (secciones_id ASC);
CREATE INDEX fk_movimiento_contenedores_archivos1_idx ON movimiento_contenedores (archivos_id ASC);


-- -----------------------------------------------------
-- Table toponimia_fichas_tecnicas
-- -----------------------------------------------------
DROP TABLE IF EXISTS toponimia_fichas_tecnicas ;
CREATE TABLE IF NOT EXISTS toponimia_fichas_tecnicas (
  fichas_tecnicas_id INT NOT NULL,
  toponimia_id INT NOT NULL,
  PRIMARY KEY (fichas_tecnicas_id, toponimia_id),
  CONSTRAINT fk_toponimia_fichas_tecnicas_fichas_tecnicas1
    FOREIGN KEY (fichas_tecnicas_id)
    REFERENCES fichas_tecnicas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_toponimia_fichas_tecnicas_toponimia1
    FOREIGN KEY (toponimia_id)
    REFERENCES toponimia (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_toponimia_fichas_tecnicas_fichas_tecnicas_idx ON toponimia_fichas_tecnicas (fichas_tecnicas_id ASC);
CREATE INDEX fk_toponimia_fichas_tecnicas_toponimia_idx ON toponimia_fichas_tecnicas (toponimia_id ASC);

-- -----------------------------------------------------
-- Table personajes_fichas_tecnicas
-- -----------------------------------------------------
DROP TABLE IF EXISTS personajes_fichas_tecnicas ;
CREATE TABLE IF NOT EXISTS personajes_fichas_tecnicas (
  fichas_tecnicas_id INT NOT NULL,
  personas_id INT NOT NULL,
  PRIMARY KEY (fichas_tecnicas_id, personas_id),
  CONSTRAINT fk_personajes_fichas_tecnicas_fichas_tecnicas1
    FOREIGN KEY (fichas_tecnicas_id)
    REFERENCES fichas_tecnicas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_personajes_fichas_tecnicas_personas1
    FOREIGN KEY (personas_id)
    REFERENCES personas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_personajes_fichas_tecnicas_personas_idx ON personajes_fichas_tecnicas (personas_id ASC);
CREATE INDEX fk_personajes_fichas_tecnicas_fichas_tecnicas_idx ON personajes_fichas_tecnicas (fichas_tecnicas_id ASC);

-- -----------------------------------------------------
-- Table generadores_fichas_tecnicas
-- -----------------------------------------------------
DROP TABLE IF EXISTS generadores_fichas_tecnicas ;
CREATE TABLE IF NOT EXISTS generadores_fichas_tecnicas (
  fichas_tecnicas_id INT NOT NULL,
  personas_id INT NOT NULL,
  PRIMARY KEY (fichas_tecnicas_id, personas_id),
  CONSTRAINT fk_generadores_fichas_tecnicas_fichas_tecnicas1
    FOREIGN KEY (fichas_tecnicas_id)
    REFERENCES fichas_tecnicas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_generadores_fichas_tecnicas_personas1
    FOREIGN KEY (personas_id)
    REFERENCES personas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_generadores_fichas_tecnicas_personas_idx ON generadores_fichas_tecnicas (personas_id ASC);
CREATE INDEX fk_generadores_fichas_tecnicas_fichas_tecnicas_idx ON generadores_fichas_tecnicas (fichas_tecnicas_id ASC);


-- -----------------------------------------------------
-- Table formatos_fichas_tecnicas
-- -----------------------------------------------------
DROP TABLE IF EXISTS formatos_fichas_tecnicas ;
CREATE TABLE IF NOT EXISTS formatos_fichas_tecnicas (
  fichas_tecnicas_id INT NOT NULL,
  formatos_id INT NOT NULL,
  PRIMARY KEY (formatos_id, fichas_tecnicas_id),
  CONSTRAINT fk_formatos_fichas_tecnicas_formatos1
    FOREIGN KEY (formatos_id)
    REFERENCES formatos (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_formatos_fichas_tecnicas_fichas_tecnicas1
    FOREIGN KEY (fichas_tecnicas_id)
    REFERENCES fichas_tecnicas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_formatos_fichas_tecnicas_fichas_tecnicas_idx ON formatos_fichas_tecnicas (fichas_tecnicas_id ASC);
CREATE INDEX fk_formatos_fichas_tecnicas_formatos_idx ON formatos_fichas_tecnicas (formatos_id ASC);

-- -----------------------------------------------------
-- Table tipo_material_soporte_fichas_tecnicas
-- -----------------------------------------------------
DROP TABLE IF EXISTS tipo_material_soporte_fichas_tecnicas ;
CREATE TABLE IF NOT EXISTS tipo_material_soporte_fichas_tecnicas (
  fichas_tecnicas_id INT NOT NULL,
  tipo_material_soporte_id INT NOT NULL,
  PRIMARY KEY (tipo_material_soporte_id, fichas_tecnicas_id),
  CONSTRAINT fk_tipo_material_soporte_fichas_tecnicas_tipo_material_soporte1
    FOREIGN KEY (tipo_material_soporte_id)
    REFERENCES tipo_material_soporte (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_tipo_material_soporte_fichas_tecnicas_ficha_tecnica1
    FOREIGN KEY (fichas_tecnicas_id)
    REFERENCES fichas_tecnicas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_tipo_material_soporte_fichas_tecnicas_ficha_tecnica_idx ON tipo_material_soporte_fichas_tecnicas (fichas_tecnicas_id ASC);
CREATE INDEX fk_tipo_material_soporte_fichas_tecnicas_tipo_material_soporte_idx ON tipo_material_soporte_fichas_tecnicas (tipo_material_soporte_id ASC);

-- -----------------------------------------------------
-- Table material_soporte_fichas_tecnicas
-- -----------------------------------------------------
DROP TABLE IF EXISTS material_soporte_fichas_tecnicas ;
CREATE TABLE IF NOT EXISTS material_soporte_fichas_tecnicas (
  fichas_tecnicas_id INT NOT NULL,
  material_soporte_id INT NOT NULL,
  PRIMARY KEY (material_soporte_id, fichas_tecnicas_id),
  CONSTRAINT fk_material_soporte_fichas_tecnicas_material_soporte1
    FOREIGN KEY (material_soporte_id)
    REFERENCES material_soporte (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_material_soporte_fichas_tecnicas_fichas_tecnicas1
    FOREIGN KEY (fichas_tecnicas_id)
    REFERENCES fichas_tecnicas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_material_soporte_fichas_tecnicas_fichas_tecnicas_idx ON material_soporte_fichas_tecnicas (fichas_tecnicas_id ASC);
CREATE INDEX fk_material_soporte_fichas_tecnicas_material_soporte_idx ON material_soporte_fichas_tecnicas (material_soporte_id ASC);


-- -----------------------------------------------------
-- Table materiales_documentos_fichas_tecnicas
-- -----------------------------------------------------
DROP TABLE IF EXISTS materiales_documentos_fichas_tecnicas ;
CREATE TABLE IF NOT EXISTS materiales_documentos_fichas_tecnicas (
  fichas_tecnicas_id INT NOT NULL,
  materiales_documentos_id INT NOT NULL,
  PRIMARY KEY (materiales_documentos_id, fichas_tecnicas_id),
  CONSTRAINT fk_materiales_documentos_fichas_tecnicas_materiales1
    FOREIGN KEY (materiales_documentos_id)
    REFERENCES materiales_documentos (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_materiales_documentos_fichas_tecnicas_documentos1
    FOREIGN KEY (fichas_tecnicas_id)
    REFERENCES fichas_tecnicas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_materiales_documentos_fichas_tecnicas_document_idx ON materiales_documentos_fichas_tecnicas (fichas_tecnicas_id ASC);
CREATE INDEX fk_materiales_documentos_fichas_tecnicas_material_idx ON materiales_documentos_fichas_tecnicas (materiales_documentos_id ASC);

-- -----------------------------------------------------
-- Table idiomas_fichas_tecnicas
-- -----------------------------------------------------
DROP TABLE IF EXISTS idiomas_fichas_tecnicas ;
CREATE TABLE IF NOT EXISTS idiomas_fichas_tecnicas (
  fichas_tecnicas_id INT NOT NULL,
  idiomas_id INT NOT NULL,
  PRIMARY KEY (idiomas_id, fichas_tecnicas_id),
  CONSTRAINT fk_idiomas_fichas_tecnicas_idiomas1
    FOREIGN KEY (idiomas_id)
    REFERENCES idiomas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_idiomas_fichas_tecnicas_fichas_tecnicas1
    FOREIGN KEY (fichas_tecnicas_id)
    REFERENCES fichas_tecnicas (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_idiomas_fichas_tecnicas_fichas_tecnicas1_idx ON idiomas_fichas_tecnicas (fichas_tecnicas_id ASC);
CREATE INDEX fk_idiomas_fichas_tecnicas_idiomas1_idx ON idiomas_fichas_tecnicas (idiomas_id ASC);


-- -----------------------------------------------------
-- Table archivos_documentos
-- -----------------------------------------------------
DROP TABLE IF EXISTS archivos_documentos ;
CREATE TABLE IF NOT EXISTS archivos_documentos (
  documentos_id INT NOT NULL,
  nombre TEXT NOT NULL,
  ruta TEXT NULL DEFAULT NULL,
  descripcion TEXT NULL DEFAULT NULL,
  numeropagina TEXT NULL DEFAULT NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  fecha_actualizacion TIMESTAMP NULL DEFAULT NULL,
  estado BOOL NOT NULL DEFAULT TRUE,
  CONSTRAINT fk_archivos_documentos1
    FOREIGN KEY (documentos_id)
    REFERENCES documentos (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_archivos_documentos1_idx ON archivos_documentos (documentos_id ASC);

-- -----------------------------------------------------
-- Table favoritos
-- -----------------------------------------------------
DROP TABLE IF EXISTS favoritos ;
CREATE TABLE IF NOT EXISTS favoritos (
  usuarios_id INT NOT NULL,
  documentos_id INT NOT NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (usuarios_id, documentos_id),
  CONSTRAINT fk_favoritos_usuarios1
    FOREIGN KEY (usuarios_id)
    REFERENCES usuarios (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_favoritos_documentos1
    FOREIGN KEY (documentos_id)
    REFERENCES documentos (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_favoritos_documentos_idx ON favoritos (documentos_id ASC);
CREATE INDEX fk_favoritos_usuarios1_idx ON favoritos (usuarios_id ASC);

-- -----------------------------------------------------
-- Table usuarios_lineas_interes
-- -----------------------------------------------------
DROP TABLE IF EXISTS usuarios_lineas_interes ;
CREATE TABLE IF NOT EXISTS usuarios_lineas_interes (
  lineas_interes_id INT NOT NULL,
  usuarios_id INT NOT NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NOW(),
  PRIMARY KEY (lineas_interes_id, usuarios_id),
  CONSTRAINT fk_lineas_interes_usuarios_lineas_interes1
    FOREIGN KEY (lineas_interes_id)
    REFERENCES lineas_interes (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_lineas_interes_usuarios_usuarios1
    FOREIGN KEY (usuarios_id)
    REFERENCES usuarios (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_lineas_interes_usuarios_usuarios1_idx ON usuarios_lineas_interes (usuarios_id ASC);
CREATE INDEX fk_lineas_interes_usuarios_lineas_interes1_idx ON usuarios_lineas_interes (lineas_interes_id ASC);

-- -----------------------------------------------------
-- Table lineas_interes_documentos
-- -----------------------------------------------------
DROP TABLE IF EXISTS lineas_interes_documentos ;
CREATE TABLE IF NOT EXISTS lineas_interes_documentos (
  lineas_interes_id INT NOT NULL,
  documentos_id INT NOT NULL,
  fecha_creacion TIMESTAMP NULL DEFAULT NOW(),
  PRIMARY KEY (lineas_interes_id, documentos_id),
  CONSTRAINT fk_lineas_interes_documentos_lineas_interes1
    FOREIGN KEY (lineas_interes_id)
    REFERENCES lineas_interes (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_lineas_interes_documentos_documentos1
    FOREIGN KEY (documentos_id)
    REFERENCES documentos (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_lineas_interes_documentos_documentos_idx ON lineas_interes_documentos (documentos_id ASC);
CREATE INDEX fk_lineas_interes_documentos_lineas_interes1_idx ON lineas_interes_documentos (lineas_interes_id ASC);

-- -----------------------------------------------------
-- Table documentos_vistos
-- -----------------------------------------------------
DROP TABLE IF EXISTS documentos_vistos ;
CREATE TABLE IF NOT EXISTS documentos_vistos (
  id SERIAL PRIMARY KEY NOT NULL,
  documentos_id INT NOT NULL,
  usuarios_id INT NOT NULL,
  fecha TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_documentos_vistos_documentos1
    FOREIGN KEY (documentos_id)
    REFERENCES documentos (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_documentos_vistos_usuarios1
    FOREIGN KEY (usuarios_id)
    REFERENCES usuarios (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
CREATE INDEX fk_documentos_vistos_documentos1_idx ON documentos_vistos (documentos_id ASC);
CREATE INDEX fk_documentos_vistos_usuarios1_idx ON documentos_vistos (usuarios_id ASC);


-- -----------------------------------------------------
-- Table errores
-- -----------------------------------------------------
DROP TABLE IF EXISTS errores ;
CREATE TABLE IF NOT EXISTS errores (
  id SERIAL PRIMARY KEY NOT NULL,
  usuarios_id INT NULL DEFAULT 0,
  codigo_error TEXT NULL DEFAULT NULL,
  mensaje TEXT NULL DEFAULT NULL,
  proceso TEXT NULL DEFAULT NULL,
  accion TEXT NULL DEFAULT NULL,
  fecha TIMESTAMP NULL DEFAULT NOW(),
  CONSTRAINT fk_errores_usuario1
    FOREIGN KEY (usuarios_id)
    REFERENCES usuarios (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);
CREATE INDEX fk_errores_usuarios1_idx ON errores (usuarios_id ASC);

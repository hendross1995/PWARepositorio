<?php
require_once 'db.modelo.php';

Class Documento extends database {

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function MostrarDocumentos(Documento $x){
        try {
            if ($x->rol == 'ADMINISTRADOR') {
                $sql = "SELECT idfichatecnica,numero,nombre,asunto_tema,estado_verificacion,estado FROM vmostrarfichastecnicas;";
                $stm = $this->pdo->prepare($sql);
                $stm->execute();
            }elseif($x->rol == 'DOCUMENTALISTA'){
                $sql = "SELECT idfichatecnica,numero,nombre,asunto_tema,estado_verificacion,estado FROM vmostrarfichastecnicas WHERE idcreador = ?;";
                $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->usuario));
            }
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function VerDetalleDocumento(Documento $x){
        try {
            $sql = "SELECT * FROM vmostrarfichastecnicas WHERE idfichatecnica = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->idfichatecnica));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function CargarLibro(Documento $x){
        try {
            $resultado = [];
            $sql1 = "SELECT * FROM ActualizarDocumentosVistos(?,?) AS resultado";
            $stm1 = $this->pdo->prepare($sql1);
            $stm1->execute(array($x->iddocumento,$x->usuario));
            $resultado[0] = $stm1->fetchAll(PDO::FETCH_OBJ);

            $sql2 = "SELECT codigo,portada,archivos_documentos FROM vmostrarfichastecnicas WHERE iddocumento = ?";
            $stm2 = $this->pdo->prepare($sql2);
            $stm2->execute(array($x->iddocumento));
            $resultado[1] = $stm2->fetchAll(PDO::FETCH_OBJ);
            return $resultado;
            $stm1 = NULL;
            $stm2 = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function ActualizarDocumentos(Documento $x){
        try {
            $sql = "SELECT * FROM ActualizarDocumentos(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) AS resultado";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                                $x->id,
                                $x->numero_documento,
								$x->codigo_institucional,
								$x->codigo_patrimonial,
								$x->codigo_digital,
								$x->nombre,
								$x->nombre_sugerido,
								$x->extension,
                                $x->estado_conservacion,
								$x->portada,
                                $x->coleccion,
                                $x->asunto_tema,
                                $x->lugar_emision,
                                $x->toponimia,
                                $x->generadores,
                                $x->personajes,
                                $x->idiomas,
                                $x->anios_criticos,
                                $x->palabras_claves,
                                $x->descripcion,
                                $x->transcripcion,
                                $x->contenedor,
                                $x->formato,
                                $x->tipo_material,
                                $x->material_soporte,
                                $x->material_documento,
                                $x->largo,
                                $x->ancho,
                                $x->estado_verificacion,
                                $x->extensiones_archivos,
                                $x->ruta,
								$x->observaciones,
								$x->estado,
								$x->accion,
                                $x->usuario
                        ));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
            } catch (Exception $e) {
                die($e->getMessage());
            }
    }

    public function EliminarArchivos(Documento $x){
        try {
            $sql = "SELECT * FROM EliminarArchivos(?,?,?) AS resultado;";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->iddocumento,$x->nombre,$x->usuario));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function VerRutaDocumentos(Documento $x){
        try {
            $sql = "SELECT * FROM VerRutaDocumentos(?,?) AS resultado;";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->iddocumento,$x->accion));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function AgregaPersonajeHistorico(){

    }
}

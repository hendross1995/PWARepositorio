<?php
require_once 'db.modelo.php';

Class Buscador extends database {

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
    public function MostrarDocumentosRelevantes(){
        try {
            $sql = "SELECT 
            (SELECT json_agg(json_build_object('idusuario',X.usuarios_id::TEXT)ORDER BY X.fecha_creacion)
                    FROM favoritos X WHERE X.documentos_id = iddocumento)::JSONB AS usuarios_favoritos,
            * FROM vmostrardocumentosmasvistos WHERE estado IS TRUE AND estado_verificacion = 'APROBADO' ORDER BY nombre LIMIT 5";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function ListarDocumentos(Buscador $x){
        $palabraABuscar = '%'.$x->frase_buscada.'%';
        $resultado = [];
        try {
            $sql1 = "SELECT
            (SELECT json_agg(json_build_object('idusuario',X.usuarios_id::TEXT)ORDER BY X.fecha_creacion)
                    FROM favoritos X WHERE X.documentos_id = iddocumento)::JSONB AS usuarios_favoritos,
                    * FROM vmostrarfichastecnicas WHERE estado IS TRUE AND estado_verificacion = 'APROBADO' AND
                      (traducir(nombre) LIKE traducir('".$palabraABuscar."')
                    OR traducir(nombre_sugerido) LIKE traducir('".$palabraABuscar."')
                    OR traducir(fondo) LIKE traducir('".$palabraABuscar."')
                    OR traducir(coleccion) LIKE traducir('".$palabraABuscar."')
                    OR traducir(asunto_tema) LIKE traducir('".$palabraABuscar."')
                    OR traducir(lugar_emision) LIKE traducir('".$palabraABuscar."')
                    OR traducir(descripcion) LIKE traducir('".$palabraABuscar."')
                    OR traducir(transcripcion) LIKE traducir('".$palabraABuscar."'))
                    {$x->sql} ORDER BY nombre LIMIT ? OFFSET ?";
            
            $sql2 = "SELECT count(iddocumento) FROM vmostrarfichastecnicas WHERE estado IS TRUE AND estado_verificacion = 'APROBADO' AND (traducir(nombre) LIKE traducir('".$palabraABuscar."')
                    OR traducir(nombre_sugerido) LIKE traducir('".$palabraABuscar."')
                    OR traducir(fondo) LIKE traducir('".$palabraABuscar."')
                    OR traducir(coleccion) LIKE traducir('".$palabraABuscar."')
                    OR traducir(asunto_tema) LIKE traducir('".$palabraABuscar."')
                    OR traducir(lugar_emision) LIKE traducir('".$palabraABuscar."')
                    OR traducir(descripcion) LIKE traducir('".$palabraABuscar."')
                    OR traducir(transcripcion) LIKE traducir('".$palabraABuscar."')) {$x->sql}";
            
            $stm = $this->pdo->prepare($sql1);
            $stm->execute(array(
                            $x->itemsPorPage,
                            $x->page
                        ));
           
            $resultado[0] = $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = $this->pdo->prepare($sql2);
            $stm->execute();
            $resultado[1] = $stm->fetchAll(PDO::FETCH_OBJ);
            $resultado[2] =  $sql1;
            
            return $resultado;
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

}

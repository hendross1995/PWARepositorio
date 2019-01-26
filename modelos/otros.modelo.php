<?php
require_once 'db.modelo.php';
Class Otros extends database {
    private $pdo;
    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function CargarInicio(){
      try {
        $resultado = [];
        $sql1 = "SELECT * FROM cargarInicio() AS resultado;";
        $sql2= "
          SELECT iddocumento,
                 codigo,
                 numero,
                 nombre,
                 (SELECT COUNT(documentos_id) FROM documentos_vistos WHERE documentos_id = iddocumento AND
                   fecha::DATE BETWEEN (SELECT DATE_TRUNC('MONTH', CURRENT_DATE)::DATE) AND
                                       (DATE_TRUNC('MONTH', CURRENT_DATE)+'1MONTH'::INTERVAL-'1DAY'::INTERVAL)::DATE) total
                 FROM vmostrarfichastecnicas WHERE iddocumento IN
                  (SELECT documentos_id FROM documentos_vistos 
                     WHERE fecha::DATE BETWEEN (SELECT DATE_TRUNC('MONTH', CURRENT_DATE)::DATE) AND
                           (DATE_TRUNC('MONTH', CURRENT_DATE)+'1MONTH'::INTERVAL-'1DAY'::INTERVAL)::DATE
                    GROUP BY documentos_id ORDER BY documentos_id)
                 ORDER BY total DESC,nombre LIMIT 10";
        $stm = $this->pdo->prepare($sql1);
        $stm->execute();
        $resultado[0] = $stm->fetchAll(PDO::FETCH_OBJ);
        
        $stm1 = $this->pdo->prepare($sql2);
        $stm1->execute();
        $resultado[1] = $stm1->fetchAll(PDO::FETCH_OBJ);
        
        $stm = NULL;
        $stm1 = NULL;
        return $resultado;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
    public function CargarPaises(){
      try {
        $sql = "SELECT * FROM vmostrarpaises";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
        $stm = NULL;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
    public function CargarProvincias(Otros $x){
      try {
        $sql = "SELECT * FROM provincias WHERE paises_id = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array($x->id_pais));
        return $stm->fetchAll(PDO::FETCH_OBJ);
        $stm = NULL;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
    public function CargarCantones(Otros $x){
      try {
        $sql = "SELECT * FROM cantones WHERE provincias_id = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array($x->id_provincia));
        return $stm->fetchAll(PDO::FETCH_OBJ);
        $stm = NULL;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
    public function CargarParroquias(Otros $x){
      try {
        $sql = "SELECT * FROM parroquias WHERE cantones_id = ?";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array($x->id_canton));
        return $stm->fetchAll(PDO::FETCH_OBJ);
        $stm = NULL;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
    public function CargarOcupaciones(){
      try {
        $sql = "SELECT * FROM vmostrarocupaciones";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
        $stm = NULL;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
    public function CargarEstadoCivil(){
      try {
        $sql = "SELECT * FROM vmostrarestadosciviles";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
        $stm = NULL;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
    public function MostrarErrores(){
      try {
        $sql = "SELECT * FROM vmostrarerrores";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
        $stm = NULL;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
    public function CargarRoles(){
      try {
        $sql = "SELECT * FROM vmostrarroles";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
        $stm = NULL;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
    public function CargarEstadosConservacion(){
      try {
          $sql = "SELECT * FROM vmostrarestadosconservacion ORDER BY nombre;";
          $stm = $this->pdo->prepare($sql);
          $stm->execute();
          return $stm->fetchAll(PDO::FETCH_OBJ);
          $stm = NULL;
      } catch (Exception $e) {
          die($e->getMessage());
      }
    }
    public function CargarEstadosVerificacion(){
      try {
          $sql = "SELECT * FROM vmostrarestadosverificacion ORDER BY nombre;";
          $stm = $this->pdo->prepare($sql);
          $stm->execute();
          return $stm->fetchAll(PDO::FETCH_OBJ);
          $stm = NULL;
      } catch (Exception $e) {
          die($e->getMessage());
      }
    }
}

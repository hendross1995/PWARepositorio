<?php
require_once 'db.modelo.php';
Class Coleccion extends database {
    private $pdo;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }    
    }

    public function MostrarColecciones(){
        try {
            $sql = "SELECT * FROM vmostrarcolecciones";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function ActualizarColecciones(Coleccion $x){
        try {
            $sql = "SELECT * FROM ActualizarColecciones(?,?,?,?,?,?,?,?) AS resultado";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                                $x->id,
                                $x->nombre,
                                $x->descripcion,
                                $x->fecharegistro,
                                $x->idfondo,
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
    public function CargarColecciones(Coleccion $x){
        try {
            $sql = "SELECT * FROM vmostrarcolecciones WHERE estado IS TRUE AND idfondo = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->id_fondo));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
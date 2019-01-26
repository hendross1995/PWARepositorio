<?php
require_once 'db.modelo.php';
Class Seccion extends database {
    private $pdo;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }    
    }

    public function MostrarSecciones(){
        try {
            $sql = "SELECT * FROM vmostrarsecciones";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function ActualizarSecciones(Seccion $x){
        try {
            $sql = "SELECT * FROM actualizarsecciones(?,?,?,?,?,?) AS resultado";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                                $x->id,
                                $x->idarchivo,
                                $x->nombre,
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
    public function CargarSecciones(Seccion $x){
        try {
            $sql = "SELECT * FROM vmostrarsecciones WHERE estado IS TRUE AND idarchivo = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->idarchivo));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
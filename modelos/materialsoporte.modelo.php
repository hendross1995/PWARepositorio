<?php
require_once 'db.modelo.php';
Class MaterialSoporte extends database {
    private $pdo;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }    
    }
    public function MostrarMaterialesSoporte(){
        try {
            $sql = "SELECT * FROM vmostrarmaterialessoporte";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function ActualizarMaterialesSoporte(MaterialSoporte $x){
        try {
            $sql = "SELECT * FROM actualizarmaterialessoporte(?,?,?,?,?) AS resultado";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                                $x->id,
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
    public function CargarMaterialesSoporte(){
        try {
            $sql = "SELECT * FROM vmostrarmaterialessoporte WHERE estado IS TRUE";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

}
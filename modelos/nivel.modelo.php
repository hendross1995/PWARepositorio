<?php
require_once 'db.modelo.php';
Class Nivel extends database {
    private $pdo;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }    
    }

    public function MostrarNiveles(){
        try {
            $sql = "SELECT * FROM vmostrarniveles";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function ActualizarNiveles(Nivel $x){
        try {
            $sql = "SELECT * FROM actualizarNiveles(?,?,?,?,?,?) AS resultado";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                                $x->id,
                                $x->idseccion,
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
    public function CargarNiveles(Nivel $x){
        try {
            $sql = "SELECT * FROM vmostrarniveles WHERE estado IS TRUE AND idseccion = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->idseccion));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
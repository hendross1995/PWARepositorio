<?php
require_once 'db.modelo.php';
Class Contenedor extends database {

    private $pdo;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function MostrarContenedores(){
        try {
            $sql = "SELECT * FROM vmostrarcontenedores";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function ActualizarContenedores(Contenedor $x){
        try {
            $sql = "SELECT * FROM actualizarcontenedores(?,?,?,?,?,?,?) AS resultado";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                                $x->id,
                                $x->idnivel,
                                $x->codigo,
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
    public function CargarContenedores(Contenedor $x){
        try {
            $sql = "SELECT * FROM vmostrarcontenedores WHERE estado IS TRUE AND idnivel = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->idnivel));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function MoverContenedores(Contenedor $x){
        try {
            $sql = "SELECT * FROM ActualizarMovimientoContenedor(?,?,?,?,?) AS resultado";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                                $x->idcontenedor,
                                $x->idnivel,
                                $x->idseccion,
                                $x->idarchivo,
                                $x->descripcion
                        ));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
            } catch (Exception $e) {
                die($e->getMessage());
            }
    }
}

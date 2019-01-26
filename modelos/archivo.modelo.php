<?php
require_once 'db.modelo.php';
Class Archivo extends database {
    private $pdo;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }    
    }
    public function MostrarArchivos(){
        try {
            $sql = "SELECT * FROM vmostrararchivos";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function ActualizarArchivos(Archivo $x){
        try {
            $sql = "SELECT * FROM actualizararchivos(?,?,?,?,?) AS resultado";
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
    public function CargarArchivos(){
        try {
            $sql = "SELECT * FROM vmostrararchivos WHERE estado IS TRUE";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

}
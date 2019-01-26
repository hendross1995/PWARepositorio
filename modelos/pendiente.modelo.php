<?php
require_once 'db.modelo.php';

Class Pendiente extends database {

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function MostrarPendientes(){
        try {
            $sql = "SELECT * FROM vmostrarfichastecnicas WHERE estado_verificacion = 'REVISIÓN'";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function VerDetallePendiente(Pendiente $x){
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

    public function AprobarDocumentos(Pendiente $x){
        try {
            $sql = "SELECT * FROM AprobarDocumentos(?,?,?) AS resultado";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                                $x->revisor,
                                $x->idfichatecnica,
                                $x->usuario
                        ));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
            } catch (Exception $e) {
                die($e->getMessage());
            }
    }
    public function ReprobarDocumentos(Pendiente $x){
        try {
            $sql = "SELECT * FROM ReprobarDocumentos(?,?,?,?) AS resultado";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                                $x->revisor,
                                $x->idfichatecnica,
                                $x->observaciones,
                                $x->usuario
                        ));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
            } catch (Exception $e) {
                die($e->getMessage());
            }
    }
}

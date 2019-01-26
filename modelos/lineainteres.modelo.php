<?php
require_once 'db.modelo.php';
Class LineaInteres extends database {
    private $pdo;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function MostrarLineasInteres(){
        try {
            $sql = "SELECT * FROM vmostrarlineasinteres";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function ActualizarLineasInteres(LineaInteres $x){
        try {
            $sql = "SELECT * FROM ActualizarLineasInteres(?,?,?,?,?) AS resultado";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                                $x->id,
                                $x->nombre,
                                $x->estado,
                                $x->accion,
                                $x->ususario
                        ));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
            } catch (Exception $e) {
                die($e->getMessage());
            }
    }

    public function CargarLineasInteres(){
        try {
            $sql = "SELECT * FROM vmostrarlineasinteres WHERE estado IS TRUE";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

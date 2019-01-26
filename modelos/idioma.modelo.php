<?php
require_once 'db.modelo.php';
Class Idioma extends database {
    private $pdo;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function MostrarIdiomas(){
        try {
            $sql = "SELECT * FROM vmostraridiomas WHERE nombre != 'NO DEFINIDO'";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function ActualizarIdiomas(Idioma $x){
        try {
            $sql = "SELECT * FROM ActualizarIdiomas(?,?,?,?,?) AS resultado";
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
    public function CargarIdiomas(){
        try {
            $sql = "SELECT * FROM vmostraridiomas WHERE estado IS TRUE";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

}

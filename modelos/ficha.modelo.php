<?php
require_once 'db.modelo.php';
Class Ficha extends database {
    private $pdo;
    private $id_ficha;
    private $tipo_ficha_id;
    private $numero_ficha;
    private $estado;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function MostrarFichas(){
        try {
            $sql = "SELECT * FROM vmostrarfichas";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function ActualizarFichas(Ficha $x){
        try {
            $sql = "SELECT * FROM actualizarfichas(?,?,?,?,?) AS resultado";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array(
                                $x->id_ficha,
                                $x->tipo_ficha_id,
                                $x->numero_ficha,
                                $x->estado,
                                $x->accion
                        ));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
            } catch (Exception $e) {
                die($e->getMessage());
            }
    }
    public function MostrarTiposFichas(){
        try {
            $sql = "SELECT * FROM vmostrartiposfichas ORDER BY nombre;";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

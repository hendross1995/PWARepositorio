<?php
require_once 'db.modelo.php';
Class inicioAdmin extends database {
    private $pdo;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }catch (Exception $e) {
            die($e->getMessage());
        }    
    }
    public function MostrarFondos(){
        try {
            $sql = "SELECT count(*) FROM vmostrarusuarios";

            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetch(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
}
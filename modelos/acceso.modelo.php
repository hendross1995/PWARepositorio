<?php
require_once 'db.modelo.php';
Class Acceso extends database {
    private $pdo;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }
        catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function IniciarSesion(Acceso $x){
        try {
            $sql = "SELECT * FROM ValidarLogin(?,?) AS resultado";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->usuario,$x->contrasena));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
           die($e->getMessage());
        }
    }
    public function VerEstadoUsuario(Acceso $x){
        try{
            $stm = $this->pdo->prepare("SELECT * FROM VerEstadoUsuario(?) AS resultado;");
            $stm->execute(array($x->idusuario));
            return $stm->fetchAll(PDO::FETCH_OBJ);
        }catch(Exception $e){
            die($e->getMessage());
        }
    }
    public function ActualizarContrasena(Acceso $x){
        try {
            $sql = "SELECT * FROM ActualizarAcceso(?,?,?,?,?) AS resultado;";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->correo,$x->contrasena1,$x->contrasena2,$x->contrasena3,$x->accion));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        }catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function MostrarOcupaciones(){
        try {
            $sql = "SELECT * FROM vmostrarocupaciones ORDER BY nombre;";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function VerExisteCorreo(Acceso $x){
         try{
            $stm = $this->pdo->prepare("SELECT * FROM VerExisteCorreo(?) AS resultado;");
            $stm->execute(array($x->correo));
            return $stm->fetchAll(PDO::FETCH_OBJ);
        }catch(Exception $e){
            die($e->getMessage());
        }
    }
}
?>

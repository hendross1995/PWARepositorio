<?php
require_once 'db.modelo.php';
Class Inicio extends database {
    private $pdo;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        } 
        catch (Exception $e) {
            die($e->getMessage());
        }    
    }

    /* public function IniciarSesion(Acceso $x){
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
    public function IniciarPeriodo(Acceso $x){
        try {
            $sql = "SELECT * FROM IniciarPeriodo(?,?) AS resultado";
                
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->usuario,$x->periodo));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;   
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function IniciarRolPeriodo(Acceso $x){
        try {
            $sql = "SELECT * FROM IniciarRolPeriodo(?,?,?) AS resultado;";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->rol,$x->usuario,$x->periodo));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;   
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function IniciarEstudiante(Acceso $x){
        try {
            $sql = "SELECT * FROM IniciarEstudiante(?,?) AS resultado;";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->rol,$x->usuario));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;   
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function VerEstadoUsuario(Acceso $x){
        try{
            $stm = $this->pdo->prepare("SELECT * FROM VerEstadoUsuario(?,?,?,?)");
            $stm->execute(array($x->usuario,$x->rol,$x->periodo,$x->institucion));
            return $stm->fetchAll(PDO::FETCH_OBJ);
        }catch(Exception $e){
            die($e->getMessage());
        }
    }
    public function VerExisteUsuario(Acceso $x){
        try { 
            $sql = "SELECT institucion FROM vmostrarpersonas WHERE correo2 = ? LIMIT 1;";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->correo));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        }catch (Exception $e) { 
            die($e->getMessage());
        }
    }
    public function VerParametrosCorreos(Acceso $x){
        try { 
            $sql = "SELECT * FROM vmostrarmisinstitucionesdetalles WHERE id = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->institucion));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        }catch (Exception $e) { 
            die($e->getMessage());
        }
    }
    public function ActualizarContrasena(Acceso $x){
        try { 
            $sql = "SELECT * FROM ActualizarAcceso(?,?,?,?,?,?) AS resultado;";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->correo,$x->contrasena1,$x->contrasena2,$x->contrasena3,$x->accion,$x->institucion));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        }catch (Exception $e) { 
            die($e->getMessage());
        }
    } */
}
?>
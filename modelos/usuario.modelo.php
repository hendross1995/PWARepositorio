<?php
require_once 'db.modelo.php';
Class Usuario extends database {
    private $pdo;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }
        catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function MostrarUsuarios(){
        try {
            $sql = "SELECT * FROM vmostrarusuarios where rol != 'LECTOR' AND cedula != '1313375477' AND cedula != '1316075926'";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function MostrarUsuariosLectores(){
        try {
            $sql = "SELECT * FROM vmostrarusuarios where rol = 'LECTOR'";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
    
    public function MostrarPerfil(Usuario $x){
        try {
            $sql = "SELECT * FROM vmostrarusuarios where idusuario = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute(array($x->idUsuario));
            return $stm->fetchAll(PDO::FETCH_OBJ);
            $stm = NULL;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    
    
    public function ActualizarUsuarios(Usuario $x){
      try {
          $sql = "SELECT * FROM ActualizarUsuarios(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) AS resultado";
          $stm = $this->pdo->prepare($sql);
          $stm->execute(array(
                              $x->id,
                              $x->cedula,
                              $x->apellidos,
                              $x->nombres,
                              $x->sexo,
                              $x->convencional,
                              $x->celular,
                              $x->parroquias_id,
                              $x->direccion,
                              $x->roles_id,
                              $x->correo,
                              $x->contrasena,
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
    
    
    public function ActualizarPerfil(Usuario $x){
      try {
          $sql = "SELECT * FROM ActualizarPerfil(?,?,?,?,?,?,?,?,?,?,?) AS resultado";
          $stm = $this->pdo->prepare($sql);
          $stm->execute(array(
                              $x->cedula, 
                              $x->apellidos, 
                              $x->nombres,
                              $x->sexo,
                              $x->convencional,
                              $x->celular,
                              $x->parroquias_id,
                              $x->direccion,
                              $x->correo,
                              $x->usuario,
                              $x->accion
                      ));
          return $stm->fetchAll(PDO::FETCH_OBJ);
          $stm = NULL;
          } catch (Exception $e) {
              die($e->getMessage());
          }
      }
    
    public function RegistrarUsuarios(Usuario $x){
            try {
                $sql = "SELECT * FROM RegistrarUsuario(?,?,?,?,?,?,?,?) AS resultado";
                $stm = $this->pdo->prepare($sql);
                $stm->execute(array(
                                    $x->apellidos,
                                    $x->nombres,
                                    $x->sexo,
                                    $x->ocupacion,
                                    $x->organizacion,
                                    $x->telefono,
                                    $x->correo,
                                    $x->password
                            ));
                return $stm->fetchAll(PDO::FETCH_OBJ);
                $stm = NULL;
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }

  public function CargarUsuarios(){
      try {
          $sql = "SELECT * FROM vmostrarusuarios WHERE estado IS TRUE ORDER BY nombre";
          $stm = $this->pdo->prepare($sql);
          $stm->execute();
          return $stm->fetchAll(PDO::FETCH_OBJ);
          $stm = NULL;
      } catch (Exception $e) {
          die($e->getMessage());
      }
  }

}

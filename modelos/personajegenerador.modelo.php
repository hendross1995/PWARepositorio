<?php
require_once 'db.modelo.php';
Class PersonajeGenerador extends database{
  private $pdo;
  public function __CONSTRUCT(){
      try {
          $this->pdo = DataBase::Conectar();
      }
      catch (Exception $e) {
          die($e->getMessage());
      }
  }
  public function MostrarPersonajesGeneradores(){
      try {
          $sql = "SELECT * FROM vmostrarpersonajesgeneradores WHERE nombres != 'NO DEFINIDO'";
          $stm = $this->pdo->prepare($sql);
          $stm->execute();
          return $stm->fetchAll(PDO::FETCH_OBJ);
          $stm = NULL;
      } catch (Exception $e) {
          die($e->getMessage());
      }
  }
  public function ActualizarPersonajesGeneradores(PersonajeGenerador $x){
    try {
        $sql = "SELECT * FROM ActualizarPersonajesGeneradores(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) AS resultado";
        $stm = $this->pdo->prepare($sql);
        $stm->execute(array(
                            $x->id,
                            $x->cedula,
                            $x->apellidos,
                            $x->nombres,
                            $x->lugar_nacimiento,
                            $x->fecha_nacimiento,
                            $x->fecha_disfuncion,
                            $x->sexo,
                            $x->foto_carnet,
                            $x->nacionalidad,
                            $x->organizacion,
                            $x->alias,
                            $x->descripcion,
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
    public function CargarPersonajesGeneradores(){
      try {
        $sql = "SELECT * FROM vmostrarpersonajesgeneradores WHERE estado IS TRUE";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
        $stm = NULL;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }

    public function CargarPersonajes(){
      try {
        $sql = "SELECT * FROM vmostrarpersonajesgeneradores WHERE idpersona IN (SELECT personas_id FROM personajes_fichas_tecnicas);";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
        $stm = NULL;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }

    public function CargarGeneradores(){
      try {
        $sql = "SELECT * FROM vmostrarpersonajesgeneradores WHERE idpersona IN (SELECT personas_id FROM generadores_fichas_tecnicas);";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
        $stm = NULL;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
}
?>

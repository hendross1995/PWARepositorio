<?php
require_once 'db.modelo.php';
Class Favorito extends database {
    private $pdo;

    public function __CONSTRUCT(){
        try {
            $this->pdo = DataBase::Conectar();
        }
        catch (Exception $e) {
            die($e->getMessage());
        }
    }
    public function MostrarFavoritos(Favorito $x){
      try {
        $sql = "SELECT * FROM vmostrarfavoritos WHERE {$x->usuario};";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
        $stm = NULL;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
    public function TotalFavoritos(){
      try {
        $sql = "SELECT iddocumento,numero,nombre,
       (SELECT COUNT(*) FROM favoritos WHERE documentos_id = iddocumento) AS total_favoritos
          FROM vmostrarfichastecnicas WHERE iddocumento IN (SELECT documentos_id FROM favoritos)";
        $stm = $this->pdo->prepare($sql);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
        $stm = NULL;
      } catch (Exception $e) {
        die($e->getMessage());
      }
    }
    public function ActualizarFavoritos(Favorito $x){
      try {
          $sql = "SELECT * FROM ActualizarFavoritos(?,?) AS resultado";
          $stm = $this->pdo->prepare($sql);
          $stm->execute(array(
                              $x->usuario,
                              $x->iddocumento
                      ));
          return $stm->fetchAll(PDO::FETCH_OBJ);
          $stm = NULL;
          } catch (Exception $e) {
              die($e->getMessage());
          }
      }
}

<?php
require_once('../db.php');
class Seguidor_Seguido
{
    private $db;
    private $connection;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
        $this->connection->set_charset("utf8mb4");
    }

    public function sugerencias(){
        $user_id = $_SESSION['user_id'];
        $queryThree = "SELECT name, imagen_perfil FROM perfiles WHERE usuario_id != $user_id ORDER BY RAND() LIMIT 4";
        $resultThree = $this->connection->query($queryThree);
        $usuariosHTML = '';
        if ($resultThree) {
            if ($resultThree->num_rows > 0) {
                while ($row = $resultThree->fetch_assoc()) {
                    $nombre = $row['name'];
                    $imagen = $row['imagen_perfil'];
                    $usuariosHTML .= '<div class="sidenav__users-follow">';
                    $usuariosHTML .= '<div class="sidenav__info-user">';
                    $usuariosHTML .= '<a href="#"><img src="' . $imagen . '" alt="Imagen de perfil"></a>';
                    $usuariosHTML .= '<a href="#">' . $nombre . '</a>';
                    $usuariosHTML .= '</div>';
                    $usuariosHTML .= '<a class="follow-link" href="#">Seguir</a>';
                    $usuariosHTML .= '</div>';
                }
            } else {
                echo "No se encontraron usuarios.";
            }
        }
        return $usuariosHTML;
    }
    
}
// $perfil = new Perfil($connection);
?>
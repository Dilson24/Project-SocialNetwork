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
        $queryThree = "SELECT usuario_id, name, imagen_perfil FROM perfiles WHERE usuario_id != $user_id ORDER BY RAND() LIMIT 4";
        $resultThree = $this->connection->query($queryThree);
        $usuariosHTML = '';
        if ($resultThree) {
            if ($resultThree->num_rows > 0) {
                while ($row = $resultThree->fetch_assoc()) {
                    $usuario_id = $row['usuario_id'];
                    $nombre = $row['name'];
                    $imagen = $row['imagen_perfil'];
                    $usuariosHTML .= '<div class="sidenav__users-follow">';
                    $usuariosHTML .= '<div class="sidenav__info-user">';
                    $usuariosHTML .= '<a href="#"><img src="' . $imagen . '" alt="Imagen de perfil"></a>';
                    $usuariosHTML .= '<a href="#">' . $nombre . '</a>';
                    $usuariosHTML .= '</div>';
                    $usuariosHTML .= '<a class="follow-button" data-usuario-id="' . $usuario_id .'" href="#"><span>Seguir</span></a>';
                    $usuariosHTML .= '</div>';
                }
            } else {
                echo "No se encontraron usuarios.";
            }
        }
        return $usuariosHTML;
    }

    public function seguirUsuario() {
        $success = false;
        $message = '';
    
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        $user_id = $_SESSION['user_id'];
    
        if (isset($_POST['usuario_id'])) {
            $seguido_id = $_POST['usuario_id'];
            $querySeguidores = "INSERT INTO seguidores (seguidor_id, usuario_id) VALUES ($user_id, $seguido_id)";
            $querySeguidos = "INSERT INTO seguidos (seguidor_id, usuario_id) VALUES ($user_id, $seguido_id)";
    
            $resultadoSeguidores = $this->connection->query($querySeguidores);
            $resultadoSeguidos = $this->connection->query($querySeguidos);
    
            if ($resultadoSeguidores && $resultadoSeguidos) {
                // Ambas inserciones fueron exitosas
                $success = true;
                $message = 'Seguido con éxito';
            } else {
                // Manejar errores si alguna inserción falla
                $message = 'Error al seguir al usuario';
            }
        } else {
            // Manejar el caso en el que no se enviaron los datos esperados
            $message = 'Faltan datos necesarios';
        }
    
        echo json_encode(
            array(
                'success' => $success,
                'message' => $message
            )
        );
        die();
    }
    
    
    public function dejarDeSeguirUsuario($seguidor_id, $usuario_id) {
        $query = "DELETE FROM seguidores WHERE seguidor_id = $seguidor_id AND usuario_id = $usuario_id";
        return $this->connection->query($query);
    }

    public function obtenerSeguidores($usuario_id) {
        $query = "SELECT seguidor_id FROM seguidores WHERE usuario_id = $usuario_id";
        $result = $this->connection->query($query);
        // Procesa y devuelve la lista de seguidores.
    }

    public function obtenerSeguidos($usuario_id) {
        $query = "SELECT usuario_id FROM seguidores WHERE seguidor_id = $usuario_id";
        $result = $this->connection->query($query);
    }  
    
}
$Seguidor_Seguido = new Seguidor_Seguido();
if (isset($_GET['Seguidor_Seguido'])) {
    $Seguidor_Seguido->seguirUsuario();
}
?>
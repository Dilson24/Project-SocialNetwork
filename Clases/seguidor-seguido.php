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

    public function sugerencias()
    {
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
                    $usuariosHTML .= '<a class="follow-button" data-usuario-id="' . $usuario_id . '" href="#"><span>Seguir</span></a>';
                    $usuariosHTML .= '</div>';
                }
            } else {
                echo "No se encontraron usuarios.";
            }
        }
        return $usuariosHTML;
    }

    public function seguirUsuario()
    {
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


    public function dejarDeSeguirUsuario()
    {
        $success = false;
        $message = '';
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $user_id = $_SESSION['user_id'];

        if (isset($_POST['usuario_id'])) {
            $unfollow = (int) $_POST['usuario_id'];
            var_dump($user_id, $unfollow);
            $querySeguidores = "DELETE FROM seguidores WHERE seguidor_id = $user_id AND usuario_id = $unfollow ";
            $querySeguidos = "DELETE FROM seguidos WHERE usuario_id = $unfollow AND seguidor_id = $user_id ";

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
        die();
    }

    public function obtenerSeguidores()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $user_id = $_SESSION['user_id'];

        // Consulta para obtener la lista de seguidores
        $query = "SELECT seguidor_id FROM seguidores WHERE usuario_id = $user_id";
        $result = $this->connection->query($query);

        $seguidores = array(); // Un arreglo para almacenar los datos de los seguidores

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $seguidor_id = $row['seguidor_id'];
                // Consulta para obtener los datos del perfil del seguidor
                $perfil_query = "SELECT name, imagen_perfil FROM perfiles WHERE usuario_id = $seguidor_id";
                $perfil_result = $this->connection->query($perfil_query);

                if ($perfil_result && $perfil_result->num_rows > 0) {
                    $perfil_data = $perfil_result->fetch_assoc();
                    $nombre = $perfil_data['name'];
                    $imagen_perfil = $perfil_data['imagen_perfil'];
                    // Agrega los datos del seguidor al arreglo de seguidores
                    $seguidores[] = array(
                        'nombre' => $nombre,
                        'imagen_perfil' => $imagen_perfil,
                        'seguidor_id' => $seguidor_id
                    );
                }
            }
        }

        return $seguidores;
    }

    public function obtenerSeguidos()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $user_id = $_SESSION['user_id'];

        // Consulta para obtener la lista de seguidos
        $query = "SELECT usuario_id FROM seguidos WHERE seguidor_id = $user_id";

        $result = $this->connection->query($query);

        $seguidos = array(); // Un arreglo para almacenar los datos de los seguidos

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $seguido_id = $row['usuario_id'];

                // Consulta para obtener los datos del perfil del seguido
                $perfil_query = "SELECT name, imagen_perfil FROM perfiles WHERE usuario_id = $seguido_id";
                $perfil_result = $this->connection->query($perfil_query);

                if ($perfil_result && $perfil_result->num_rows > 0) {
                    $perfil_data = $perfil_result->fetch_assoc();
                    $nombre = $perfil_data['name'];
                    $imagen_perfil = $perfil_data['imagen_perfil'];
                    // Agrega los datos del seguido al arreglo de seguidos
                    $seguidos[] = array(
                        'nombre' => $nombre,
                        'imagen_perfil' => $imagen_perfil,
                        'usuario_id' => $seguido_id
                    );
                }
            }
        }

        return $seguidos;
    }


    public function obtenerSeguidoresTotal()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION['user_id'];
        $query = "SELECT COUNT(*) AS total_seguidores FROM seguidores WHERE usuario_id = $user_id";
        $result = $this->connection->query($query);
        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $totalFollowers = $row['total_seguidores'];
                return $totalFollowers;
            } else {
                // Manejar el error
                echo "Error: La consulta falló";
            }
        }
    }

    public function obtenerSeguidosTotal()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION['user_id'];
        $query = "SELECT COUNT(*) AS total_seguidos FROM seguidos WHERE seguidor_id = $user_id";
        $result = $this->connection->query($query);
        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $totalFollowing = $row['total_seguidos'];
                return $totalFollowing;
            } else {
                // Manejar el error
                echo "Error: La consulta falló";
            }
        }
    }

}
$Seguidor_Seguido = new Seguidor_Seguido();
if (isset($_GET['Seguidor_Seguido'])) {
    $Seguidor_Seguido->seguirUsuario();
}
if (isset($_GET['Unfollow'])) {
    $Seguidor_Seguido->dejarDeSeguirUsuario();
}
?>
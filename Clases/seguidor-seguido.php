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
        // Consulta SQL para seleccionar usuarios sugeridos que el usuario actual no sigue
        $queryThree = "SELECT p.usuario_id, p.name, p.imagen_perfil
        FROM perfiles p
        WHERE p.usuario_id != $user_id 
        AND NOT EXISTS (
        SELECT 1
        FROM seguidores s
        WHERE s.seguidor_id = $user_id
        AND s.usuario_id = p.usuario_id
        )
        ORDER BY RAND()
        LIMIT 4";
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
                    $usuariosHTML .= '<a href="../Vistas/perfiles.php?id=' . $usuario_id . '"><img src="' . $imagen . '" alt="Imagen de perfil"></a>';
                    $usuariosHTML .= '<a href="../Vistas/perfiles.php?id=' . $usuario_id . '">' . $nombre . '</a>';
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
    public function seguirUsuario()
    {
        $response = array();
        $response['success'] = false;
        $response['message'] = '';

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
                $response['success'] = true;
                $response['message'] = 'Seguido con éxito';
                // Obtener el nuevo total de seguidos
                $totalSeguidos = $this->obtenerSeguidosTotal();
                $response['total_seguidos'] = $totalSeguidos;
            } else {
                // Manejar errores si alguna inserción falla
                $response = 'Error al seguir al usuario' . $this->connection->error;
                ;
            }
        } else {
            // Manejar el caso en el que no se enviaron los datos esperados
            $response['message'] = 'Faltan datos necesarios';
        }

        // Enviar la respuesta como JSON
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }
    public function dejarDeSeguirUsuario()
    {
        $response = array();
        $response['success'] = false;
        $response['message'] = '';

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $user_id = $_SESSION['user_id'];

        if (isset($_POST['usuario_id'])) {
            $unfollow = (int) $_POST['usuario_id'];
            $querySeguidores = "DELETE FROM seguidores WHERE seguidor_id = $user_id AND usuario_id = $unfollow ";
            $querySeguidos = "DELETE FROM seguidos WHERE usuario_id = $unfollow AND seguidor_id = $user_id ";

            $resultadoSeguidores = $this->connection->query($querySeguidores);
            $resultadoSeguidos = $this->connection->query($querySeguidos);

            if ($resultadoSeguidores && $resultadoSeguidos) {
                // Ambas eliminaciones fueron exitosas
                $response['success'] = true;
                $response['message'] = 'Dejado de seguir con éxito';
                // Obtener el nuevo total de seguidos
                $totalSeguidos = $this->obtenerSeguidosTotal();
                $response['total_seguidos'] = $totalSeguidos;
            } else {
                // Manejar errores si alguna eliminación falla
                $response['message'] = 'Error al dejar de seguir al usuario';
            }
        } else {
            // Manejar el caso en el que no se enviaron los datos esperados
            $response['message'] = 'Faltan datos necesarios';
        }

        // Enviar la respuesta como JSON
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }
    
    public function obtenerSeguidoresTotalPublic($profile_id)
    {
        $query = "SELECT COUNT(*) AS total_seguidores FROM seguidores WHERE usuario_id = $profile_id";
        $result = $this->connection->query($query);
        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $totalFollowers_p = $row['total_seguidores'];
                return $totalFollowers_p;
            } else {
                // Manejar el error
                echo "Error: La consulta falló";
            }
        }
    }
    public function obtenerSeguidosTotalPublic($profile_id)
    {
        $query = "SELECT COUNT(*) AS total_seguidos FROM seguidos WHERE seguidor_id =  $profile_id";
        $result = $this->connection->query($query);
        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $totalFollowing_p = $row['total_seguidos'];
                return $totalFollowing_p;
            } else {
                // Manejar el error
                echo "Error: La consulta falló";
            }
        }
    }

    public function obtenerSeguidoresPublico($profile_id)
    {
        // Consulta para obtener la lista de seguidores
        $query = "SELECT seguidor_id FROM seguidores WHERE usuario_id = $profile_id";
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
    public function obtenerSeguidosPublic($profile_id)
    {
        // Consulta para obtener la lista de seguidos
        $query = "SELECT usuario_id FROM seguidos WHERE seguidor_id = $profile_id";

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

    public function sigueUsuario($usuarioSesion, $usuarioSeguido) {
        // Consulta para verificar si $usuarioSesion sigue a $usuarioSeguido
        $query = "SELECT COUNT(*) AS sigue FROM seguidos WHERE seguidor_id = $usuarioSesion AND usuario_id = $usuarioSeguido";
        $result = $this->connection->query($query);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['sigue'] > 0;
        }
    
        return false;
    }
    
}
$Seguidor_Seguido = new Seguidor_Seguido();
if (isset($_GET['Follow'])) {
    $Seguidor_Seguido->seguirUsuario();
}
if (isset($_GET['Unfollow'])) {
    $Seguidor_Seguido->dejarDeSeguirUsuario();
}
?>
<?php
// Incluir el archivo que contiene la lógica de la base de datos y la clase Database
require_once('../db.php');
// Definir la clase 'Seguidor_Seguido'
class Seguidor_Seguido
{
    private $db;
    private $connection;
    // Constructor de la clase
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
        $this->connection->set_charset("utf8mb4");
    }

    // Método para obtener sugerencias de usuarios que el usuario actual no sigue.
    public function sugerencias()
    {
        // Obtener el ID del usuario actual desde la sesión
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
        // Ejecutar la consulta en la base de datos
        $resultThree = $this->connection->query($queryThree);
        // Variable para almacenar el HTML de usuarios sugeridos
        $usuariosHTML = '';
        // Verificar si la consulta fue exitosa
        if ($resultThree) {
            // Verificar si se encontraron usuarios sugeridos
            if ($resultThree->num_rows > 0) {
                // Iterar sobre los resultados y construir el HTML
                while ($row = $resultThree->fetch_assoc()) {
                    $usuario_id = $row['usuario_id'];
                    $nombre = $row['name'];
                    $imagen = $row['imagen_perfil'];
                    // Construir la estructura HTML para cada usuario sugerido
                    $usuariosHTML .= '<div class="sidenav__users-follow">';
                    $usuariosHTML .= '<div class="sidenav__info-user">';
                    $usuariosHTML .= '<a href="../Vistas/perfiles.php?id=' . $usuario_id . '"><img src="' . $imagen . '" alt="Imagen de perfil"></a>';
                    $usuariosHTML .= '<a href="../Vistas/perfiles.php?id=' . $usuario_id . '">' . $nombre . '</a>';
                    $usuariosHTML .= '</div>';
                    $usuariosHTML .= '<a class="follow-button" data-usuario-id="' . $usuario_id . '" href="#"><span>Seguir</span></a>';
                    $usuariosHTML .= '</div>';
                }
            } else {
                // Mensaje si no se encontraron usuarios sugeridos
                echo "No se encontraron usuarios.";
            }
        }
        // Devolver el HTML de usuarios sugeridos
        return $usuariosHTML;
    }
    // Método para obtener sugerencias de usuarios en un formato específico para un popup.
    public function sugerenciasPopup()
    {
        // Obtener el ID del usuario actual desde la sesión
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
        LIMIT 10";
        // Ejecutar la consulta en la base de datos
        $resultThree = $this->connection->query($queryThree);
        // Variable para almacenar el HTML de usuarios sugeridos
        $usuariosHTML = '';
        // Verificar si la consulta fue exitosa
        if ($resultThree) {
            // Verificar si se encontraron usuarios sugeridos
            if ($resultThree->num_rows > 0) {
                // Iterar sobre los resultados y construir el HTML
                while ($row = $resultThree->fetch_assoc()) {
                    $usuario_id = $row['usuario_id'];
                    $nombre = $row['name'];
                    $imagen = $row['imagen_perfil'];
                    // Construir la estructura HTML para cada usuario sugerido en el formato del popup
                    $usuariosHTML .= '<div class="show-users" id="' . $usuario_id . '">';
                    $usuariosHTML .= '<div class="show-users_info">';
                    $usuariosHTML .= '<a class="show-users_profilImg" href="../Vistas/perfiles.php?id=' . $usuario_id . '"><img src="' . $imagen . '" alt="Imagen de perfil"></a>';
                    $usuariosHTML .= '<a class="show-users_profile" href="../Vistas/perfiles.php?id=' . $usuario_id . '">' . $nombre . '</a>';
                    $usuariosHTML .= '</div>';
                    $usuariosHTML .= '<button data-id="' . $usuario_id . '" class="btnFollow">Seguir</button>';
                    $usuariosHTML .= '</div>';
                }
            } else {
                // Mensaje si no se encontraron usuarios sugeridos
                echo "No se encontraron usuarios.";
            }
        }
        // Devolver el HTML de usuarios sugeridos
        return $usuariosHTML;
    }
    //Método para obtener el total de seguidores de un usuario.
    public function obtenerSeguidoresTotal()
    {
        // Iniciar la sesión si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Obtener el ID del usuario actual desde la sesión
        $user_id = $_SESSION['user_id'];
        // Consulta SQL para contar el total de seguidores del usario
        $query = "SELECT COUNT(*) AS total_seguidores FROM seguidores WHERE usuario_id = $user_id";
        // Ejecutar la consulta en la base de datos
        $result = $this->connection->query($query);
        // Verificar si la consulta fue exitosa
        if ($result) {
            // Verificar si se encontraron resultados
            if ($result->num_rows > 0) {
                // Obtener el resultado de la consulta
                $row = $result->fetch_assoc();
                // Extraer el total de seguidores del resultado
                $totalFollowers = $row['total_seguidores'];
                // Devolver el total de seguidores
                return $totalFollowers;
            } else {
                // Manejar el error
                echo "Error: La consulta falló";
            }
        }
    }
    //Método para obtener el total de seguidos de un usuario.
    public function obtenerSeguidosTotal()
    {
        // Iniciar la sesión si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Obtener el ID del usuario actual desde la sesión
        $user_id = $_SESSION['user_id'];
        // Consulta SQL para contar el total de seguidos por el usuario
        $query = "SELECT COUNT(*) AS total_seguidos FROM seguidos WHERE seguidor_id = $user_id";
        // Ejecutar la consulta en la base de datos
        $result = $this->connection->query($query);
        // Verificar si la consulta fue exitosa
        if ($result) {
            // Verificar si se encontraron resultados
            if ($result->num_rows > 0) {
                // Obtener el resultado de la consulta
                $row = $result->fetch_assoc();
                // Extraer el total de seguidos del resultado
                $totalFollowing = $row['total_seguidos'];
                // Devolver el total de seguidores
                return $totalFollowing;
            } else {
                // Manejar el error
                echo "Error: La consulta falló";
            }
        }
    }
    // Método para obtener la lista de seguidores para el usuario actual.
    public function obtenerSeguidores()
    {
        // Verifica si la sesión está activa, y si no, la inicia.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Obtiene el ID de usuario de la sesión actual.
        $user_id = $_SESSION['user_id'];
        // Consulta SQL para obtener la lista de seguidores del usuario.
        $query = "SELECT seguidor_id FROM seguidores WHERE usuario_id = $user_id";
        $result = $this->connection->query($query);
        // Arreglo para almacenar los datos de los seguidores.
        $seguidores = array();
        // Verifica si la consulta fue exitosa.
        if ($result) {
            // Itera sobre los resultados de la consulta.
            while ($row = $result->fetch_assoc()) {
                $seguidor_id = $row['seguidor_id'];
                // Consulta SQL para obtener los datos del perfil del seguidor.
                $perfil_query = "SELECT name, imagen_perfil FROM perfiles WHERE usuario_id = $seguidor_id";
                $perfil_result = $this->connection->query($perfil_query);
                // Verifica si la consulta de perfil fue exitosa y tiene al menos un resultado.
                if ($perfil_result && $perfil_result->num_rows > 0) {
                    $perfil_data = $perfil_result->fetch_assoc();
                    $nombre = $perfil_data['name'];
                    $imagen_perfil = $perfil_data['imagen_perfil'];
                    // Agrega los datos del seguidor al arreglo de seguidores.
                    $seguidores[] = array(
                        'nombre' => $nombre,
                        'imagen_perfil' => $imagen_perfil,
                        'seguidor_id' => $seguidor_id
                    );
                }
            }
        }
        // Retorna el arreglo de seguidores.
        return $seguidores;
    }
    // Método para obtener la lista de usuarios seguidos por el usuario actual.
    public function obtenerSeguidos()
    {
        // Verifica si la sesión está activa, y si no, la inicia.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Obtiene el ID de usuario de la sesión actual.
        $user_id = $_SESSION['user_id'];
        // Consulta SQL para obtener la lista de usuarios seguidos por el usuario actual.
        $query = "SELECT usuario_id FROM seguidos WHERE seguidor_id = $user_id";
        $result = $this->connection->query($query);
        // Arreglo para almacenar los datos de los usuarios seguidos.
        $seguidos = array();
        // Verifica si la consulta fue exitosa.
        if ($result) {
            // Itera sobre los resultados de la consulta.
            while ($row = $result->fetch_assoc()) {
                $seguido_id = $row['usuario_id'];
                // Consulta SQL para obtener los datos del perfil del usuario seguido.
                $perfil_query = "SELECT name, imagen_perfil FROM perfiles WHERE usuario_id = $seguido_id";
                $perfil_result = $this->connection->query($perfil_query);
                // Verifica si la consulta de perfil fue exitosa y tiene al menos un resultado.
                if ($perfil_result && $perfil_result->num_rows > 0) {
                    $perfil_data = $perfil_result->fetch_assoc();
                    $nombre = $perfil_data['name'];
                    $imagen_perfil = $perfil_data['imagen_perfil'];
                    // Agrega los datos del usuario seguido al arreglo de seguidos.
                    $seguidos[] = array(
                        'nombre' => $nombre,
                        'imagen_perfil' => $imagen_perfil,
                        'usuario_id' => $seguido_id
                    );
                }
            }
        }
        // Retorna el arreglo de usuarios seguidos.
        return $seguidos;
    }
    // Método qué permite al usuario actual seguir a otro usuario.
    public function seguirUsuario()
    {
        // Arreglo de respuesta con valores iniciales.
        $response = array();
        $response['success'] = false;
        $response['message'] = '';
        // Verifica si la sesión está activa, y si no, la inicia.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Obtiene el ID de usuario de la sesión actual.
        $user_id = $_SESSION['user_id'];
        // Verifica si se proporcionó el ID del usuario a seguir a través de POST.
        if (isset($_POST['usuario_id'])) {
            // Obtiene el ID del usuario a seguir.
            $seguido_id = $_POST['usuario_id'];
            // Consultas SQL para insertar registros en las tablas 'seguidores' y 'seguidos'.
            $querySeguidores = "INSERT INTO seguidores (seguidor_id, usuario_id) VALUES ($user_id, $seguido_id)";
            $querySeguidos = "INSERT INTO seguidos (seguidor_id, usuario_id) VALUES ($user_id, $seguido_id)";
            // Ejecuta las consultas de inserción.
            $resultadoSeguidores = $this->connection->query($querySeguidores);
            $resultadoSeguidos = $this->connection->query($querySeguidos);
            // Verifica el éxito de ambas inserciones.
            if ($resultadoSeguidores && $resultadoSeguidos) {
                // Ambas inserciones fueron exitosas.
                $response['success'] = true;
                $response['message'] = 'Seguido con éxito';
                // Obtiene el nuevo total de usuarios seguidos.
                $totalSeguidos = $this->obtenerSeguidosTotal();
                $response['total_seguidos'] = $totalSeguidos;
            } else {
                // Maneja errores si alguna inserción falla.
                $response['message'] = 'Error al seguir al usuario' . $this->connection->error;
            }
        } else {
            // Maneja el caso en el que no se enviaron los datos esperados.
            $response['message'] = 'Faltan datos necesarios';
        }
        // Envia la respuesta como JSON.
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }
    // Método qué permite al usuario actual dejar de seguir a otro usuario.
    public function dejarDeSeguirUsuario()
    {
        // Arreglo de respuesta con valores iniciales.
        $response = array();
        $response['success'] = false;
        $response['message'] = '';
        // Verifica si la sesión está activa, y si no, la inicia.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Obtiene el ID de usuario de la sesión actual.
        $user_id = $_SESSION['user_id'];
        // Verifica si se proporcionó el ID del usuario a dejar de seguir a través de POST.
        if (isset($_POST['usuario_id'])) {
            // Obtiene el ID del usuario a dejar de seguir.
            $unfollow = (int) $_POST['usuario_id'];
            // Consultas SQL para eliminar registros de las tablas 'seguidores' y 'seguidos'.
            $querySeguidores = "DELETE FROM seguidores WHERE seguidor_id = $user_id AND usuario_id = $unfollow ";
            $querySeguidos = "DELETE FROM seguidos WHERE usuario_id = $unfollow AND seguidor_id = $user_id ";
            // Ejecuta las consultas de eliminación.
            $resultadoSeguidores = $this->connection->query($querySeguidores);
            $resultadoSeguidos = $this->connection->query($querySeguidos);
            // Verifica el éxito de ambas eliminaciones.
            if ($resultadoSeguidores && $resultadoSeguidos) {
                // Ambas eliminaciones fueron exitosas.
                $response['success'] = true;
                $response['message'] = 'Dejado de seguir con éxito';
                // Obtiene el nuevo total de usuarios seguidos.
                $totalSeguidos = $this->obtenerSeguidosTotal();
                $response['total_seguidos'] = $totalSeguidos;
            } else {
                // Maneja errores si alguna eliminación falla.
                $response['message'] = 'Error al dejar de seguir al usuario';
            }
        } else {
            // Maneja el caso en el que no se enviaron los datos esperados.
            $response['message'] = 'Faltan datos necesarios';
        }
        // Envia la respuesta como JSON.
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }
    // Método para obtener el total de seguidores de un perfil público identificado por su ID.
    public function obtenerSeguidoresTotalPublic($profile_id)
    {
        // Consulta SQL para obtener el total de seguidores del perfil público.
        $query = "SELECT COUNT(*) AS total_seguidores FROM seguidores WHERE usuario_id = $profile_id";
        $result = $this->connection->query($query);
        // Verifica si la consulta fue exitosa.
        if ($result) {
            // Verifica si hay al menos un resultado.
            if ($result->num_rows > 0) {
                // Obtiene el total de seguidores del perfil público.
                $row = $result->fetch_assoc();
                $totalFollowers_p = $row['total_seguidores'];
                return $totalFollowers_p;
            } else {
                // Maneja el caso en el que la consulta no devuelve resultados.
                echo "Error: La consulta no devolvió resultados";
            }
        } else {
            // Maneja el caso en el que la consulta no fue exitosa.
            echo "Error: La consulta falló";
        }
    }
    // Método para obtener el total de usuarios seguidos por un perfil público identificado por su ID.
    public function obtenerSeguidosTotalPublic($profile_id)
    {
        // Consulta SQL para obtener el total de usuarios seguidos por el perfil público.
        $query = "SELECT COUNT(*) AS total_seguidos FROM seguidos WHERE seguidor_id =  $profile_id";
        $result = $this->connection->query($query);
        // Verifica si la consulta fue exitosa.
        if ($result) {
            // Verifica si hay al menos un resultado.
            if ($result->num_rows > 0) {
                // Obtiene el total de usuarios seguidos por el perfil público.
                $row = $result->fetch_assoc();
                $totalFollowing_p = $row['total_seguidos'];
                return $totalFollowing_p;
            } else {
                // Maneja el caso en el que la consulta no devuelve resultados.
                echo "Error: La consulta no devolvió resultados";
            }
        } else {
            // Maneja el caso en el que la consulta no fue exitosa.
            echo "Error: La consulta falló";
        }
    }
    // Metodo para obtener la lista de seguidores de un perfil público identificado por su ID.
    public function obtenerSeguidoresPublico($profile_id)
    {
        // Consulta SQL para obtener la lista de seguidores del perfil público.
        $query = "SELECT seguidor_id FROM seguidores WHERE usuario_id = $profile_id";
        $result = $this->connection->query($query);
        $seguidores = array(); // Un arreglo para almacenar los datos de los seguidores
        // Verifica si la consulta fue exitosa.
        if ($result) {
            // Itera sobre los resultados de la consulta.
            while ($row = $result->fetch_assoc()) {
                $seguidor_id = $row['seguidor_id'];
                // Consulta SQL para obtener los datos del perfil del seguidor.
                $perfil_query = "SELECT name, imagen_perfil FROM perfiles WHERE usuario_id = $seguidor_id";
                $perfil_result = $this->connection->query($perfil_query);
                // Verifica si la consulta de perfil fue exitosa y tiene al menos un resultado.
                if ($perfil_result && $perfil_result->num_rows > 0) {
                    $perfil_data = $perfil_result->fetch_assoc();
                    $nombre = $perfil_data['name'];
                    $imagen_perfil = $perfil_data['imagen_perfil'];
                    // Agrega los datos del seguidor al arreglo de seguidores.
                    $seguidores[] = array(
                        'nombre' => $nombre,
                        'imagen_perfil' => $imagen_perfil,
                        'seguidor_id' => $seguidor_id
                    );
                }
            }
        }
        // Retorna el arreglo de seguidores.
        return $seguidores;
    }
    // Método para obtener la lista de usuarios seguidos por un perfil público identificado por su ID.
    public function obtenerSeguidosPublico($profile_id)
    {
        // Consulta SQL para obtener la lista de usuarios seguidos por el perfil público.
        $query = "SELECT usuario_id FROM seguidos WHERE seguidor_id = $profile_id";
        $result = $this->connection->query($query);
        $seguidos = array(); // Un arreglo para almacenar los datos de los usuarios seguidos.
        // Verifica si la consulta fue exitosa.
        if ($result) {
            // Itera sobre los resultados de la consulta.
            while ($row = $result->fetch_assoc()) {
                $seguido_id = $row['usuario_id'];
                // Consulta SQL para obtener los datos del perfil del usuario seguido.
                $perfil_query = "SELECT name, imagen_perfil FROM perfiles WHERE usuario_id = $seguido_id";
                $perfil_result = $this->connection->query($perfil_query);
                // Verifica si la consulta de perfil fue exitosa y tiene al menos un resultado.
                if ($perfil_result && $perfil_result->num_rows > 0) {
                    $perfil_data = $perfil_result->fetch_assoc();
                    $nombre = $perfil_data['name'];
                    $imagen_perfil = $perfil_data['imagen_perfil'];
                    // Agrega los datos del usuario seguido al arreglo de seguidos.
                    $seguidos[] = array(
                        'nombre' => $nombre,
                        'imagen_perfil' => $imagen_perfil,
                        'usuario_id' => $seguido_id
                    );
                }
            }
        }
        // Retorna el arreglo de usuarios seguidos.
        return $seguidos;
    }
    // Método para verificar sí el usuario de sesión sigue a otro
    public function sigueUsuario($usuarioSesion, $usuarioSeguido)
    {
        // Consulta SQL para verificar si $usuarioSesion sigue a $usuarioSeguido.
        $query = "SELECT COUNT(*) AS sigue FROM seguidos WHERE seguidor_id = $usuarioSesion AND usuario_id = $usuarioSeguido";
        $result = $this->connection->query($query);
            // Verifica si la consulta fue exitosa y si hay al menos un resultado.
        if ($result && $result->num_rows > 0) {
            // Obtiene el resultado de la consulta y devuelve true si $usuarioSesion sigue a $usuarioSeguido.
            $row = $result->fetch_assoc();
            return $row['sigue'] > 0;
        }
            // Retorna false en caso de error o si no hay resultados.
        return false;
    }
}
// Crea una instancia de la clase 'Seguidor_Seguido'
$Seguidor_Seguido = new Seguidor_Seguido();
// Verificar si se esta siguiendo a un usuario
if (isset($_GET['Follow'])) {
    $Seguidor_Seguido->seguirUsuario();
}
// Verificar si se esta dejandio de seguir a un usuario
if (isset($_GET['Unfollow'])) {
    $Seguidor_Seguido->dejarDeSeguirUsuario();
}
?>
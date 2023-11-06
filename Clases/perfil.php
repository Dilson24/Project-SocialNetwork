<?php
require_once('../db.php');
class Perfil
{
    private $db;
    private $connection;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
        $this->connection->set_charset("utf8mb4");
    }

    public function obtenerDatosUsuario()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $user_id = $_SESSION['user_id'];

        $query = "SELECT name, imagen_perfil FROM perfiles WHERE usuario_id = $user_id";
        $result = $this->connection->query($query);

        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_name = $row['name'];
                $user_image = $row['imagen_perfil'];
            } else {
                $user_name = "Usuario Desconocido";
                $user_image = "../Img/User-Profile.png"; // Ruta predeterminada
            }
        } else {
            $user_name = "Error en la consulta";
            $user_image = "../Img/User-Profile.png"; // Ruta predeterminada
        }

        return ['name' => $user_name, 'imagen_perfil' => $user_image];
    }


    public function obtenerTotalPublicaciones()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION['user_id'];

        $query = "SELECT COUNT(*) AS numero_publicaciones FROM publicaciones WHERE usuario_id = $user_id";
        $result = $this->connection->query($query);
        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $numero_publicaciones = $row['numero_publicaciones'];
                return $numero_publicaciones;
            } else {
                // Manejar el error
                echo "Error: La consulta falló";
            }
        }
    }

}
?>
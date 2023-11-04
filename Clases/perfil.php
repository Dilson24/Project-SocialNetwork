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

    public function obtenerNombre()
    {
        // Asegúrate de que la sesión esté iniciada antes de intentar acceder a $_SESSION
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $user_id = $_SESSION['user_id'];
        $query = "SELECT name FROM perfiles WHERE usuario_id = $user_id";
        $result = $this->connection->query($query);

        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_name = $row['name'];
                return $user_name;
            } else {
                return "Usuario Desconocido";
            }
        } else {
            return "Error en la consulta";
        }
    }
    public function obtenerImagen()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION['user_id'];
        $queryTwo = "SELECT imagen_perfil FROM perfiles WHERE usuario_id = $user_id";
        $resultTwo = $this->connection->query($queryTwo);
        if ($resultTwo) { // Comprueba si la consulta se ejecutó correctamente
            if ($resultTwo->num_rows > 0) {
                $rowTwo = $resultTwo->fetch_assoc();
                $user_image = $rowTwo['imagen_perfil'];
                return $user_image;
            } else {
                // Manejar el caso en que no se encuentre la imagen del perfil
                $user_image = "../Img/User-Profile.png"; // Puedes asignar una ruta predeterminada aquí
            }
        }
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
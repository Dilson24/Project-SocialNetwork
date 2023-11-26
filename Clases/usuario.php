<?php
// Incluir el archivo que contiene la lógica de la base de datos y la clase Database
require_once('../db.php');
// Incluir la librería para JWT
require_once('../vendor/firebase/php-jwt/src/JWT.php');
use \Firebase\JWT\JWT;
// Definir la clase 'Usuario'
class Usuario
{
    private $db;
    private $connection;
    // Constructor de la clase
    public function __construct()
    {
        // Obtener una instancia de la clase Database
        $this->db = Database::getInstance();
        // Obtener la conexión a la base de datos
        $this->connection = $this->db->getConnection();
        // Establecer la codificación de caracteres en UTF-8
        $this->connection->set_charset("utf8mb4");
    }
    // Método para registrar un nuevo usuario
    public function registrar()
    {
        // Verificar si la solicitud es de tipo POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener datos del formulario de registro
            $email = $_POST['email'];
            $password = $_POST['password'];
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Paso 1: Insertar email, contraseña y estado en la tabla de 'usuario'
            $query = "INSERT INTO usuario (email, password, state) VALUES (?,?,'Activo')";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ss", $email, $hashedPassword);
            // Paso 2: Insertar otros datos en la tabla de 'perfiles'
            if ($stmt->execute()) {
                $userId = $stmt->insert_id;
                $name = $_POST['name'];
                $lastName = $_POST['lastName'];
                $dateOfBirth = $_POST['dateOfBirth'];
                $country = $_POST['country'];
                $city = $_POST['city'];
                $nameFormatted = ucfirst(strtolower($name));
                $lastNameFormatted = ucfirst(strtolower($lastName));
                $dateOfBirthFormatted = date('Y-m-d', strtotime(str_replace('/', '-', $dateOfBirth)));
                $defaultProfileImage = '../Img/User-Profile.png';
                $query = "INSERT INTO perfiles (usuario_id, name, last_name, date_of_birth, country, city, imagen_perfil) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->connection->prepare($query);
                $stmt->bind_param("issssss", $userId, $nameFormatted, $lastNameFormatted, $dateOfBirthFormatted, $country, $city, $defaultProfileImage);
                if ($stmt->execute()) {
                    // La inserción en la tabla 'perfiles' fue exitosa
                    // Iniciar sesión y redirigir al usuario
                    session_start();
                    $_SESSION['name'] = $nameFormatted;
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['profileImage'] = $defaultProfileImage;
                    header('Location: ../Vistas/inicio.php');
                    exit();
                } else {
                    // Manejar el error en la inserción en la tabla 'perfiles'
                    echo "<script>alert('Error: Algo falló. Inténtelo de nuevo.');";
                    echo "window.location = '../Vistas/Registro.php';</script>";
                }
            } else {
                // Manejar el error en la inserción en la tabla 'usuario'
                echo "<script>alert('Error: Algo falló. Inténtelo de nuevo.');";
                echo "window.location = '../Vistas/Registro.php';</script>";
            }
        }
    }
    // Método para generar un token JWT
    public function generarTokenJWT($user_id, $email)
    {
        $secret_key = 'Project_socialnetwork';
        $token_data = array(
            "user_id" => $user_id,
            "email" => $email
            // Puedes agregar más datos personalizados si es necesario
        );
        $token = \Firebase\JWT\JWT::encode($token_data, $secret_key, 'HS256');
        return $token;
    }
    // Método para iniciar sesión
    public function iniciar_sesion()
    {
        // Iniciar sesión
        session_start();
        $db = Database::getInstance();
        $connection = $db->getConnection();
        // Verificar si la solicitud es de tipo POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener credenciales del formulario de inicio de sesión
            $email = $_POST['Email'];
            $password = $_POST['password'];
            // Verificar las credenciales en la tabla de 'usuario' con el estado 'Activo'
            $query = "SELECT usuario_id, email, password, state FROM usuario WHERE email = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                // Usuario encontrado
                $row = $result->fetch_assoc();
                if ($row['state'] === 'Activo') {
                    // El usuario está activo
                    $storedPassword = $row['password'];
                    if (password_verify($password, $storedPassword)) {
                        // Contraseña correcta
                        $_SESSION['user_id'] = $row['usuario_id'];
                        // Generar el token JWT usando la función separada
                        $token = $this->generarTokenJWT($row['usuario_id'], $row['email']);
                        // Guardar el token en una cookie
                        setcookie('token', $token, time() + 3600, '/');
                        header('Location: ../Vistas/inicio.php'); // Redirigir al perfil del usuario
                        exit();
                    } else {
                        // Contraseña incorrecta
                        echo "<script>alert('Contraseña incorrecta. Inténtalo de nuevo.');";
                        echo "window.location = '../Vistas/inicio-sesion.php';</script>";
                    }
                } else {
                    // Usuario no está activo
                    echo "<script>alert('Usuario inactivo. Envía un correo a adminExample@RSN.com solicitando tu reactivación.');";
                    echo "window.location = '../Vistas/inicio-sesion.php';</script>";
                }
            } else {
                // Usuario no encontrado
                echo "<script>alert('Usuario no encontrado. Regístrate si no tienes una cuenta.');";
                echo "window.location = '../Vistas/inicio-sesion.php';</script>";
            }
        }
    }
    // Método para cerrar sesión
    public function logout()
    {
        session_start();
        session_destroy();
        session_unset();
        header("Location: ../index.php");
    }
}
// Crear una instancia de la clase 'Usuario'
$usuario = new Usuario();
// Verificar si se está registrando un nuevo usuario o iniciando sesión
if (isset($_GET['register'])) {
    $usuario->registrar();
} else {
    $usuario->iniciar_sesion();
}
// Verificar si se está cerrando sesión
if (isset($_GET['logout'])) {
    $usuario->logout();
}
?>
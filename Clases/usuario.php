<?php
require_once('../db.php');
require_once('../vendor/firebase/php-jwt/src/JWT.php');
use \Firebase\JWT\JWT;

class Usuario
{
    private $db;
    private $connection;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
        $this->connection->set_charset("utf8mb4");
    }

    public function registrar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Paso 1: Insertar email,contraseña y el estado en la tabla de `usuario`
            $query = "INSERT INTO usuario (email, password, state) VALUES (?,?,'Activo')";
            $stmt = $this->connection->prepare($query); // Cambiado de $connection a $this->connection
            $stmt->bind_param("ss", $email, $hashedPassword);

            if ($stmt->execute()) {
                // La inserción en la tabla `usuario` fue exitosa
                // Obtener el ID del usuario recién insertado
                $userId = $stmt->insert_id;
                // Paso 2: Insertar los demás datos en la tabla de `perfiles`
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
                $stmt = $this->connection->prepare($query); // Cambiado de $connection a $this->connection
                $stmt->bind_param("issssss", $userId, $nameFormatted, $lastNameFormatted, $dateOfBirthFormatted, $country, $city, $defaultProfileImage);
                if ($stmt->execute()) {
                    // La inserción en la tabla `perfiles` fue exitosa
                    session_start();
                    $_SESSION['name'] = $nameFormatted;
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['profileImage'] = $defaultProfileImage;
                    header('Location: ../Vistas/inicio.php');
                    exit();
                } else {
                    echo "<script>alert('Error: Algo falló. Inténtelo de nuevo.');";
                    echo "window.location = '../Vistas/Registro.php';</script>";
                    // Manejar el error en la inserción en la tabla `perfiles`
                }
            } else {
                echo "<script>alert('Error: Algo falló. Inténtelo de nuevo.');";
                echo "window.location = '../Vistas/Registro.php';</script>";
                // Manejar el error en la inserción en la tabla `usuario`
            }
        }
    }
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

    public function iniciar_sesion()
    {
        session_start();
        $db = Database::getInstance();
        $connection = $db->getConnection();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['Email'];
            $password = $_POST['password'];
            // Verificar las credenciales en la tabla de 'usuario'
            $query = "SELECT usuario_id, email, password FROM usuario WHERE email = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                // El usuario con el correo electrónico proporcionado fue encontrado
                $row = $result->fetch_assoc();
                $storedPassword = $row['password'];

                if (password_verify($password, $storedPassword)) {
                    // La contraseña es correcta
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
                // Usuario no encontrado
                echo "<script>alert('Usuario no encontrado. Regístrate si no tienes una cuenta.');";
                echo "window.location = '../Vistas/inicio-sesion.php';</script>";
            }
        }
    }
    public function logout()
    {
        session_start();
        session_destroy();
        session_unset();
        header("Location: ../index.php");
    }
}

$usuario = new Usuario();

if (isset($_GET['register'])) {
    $usuario->registrar();
} else {
    $usuario->iniciar_sesion();
}
if (isset($_GET['logout'])) {
    $usuario->logout();
}
?>
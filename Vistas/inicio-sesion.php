<?php
session_start();
require_once('../db.php');
require_once('../vendor/firebase/php-jwt/src/JWT.php');
use \Firebase\JWT\JWT;

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
            // Crear un token JWT
            $secret_key='Project_socialnetwork';
            $token_data = array(
                "user_id" => $row['usuario_id'],
                "email" =>$row['email']
                //se pueden agregar más datos personalizados
            );
            $token = JWT::encode($token_data,$secret_key,'HS256');
            //Guardar token en una cookie
            setcookie('token', $token, time()+3600,'/');
            header('Location: ../Vistas/inicio.php'); // Redirigir al perfil del usuario
            exit();
        } else {
            // Contraseña incorrecta
            $error_message = "Contraseña incorrecta. Inténtalo de nuevo.";
        }
    } else {
        // Usuario no encontrado
        $error_message = "Usuario no encontrado. Regístrate si no tienes una cuenta.";
    }
}
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Dilson Alexander Cruz Nivia">
    <link rel="stylesheet" href="../Css/singIn.css" type="text/css">
    <title>Sing In</title>
</head>

<body>
    <div class="login-card">
        <div class="card-header">
            <div class="log">Inicio de sesión</div>
        </div>
        <form method="post" action="inicio-sesion.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input required="required" name="Email" id="email" type="email">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input required="required" name="password" id="password" type="password">
            </div>
            <div class="form-group">
                <input value="Iniciar Sesión" type="submit">
            </div>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
        <?php
        if (isset($error_message)) {
            echo '<p class="error-message">' . $error_message . '</p>';
        }
        ?>
    </div>

</body>

</html>

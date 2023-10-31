<?php
require_once('../db.php');
require_once('../vendor/firebase/php-jwt/src/JWT.php');
use \Firebase\JWT\JWT;

$db = Database::getInstance();
$connection = $db->getConnection();
$connection->set_charset("utf8mb4");
session_start();

if (!isset($_SESSION['user_id'])) {
    //Vertificar la cokie con el token
    if (isset($_COOKIE['token'])) {
        $token = $_COOKIE['token'];
        $secret_key = 'Project_socialnetwork';

        try {
            $decode = JWT::decode($token . $secret_key, array('HS256'));
            $_SESSION['user_id'] = $decode->user_id;
        } catch (Exception $e) {
            //Error de autentificación
            header('Location: inicio-sesion.php');
            exit();
        }
    } else {
        // Si no hay token en la cookie, redirige a la página de inicio de sesión
        header('Location: inicio-sesion.php');
        exit();
    }
    header('Location: inicio-sesion.php');
    exit();
}
$user_id = $_SESSION['user_id'];
/*consultas*/
$query = "SELECT name FROM perfiles WHERE usuario_id = $user_id";
$queryTwo = "SELECT imagen_perfil FROM perfiles WHERE usuario_id = $user_id";
$queryThree = "SELECT name, imagen_perfil FROM perfiles ORDER BY RAND() LIMIT 4";
//condicional para la primera consulta
$result = $connection->query($query);
if ($result) { // Comprueba si la consulta se ejecutó correctamente
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_name = $row['name'];
    } else {
        // Manejar el caso en que no se encuentre el nombre del usuario
        $user_name = "Usuario Desconocido";
    }
}
// condicional segunda consulta
$resultTwo = $connection->query($queryTwo);
if ($resultTwo) { // Comprueba si la consulta se ejecutó correctamente
    if ($resultTwo->num_rows > 0) {
        $rowTwo = $resultTwo->fetch_assoc();
        $user_image = $rowTwo['imagen_perfil'];
    } else {
        // Manejar el caso en que no se encuentre la imagen del perfil
        $user_image = "../Img/User-Profile.png"; // Puedes asignar una ruta predeterminada aquí
    }
}
// condicional tercera consulta
$resultThree = $connection->query($queryThree);
if ($resultThree) {
    if ($resultThree->num_rows > 0) {
        $usuariosHTML = '';
        while ($row = $resultThree->fetch_assoc()) { // Cambio aquí
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
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../Css/inicio.css">
    <title>Profile</title>
</head>

<body>
    <div class="grid-container">
        <header class="header">
            <div class="header__slogan">
                <h2>Conectando el mundo, un amigo a la vez</h2>
            </div>
        </header>
        <aside class="sidenav sidenav-rigth">
            <div class="sidenav__user-profile">
                <a href="../Vistas/prefil.php"><img src="<?php echo $user_image; ?>" alt="Img profile"></a>
                <a href="../Vistas/prefil.php">
                    <?php echo $user_name; ?>
                </a>
            </div>
            <div class="sidenav__users-profiles">
                <div class="sidenav__text">
                    <span>Descubre nuevas conexiones</span>
                    <a href="#">Ver todo</a>
                </div>
                <div class="sidenav__follow">
                    <?php echo $usuariosHTML; ?>
                </div>
            </div>
            <div class="contact-us">
                <span class="brad">
                    &copy; 2023 Dilson Alexander Cruz Nivia | Todos los
                    derechos reservados.
                </span>
            </div>
        </aside>
        <aside class="sidenav sidenav-left">
            <div class="sidenav__logo">
                <img src="../Img/logo.svg" alt="img logo">
            </div>
            <ul class="sidenav__list">
                <a href="#">
                    <li class="sidenav__list-item"><i class="fa-solid fa-house"></i>Inicio</li>
                </a>
                <a href="#">
                    <li class="sidenav__list-item"><i class="fa-solid fa-heart"></i>Notificaciones</li>
                </a>
                <a href="#">
                    <li class="sidenav__list-item"><i class="fa-solid fa-square-plus"></i>Crear</li>
                </a>
                <a href="../Vistas/prefil.php">
                    <li class="sidenav__list-item"><img src="<?php echo $user_image; ?>" alt="img profile">Perfil</li>
                </a>
                <a href="#" id="logoutButton">
                    <li class="sidenav__list-item"><i class="fa-solid fa-right-from-bracket"></i>Cerrar sesión</li>
                </a>
            </ul>
        </aside>

        <main class="main">
            <div class="main-header">
                <div class="main-header__create">
                    <div class="create__profile-user">
                        <a href="../Vistas/prefil.php">
                            <div>
                                <img src="<?php echo $user_image; ?>" alt="img profile">
                            </div>
                        </a>
                    </div>
                    <div class="create__new-content">
                        <span>¿Algo que quieras compartir,
                            <?php echo $user_name; ?>?
                        </span>
                    </div>
                </div>
                <div class="main-header__line">
                </div>
                <div class="main-header__icons">
                    <div class="icons__new-image"><i class="fa-solid fa-file-image"></i><span>Imagen</span></div>
                </div>
            </div>
            <div class="main-publishing">
                <div class="main-publishing__users">
                    <div class="users-info">
                        <a href="link-perfil-user"><img src="../Img/User-Profile.png" alt="Img profile"></a>
                        <a href="link-perfil-user">Harry</a>
                    </div>
                    <!-- aqui poner opción para el usuario de editar o eliminar -->
                </div>
                <div class="main-publishing__content-text">
                    <div class="content-text">
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Id fugiat ad natus repellat
                            temporibus
                            cumque deserunt modi. Esse recusandae quia quam et fuga quaerat id error ex, aliquam,
                            distinctio
                            eum omnis. Incidunt, id obcaecati saepe, inventore ipsam rem quas unde atque impedit minima
                            odit
                            totam quis sed aliquam, expedita eligendi?</p>
                    </div>
                </div>
                <div class="main-publishing__content-img">
                    <div class="content-img">
                        <img src="../Img/my cat test.jpg" alt="publishing img">
                    </div>
                </div>
                <div class="main-publishing__content-reation">
                    <div class="contet-reaction">
                    <i class="fa-solid fa-heart"></i>
                            <span>0</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../JS/profile.js"></script>
</body>

</html>
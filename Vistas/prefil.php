<?php
require_once('../Clases/perfil.php');
require_once('../Clases/publicacion.php');
require_once('../Clases/seguidor-seguido.php');
require_once('../vendor/firebase/php-jwt/src/JWT.php');
use \Firebase\JWT\JWT;

session_start();
if (!isset($_SESSION['user_id'])) {
    // Verificar si el usuario tiene un token JWT válido
    if (isset($_COOKIE['token'])) {
        $token = $_COOKIE['token'];
        $secret_key = 'Project_socialnetwork';
        try {
            $decoded = JWT::decode($token . $secret_key, array('HS256'));
        } catch (Exception $e) {
            // El token no es válido, puedes redirigir al usuario a la página de inicio de sesión
            header('Location: inicio-sesion.php');
            exit();
        }
    } else {
        // El token no está presente en la cookie, redirigir al usuario a la página de inicio de sesión
        header('Location: inicio-sesion.php');
        exit();
    }
    header('Location: inicio-sesion.php');
    exit();
}
/*Gestión perfiles*/
$perfil = new Perfil();
$user_name = $perfil->obtenerNombre();
$user_image = $perfil->obtenerImagen();
/*Gestión publicaciones*/
$publicacion = new Publicacion();
$newpublishingHTML = $publicacion->crearPublicacion();
/*Gentión seguido_seguidor*/
$seguidor_seguido = new Seguidor_Seguido();
$sugerenciasHTML = $seguidor_seguido->sugerencias();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../Css/perfil.css">
    <title>Profile</title>
</head>

<body>
    <div class="grid-container">
        <header class="header">
            <div class="header__slogan">
                <h2>Conectando el mundo, un amigo a la vez</h2>
            </div>
        </header>
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
            <div class="main-cardUser">
                <div class="userInfo__imgProfile"><img src="<?php echo $user_image; ?>" alt="profile"></div>
                <div class="main-cardUser__info">
                    <div class="info_userInfo">
                        <div class="info-name">
                            <h2>
                                <?php echo $user_name; ?>
                            </h2>
                        </div>
                        <div class="btnEdit">
                            <button id="btnEdit" class="editBtn">Editar Perfil</button>
                        </div>
                    </div>
                    <div class="info-socialMedia">
                        <div class="info-publishings"><span>Publicaciones<span class="number">10</span></span></div>
                        <div class="info-followers"><a>Seguidores<span>10</span></a></div>
                        <div class="info-ollowing"><a>Seguidos<span>10</span></a></div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../JS/inicio.js"></script>
    <script src="../JS/follow.js"></script>
    <script src="../JS/fileUpload.js"></script>
</body>

</html>
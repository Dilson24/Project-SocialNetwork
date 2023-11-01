<?php
require_once('../Clases/perfil.php');
require_once('../Clases/seguidor-seguido.php');
// require_once('../db.php');
// $database = Database::getInstance();
// $connection = $database->getConnection();
require_once('../vendor/firebase/php-jwt/src/JWT.php');
use \Firebase\JWT\JWT;
session_start();
if(!isset($_SESSION['user_id'])){
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
                    <?php echo $sugerenciasHTML; ?>
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
            <div class="popup" id="popup">
                <div class="popup-content">
                    <span class="close-button" onclick="closePopup()"><i class="fa-solid fa-xmark"></i></span>
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
                                cumque deserunt modi. Esse recusandae quia quam et fuga quaerat id error ex,
                                aliquam,
                                distinctio
                                eum omnis. Incidunt, id obcaecati saepe, inventore ipsam rem quas unde atque impedit
                                minima
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
            </div>
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../JS/inicio.js"></script>
</body>

</html>
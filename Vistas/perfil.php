<?php
// Incluir las clases y archvios necesarios
require_once('../Clases/perfil.php');
require_once('../Clases/publicacion.php');
require_once('../Clases/seguidor-seguido.php');
require_once('../vendor/firebase/php-jwt/src/JWT.php');
use \Firebase\JWT\JWT;

// Iniciar sesión
session_start();
// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Verificar si el usuario tiene un token JWT válido
    if (isset($_COOKIE['token'])) {
        $token = $_COOKIE['token'];
        $secret_key = 'Project_socialnetwork';
        try {
            // Decodificar el token JWT
            $decoded = JWT::decode($token . $secret_key, array('HS256'));
        } catch (Exception $e) {
            // El token no es válido, redirigir al usuario a la página de inicio de sesión
            header('Location: inicio-sesion.php');
            exit();
        }
    } else {
        // El token no está presente en la cookie, redirigir al usuario a la página de inicio de sesión
        header('Location: inicio-sesion.php');
        exit();
    }
}
/*Gestión perfiles*/
// Crear una instancia de la clase 'Perfil'
$perfil = new Perfil();
// Metodo para obtener datos del usuario
$userData = $perfil->obtenerDatosUsuario();
$user_name = $userData['name'];
$user_image = $userData['imagen_perfil'];
/*Gestión publicaciones*/
// Crear una instancia de la clase 'Publiacion'
$publicacion = new Publicacion();
// Método para crear una nueva publicacion
$newpublishingHTML = $publicacion->crearPublicacion();
// Método para obtener el total de publicaciones
$total_publicaciones = $publicacion->obtenerTotalPublicaciones();
/*Gentión seguido_seguidor*/
// Crear una instancia de la clase 'Seguidor_Seguido'
$seguidor_seguido = new Seguidor_Seguido();
// Método para obtener total de seguidores
$totalFollowers = $seguidor_seguido->obtenerSeguidoresTotal();
// Método para obtener total de seguidos
$totalFollowings = $seguidor_seguido->obtenerSeguidosTotal();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Dilson Alexander Cruz Nivia">
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
                <a href="inicio.php">
                    <li class="sidenav__list-item"><i class="fa-solid fa-house"></i>Inicio</li>
                </a>
                <a href="#">
                    <li class="sidenav__list-item"><i class="fa-solid fa-heart"></i>Notificaciones</li>
                </a>
                <a href="../Vistas/inicio.php#crear">
                    <li class="sidenav__list-item"><i class="fa-solid fa-square-plus"></i>Crear</li>
                </a>
                <a href="../Vistas/perfil.php">
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
                        <div class="info-publishings">
                            <span>Publicaciones<span class="number">
                                    <?php echo $total_publicaciones; ?>
                                </span></span>
                        </div>
                        <div class="info-followers">
                            <a>Seguidores<span>
                                    <?php echo $totalFollowers; ?>
                                </span></a>
                        </div>
                        <div class="info-following" id="info-following">
                            <a>Seguidos<span>
                                    <?php echo $totalFollowings; ?>
                                </span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="popup-f" id="showFollowers">
                <div class="popup-content-f">
                    <span class="popup-content__close" id="close_Followers"><i class="fa-solid fa-xmark"></i></span>
                    <div class="popup-content__title">
                        <h3>Seguidores</h3>
                        <div class="popup-content__line"></div>
                    </div>
                    <div class="popup-content__show-users">
                        <?php
                        // Obtener la lista de seguidores utilizando el método obtenerSeguidores de la instancia de Seguidor_Seguido
                        $listFollowers = $seguidor_seguido->obtenerSeguidores();
                        // Verificar si la lista de seguidores no está vacía
                        if (!empty($listFollowers)) {
                            // Iterar sobre cada seguidor en la lista
                            foreach ($listFollowers as $follower) {
                                // Obtener el ID del seguidor actual
                                $seguidor_id = $follower['seguidor_id'];
                                // Mostrar la información del seguidor
                                echo '<div class="show-users" id="' . $seguidor_id . '">';
                                echo '<div class="show-users_info">';
                                echo '<a class="show-users_profilImg"><img src="' . $follower['imagen_perfil'] . '" alt="Imagen de perfil"></a>';
                                echo '<a class="show-users_profile">' . $follower['nombre'] . '</a>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            // Si la lista de seguidores está vacía, mostrar un mensaje indicando que el usuario no tiene seguidores
                            echo '<div class="popup-content__title"><h3>No tienes seguidores</h3><div class="popup-content__line"></div></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="popup-f" id="showFollowings">
                <div class="popup-content-f">
                    <span class="popup-content__close" id="close_Following"><i class="fa-solid fa-xmark"></i></span>
                    <div class="popup-content__title">
                        <h3>Seguidos</h3>
                        <div class="popup-content__line"></div>
                    </div>
                    <div class="popup-content__show-users">
                        <?php
                        // Obtener la lista de usuarios seguidos utilizando el método obtenerSeguidos de la instancia de Seguidor_Seguido
                        $listFollowings = $seguidor_seguido->obtenerSeguidos();
                        // Verificar si la lista de usuarios seguidos no está vacía
                        if (!empty($listFollowings)) {
                            // Iterar sobre cada usuario seguido en la lista
                            foreach ($listFollowings as $following) {
                                // Obtener el ID del usuario seguido actual
                                $seguidos_id = $following['usuario_id'];
                                // Mostrar la información del usuario seguido
                                echo '<div class="show-users" id="following" data-id="' . $seguidos_id . '">';
                                echo '<div class="show-users_info">';
                                echo '<a class="show-users_profilImg"><img src="' . $following['imagen_perfil'] . '" alt="Imagen de perfil"></a>';
                                echo '<a class="show-users_profile">' . $following['nombre'] . '</a>';
                                echo '</div>';
                                // Mostrar el botón "Dejar de seguir" para cada usuario seguido
                                echo '<button data-id="' . $seguidos_id . '" class="btnUnfollow">Dejar de seguir</button>';
                                echo '</div>';
                            }
                        } else {
                            // Si la lista de usuarios seguidos está vacía, mostrar un mensaje indicando que el usuario no sigue a nadie
                            echo '<div class="popup-content__title"><h3>No sigues a nadie.</h3><div class="popup-content__line"></div></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="publicaciones-container" id="publicaciones-container">
                <?php
                // Crear una instancia de la clase 'Publicacion'
                $publicacion = new Publicacion();
                // Llamar al método para obtener las publicaciones del perfil
                $publicacion->obtenerPublicacionesPerfil();
                ?>
            </div>
            <div class="popup-p" id="showprofile">
                <div class="profile-card">
                    <span class="popup-content__close" id="close_profile_card"><i class="fa-solid fa-xmark"></i></span>
                    <div class="edit-info">
                        <div class="profile-image">
                            <img src="<?php echo $user_image; ?>" id="preview-image" alt="User Image">
                            <input type="file" id="profile-picture" accept="image/*">
                            <label for="profile-picture">Cambiar Foto</label>
                        </div>
                        <div class="edit-group">
                            <label for="first-name" class="text">Nombre:</label>
                            <input class="edit-input" name="name" type="text" id="first-name" placeholder="Nombre">
                        </div>
                        <div class="edit-group">
                            <label for="last-name" class="text">Apellido:</label>
                            <input class="edit-input" name="lastName" type="text" id="last-name" placeholder="Apellido">
                        </div>
                        <div class="edit-group">
                            <label for="birthdate" class="text">Fecha de nacimiento:</label>
                            <input class="edit-input" name="dateOfBirth" type="date" id="birthdate"
                                placeholder="Fecha de nacimiento">
                        </div>
                        <div class="edit-group">
                            <label for="country" class="text">País:</label>
                            <select id="country" class="edit-input" name="country" required="required">
                                <option value="">-- País --</option>
                            </select>
                        </div>
                        <div class="edit-group">
                            <label for="region" class="text">Región:</label>
                            <select id="region" class="edit-input">
                                <option value="">-- Región --</option>
                            </select>
                        </div>
                        <div class="edit-group">
                            <label for="city" class="text">Ciudad:</label>
                            <select id="city" class="edit-input" name="city" required="required">
                                <option value="">-- Ciudad --</option>
                            </select>
                        </div>
                        <div class="edit-group">
                            <label for="email" class="text">Email:</label>
                            <input class="edit-input" name="email" type="email" id="email" placeholder="Email">
                        </div>
                        <div class="edit-group">
                            <label for="password" class="text">Contraseña:</label>
                            <input class="edit-input" name="password" type="password" id="password"
                                placeholder="Contraseña">
                        </div>
                        <button class="update-button" id="update-button">Actualizar datos</button>
                        <button class="delete-button" id="delete-button">Eliminar Perfil</button>
                    </div>
                </div>
            </div>
            <div id="modal" class="modal">
                <div class="modal-content">
                    <span class="popup-content__close" id="close_info"><i class="fa-solid fa-xmark"></i></span>
                    <h4 class="modal_title">Digite su contraseña para confirmar</h4>
                    <div class="edit-group">
                        <label for="password" class="text">Contraseña:</label>
                        <input class="edit-input" name="password" type="password" id="password-confirm"
                            placeholder="Contraseña">
                    </div>
                    <button class="update-button" id="btn-confirm">Enviar</button>
                </div>
            </div>
        </main>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../JS/perfil.js"></script>
    <script src="../JS/register.js"></script>
</body>

</html>
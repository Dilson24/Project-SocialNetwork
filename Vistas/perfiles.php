<?php
// Incluir las clases y archivos necesarios
require_once('../Clases/perfil.php');
require_once('../Clases/publicacion.php');
require_once('../Clases/seguidor-seguido.php');
require_once('../vendor/firebase/php-jwt/src/JWT.php');
use \Firebase\JWT\JWT;

// Iniciar la sesión
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

// Verificar si se proporciona un ID de perfil en la URL
if (isset($_GET['id'])) {
    // Obtener el ID de perfil de la URL
    $profile_id = $_GET['id'];
    /*Gestión del perfil público*/
    $perfil = new Perfil();
    // Crear una instancia de clase 'Perfil'
    // Método para obtener los datos del usuario público
    $userData_P = $perfil->obtenerDatosUsuarioPublico($profile_id);
    $user_name_p = $userData_P['name'];
    $user_image_p = $userData_P['imagen_perfil'];
    /*Gestión de las publicaciones públicas*/
    // Crear una instancia de clase 'Publiacion'
    $publicacion = new Publicacion();
    // Método para obtener el total de publicaciones públicas
    $total_publicaciones_p = $publicacion->obtenerTotalPublicacionesPublic($profile_id);
    /*Gestión de seguidores y seguidos públicos*/
    // Crear una instancia de la clase 'Seguidor_Seguido'
    $seguidor_seguido = new Seguidor_Seguido();
    // Método para obtener el total de seguidores públicos
    $totalFollowers_p = $seguidor_seguido->obtenerSeguidoresTotalPublic($profile_id);
    // Método para obtener el total de seguidos públicos
    $totalFollowings_p = $seguidor_seguido->obtenerSeguidosTotalPublic($profile_id);
} else {
    // Si no se proporciona un ID de perfil en la URL, mostrar un mensaje de error
    echo "Hubo un error, inténtelo de nuevo";
}

/*Gestión de perfiles*/
// Crear una instancia de la clase perfil
$perfil = new Perfil();
// Metodo para obtener los datos del usuario actual
$userData = $perfil->obtenerDatosUsuario();
$user_name = $userData['name'];
$user_image = $userData['imagen_perfil'];
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
                <div class="userInfo__imgProfile"><img src="<?php echo $user_image_p; ?>" alt="profile"></div>
                <div class="main-cardUser__info">
                    <div class="info_userInfo">
                        <div class="info-name">
                            <h2>
                                <?php echo $user_name_p; ?>
                            </h2>
                        </div>
                        <div class="btnEdit">
                            <?php
                            // Obtener el ID del perfil desde la URL
                            $profile_id = $_GET['id'];
                            // El ID del perfil que se está siguiendo
                            $seguidos_id = $profile_id;
                            // Verificar si el usuario actual sigue al perfil actual
                            $sigueUsuario = $seguidor_seguido->sigueUsuario($_SESSION['user_id'], $seguidos_id);
                            // Verificar si el usuario actual es el mismo que el perfil actual
                            $esSeguido = ($_SESSION['user_id'] == $seguidos_id);
                            // Verificar si el usuario actual no es el mismo que el perfil actual
                            if (!$esSeguido) {
                                // Si el usuario actual sigue al perfil actual
                                if ($sigueUsuario) {
                                    // Mostrar el botón "Dejar de seguir"
                                    echo '<button data-id="' . $seguidos_id . '" class="btnUnfollow">Dejar de seguir</button>';
                                } else {
                                    // Mostrar el botón "Seguir"
                                    echo '<button data-id="' . $seguidos_id . '" class="btnFollow">Seguir</button>';
                                }
                            } else {
                                // Si el usuario actual es el mismo que el perfil actual, mostrar el botón "Seguido"
                                echo '<button data-id="' . $seguidos_id . '" class="btnFollow">Seguido</button>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="info-socialMedia">
                        <div class="info-publishings">
                            <span>Publicaciones<span class="number">
                                    <?php echo $total_publicaciones_p; ?>
                                </span></span>
                        </div>
                        <div class="info-followers">
                            <a>Seguidores<span>
                                    <?php echo $totalFollowers_p; ?>
                                </span></a>
                        </div>
                        <div class="info-following">
                            <a>Seguidos<span>
                                    <?php echo $totalFollowings_p; ?>
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
                        // Obtener la lista de seguidores públicos del perfil actual
                        $listFollowers = $seguidor_seguido->obtenerSeguidoresPublico($profile_id);
                        // Verificar si la lista de seguidores no está vacía
                        if (!empty($listFollowers)) {
                            // Iterar sobre cada seguidor en la lista
                            foreach ($listFollowers as $follower) {
                                // Obtener el ID del seguidor actual
                                $seguidor_id = $follower['seguidor_id'];
                                // Verificar si el usuario de sesión sigue al seguidor actual
                                $sigueUsuario = $seguidor_seguido->sigueUsuario($_SESSION['user_id'], $seguidor_id);
                                // Verificar si el usuario de sesión es el mismo que el seguidor actual
                                $esUsuarioSesion = ($_SESSION['user_id'] == $seguidor_id);
                                // Mostrar la información del seguidor
                                echo '<div class="show-users" id="' . $seguidor_id . '">';
                                echo '<div class="show-users_info">';
                                // Redireccionar al usuario a su perfil o al perfil público según corresponda
                                if ($esUsuarioSesion) {
                                    echo '<a href="../Vistas/perfil.php" class="show-users_profilImg"><img src="' . $follower['imagen_perfil'] . '" alt="Imagen de perfil"></a>';
                                } else {
                                    echo '<a href="../Vistas/perfiles.php?id=' . $seguidor_id . '" class="show-users_profilImg"><img src="' . $follower['imagen_perfil'] . '" alt="Imagen de perfil"></a>';
                                }
                                // Redireccionar al usuario a su perfil o al perfil público según corresponda
                                if ($esUsuarioSesion) {
                                    echo '<a href="../Vistas/perfil.php" class="show-users_profile">' . $follower['nombre'] . '</a>';
                                } else {

                                    echo '<a href="../Vistas/perfiles.php?id=' . $seguidor_id . '" class="show-users_profile">' . $follower['nombre'] . '</a>';
                                }
                                echo '</div>';
                                // Mostrar el botón "Seguir" o "Dejar de seguir" según la relación entre el usuario de sesión y el seguidor actual
                                if (!$esUsuarioSesion) {
                                    if ($sigueUsuario) {
                                        echo '<button data-id="' . $seguidor_id . '" class="btnUnfollow">Dejar de seguir</button>';
                                    } else {
                                        echo '<button data-id="' . $seguidor_id . '" class="btnFollow">Seguir</button>';
                                    }
                                }
                                echo '</div>'; // Cerrar la etiqueta div para cada seguidor
                            }
                        } else {
                            // Si la lista de seguidores está vacía, mostrar un mensaje indicando que el usuario no tiene seguidores
                            echo '<div class="popup-content__title"><h3>El usuario no tiene seguidores.</h3><div class="popup-content__line"></div></div>';
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
                        // Obtener la lista de usuarios seguidos públicamente por el perfil actual
                        $listFollowings = $seguidor_seguido->obtenerSeguidosPublico($profile_id);
                        // Verificar si la lista de usuarios seguidos no está vacía
                        if (!empty($listFollowings)) {
                            // Iterar sobre cada usuario seguido en la lista
                            foreach ($listFollowings as $following) {
                                // Obtener el ID del usuario seguido actual
                                $seguidos_id = $following['usuario_id'];
                                // Verificar si el usuario de sesión sigue al usuario seguido actual
                                $sigueUsuario = $seguidor_seguido->sigueUsuario($_SESSION['user_id'], $seguidos_id);
                                // Verificar si el usuario de sesión es el mismo que el usuario seguido actual
                                $esUsuarioSesion = ($_SESSION['user_id'] == $seguidos_id);
                                // Mostrar la información del usuario seguido
                                echo '<div class="show-users" id="' . $seguidos_id . '">';
                                echo '<div class="show-users_info">';
                                // Redireccionar al usuario a su perfil o al perfil público según corresponda
                                if ($esUsuarioSesion) {
                                    echo '<a href="../Vistas/perfil.php" class="show-users_profilImg"><img src="' . $following['imagen_perfil'] . '" alt="Imagen de perfil"></a>';
                                } else {
                                    echo '<a href="../Vistas/perfiles.php?id=' . $seguidos_id . '" class="show-users_profilImg"><img src="' . $following['imagen_perfil'] . '" alt="Imagen de perfil"></a>';
                                }
                                // Redireccionar al usuario a su perfil o al perfil público según corresponda
                                if ($esUsuarioSesion) {
                                    echo '<a href="../Vistas/perfil.php" class="show-users_profile">' . $following['nombre'] . '</a>';
                                } else {
                                    echo '<a href="../Vistas/perfiles.php?id=' . $seguidos_id . '" class="show-users_profile">' . $following['nombre'] . '</a>';
                                }
                                echo '</div>';
                                // Mostrar el botón "Seguir" o "Dejar de seguir" según la relación entre el usuario de sesión y el usuario seguido actual
                                if (!$esUsuarioSesion) {
                                    if ($sigueUsuario) {
                                        echo '<button data-id="' . $seguidos_id . '" class="btnUnfollow">Dejar de seguir</button>';
                                    } else {
                                        echo '<button data-id="' . $seguidos_id . '" class="btnFollow">Seguir</button>';
                                    }
                                }
                                echo '</div>'; // Cerrar la etiqueta div para cada usuario seguido
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
                // Llamar al método para obtener las publicaciones del perfil publico
                $publicacion->obtenerPublicacionesPerfilPublic($profile_id);
                ?>
            </div>
    </div>
    </main>
    </div>
    <script src="../JS/perfil.js"></script>
</body>

</html>
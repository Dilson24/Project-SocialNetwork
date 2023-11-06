<?php
require_once('../Clases/perfil.php');
require_once('../Clases/publicacion.php');
require_once('../Clases/seguidor-seguido.php');
/*Gestión perfiles*/
$perfil = new Perfil();
$userData = $perfil->obtenerDatosUsuario();
$user_name = $userData['name'];
$user_image = $userData['imagen_perfil'];
/*Gestión publicaciones*/
$publicacion = new Publicacion();
$newpublishingHTML = $publicacion->crearPublicacion();
$total_publicaciones = $publicacion->obtenerTotalPublicaciones();
/*Gentión seguido_seguidor*/
$seguidor_seguido = new Seguidor_Seguido();
$sugerenciasHTML = $seguidor_seguido->sugerencias();
$totalFollowers = $seguidor_seguido->obtenerSeguidoresTotal();
$totalFollowings = $seguidor_seguido->obtenerSeguidosTotal();
$listFollowers = $seguidor_seguido->obtenerSeguidores();
$listFollowings = $seguidor_seguido->obtenerSeguidos();
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
                <a href="inicio.php">
                    <li class="sidenav__list-item"><i class="fa-solid fa-house"></i>Inicio</li>
                </a>
                <a href="#">
                    <li class="sidenav__list-item"><i class="fa-solid fa-heart"></i>Notificaciones</li>
                </a>
                <a href="#">
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
                            <button id="btnEdit" class="editBtn">Seguido</button>
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
                        <div class="info-following">
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
                        $listFollowers = $seguidor_seguido->obtenerSeguidores();
                        if (!empty($listFollowers)) {
                            foreach ($listFollowers as $follower) {
                                $seguidor_id = $follower['seguidor_id']; // Obtén el ID del usuario
                                echo '<div class="show-users" id="' . $seguidor_id . '">';
                                echo '<div class="show-users_info">';
                                echo '<a class="show-users_profilImg"><img src="' . $follower['imagen_perfil'] . '" alt="Imagen de perfil"></a>';
                                echo '<a class="show-users_profile">' . $follower['nombre'] . '</a>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
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
                        $listFollowings = $seguidor_seguido->obtenerSeguidos();
                        if (!empty($listFollowings)) {
                            foreach ($listFollowings as $following) {
                                $seguidos_id = $following['usuario_id']; // Obtén el ID del usuario
                                echo '<div class="show-users" id="following" data-id="' . $seguidos_id . '">';
                                echo '<div class="show-users_info">';
                                echo '<a class="show-users_profilImg"><img src="' . $following['imagen_perfil'] . '" alt="Imagen de perfil"></a>';
                                echo '<a class="show-users_profile">' . $following['nombre'] . '</a>';
                                echo '</div>';
                                echo '<button data-id="' . $seguidos_id . '" class="btnUnfollow">Dejar de seguir</button>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="popup-content__title"><h3>No sigues a nadie.</h3><div class="popup-content__line"></div></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="publicaciones-container" id="publicaciones-container">
                <?php
                $publicacion = new Publicacion();
                $publicacion->obtenerPublicacionesPerfil();
                ?>
            </div>
    </div>

    </main>
    </div>
    <script src="../JS/perfil.js"></script>
</body>

</html>
<?php
require_once('../db.php');
$db = Database::getInstance();
$connection = $db->getConnection();
$connection->set_charset("utf8mb4");
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: inicio-sesion.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT name FROM perfiles WHERE usuario_id = $user_id";
$queryTwo = "SELECT imagen_perfil FROM perfiles WHERE usuario_id = $user_id";
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

// Ejecuta la segunda consulta
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../Css/profile.css">
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
                <a href="#"><img src="<?php echo $user_image; ?>" alt="Img profile"></a>
                <a href="#"><?php echo $user_name; ?></a>
            </div>
            <div class="sidenav__users-profiles">
                <div class="sidenav__text">
                    <samp>Descubre nuevas conexiones</samp>
                    <a href="#">Ver todo</a>
                </div>
                <div class="sidenav__follow">
                    <div class="sidenav__users-follow">
                        <a href="#"><img src="../Img/User-Profile.png" alt="Img profile"></a>
                        <a href="#">El pepe</a>
                        <a href="#">Seguir</a>
                    </div>
                </div>

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
                <a href="#">
                    <li class="sidenav__list-item"><img src="<?php echo $user_image; ?>" alt="img profile">Perfil</li>
                </a>
                <a href="#" id="logoutButton">
                    <li class="sidenav__list-item"><i class="fa-solid fa-right-from-bracket"></i>Cerrar sesión</li>
                </a>
            </ul>
        </aside>

        <main class="main">
            <div class="main-header">
                <div class="main-header__heading">Hello User</div>
                <div class="main-header__updates">Recent Items</div>
            </div>

            <div class="main-overview">
                <div class="overviewcard">
                    <div class="overviewcard__icon">Overview</div>
                    <div class="overviewcard__info">Card</div>
                </div>
                <div class="overviewcard">
                    <div class="overviewcard__icon">Overview</div>
                    <div class="overviewcard__info">Card</div>
                </div>
                <div class="overviewcard">
                    <div class="overviewcard__icon">Overview</div>
                    <div class="overviewcard__info">Card</div>
                </div>
                <div class="overviewcard">
                    <div class="overviewcard__icon">Overview</div>
                    <div class="overviewcard__info">Card</div>
                </div>
            </div>

            <div class="main-cards">
                <div class="card">Card</div>
                <div class="card">Card</div>
                <div class="card">Card</div>
            </div>
        </main>

        <footer class="footer">
            <div class="footer__copyright">&copy; 2018 MTH</div>
            <div class="footer__signature">Made with love by pure genius</div>
        </footer>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../JS/profile.js"></script>
</body>

</html>
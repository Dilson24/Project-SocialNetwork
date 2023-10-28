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
$result = $connection->query($query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_name = $row['name'];
} else {
    // Manejar el caso en que no se encuentre el nombre del usuario
    $user_name = "Usuario Desconocido";
}
// Si llega a este punto, el usuario ha iniciado sesión correctamente
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
            <div class="header__search">Search...</div>
            <div class="header__avatar">Your face</div>
        </header>
        <aside class="sidenav sidenav-rigth">
            <div class="sidenav__close-icon">
                <i class="fas fa-times sidenav__brand-close"></i>
            </div>
            <ul class="sidenav__list">
                <li class="sidenav__list-item">Item One</li>
                <li class="sidenav__list-item">Item Two</li>
                <li class="sidenav__list-item">Item Three</li>
                <li class="sidenav__list-item">Item Four</li>
                <li class="sidenav__list-item">Item Five</li>
            </ul>
        </aside>
        <aside class="sidenav sidenav-left">
            <div class="sidenav__close-icon">
                <i class="fas fa-times sidenav__brand-close"></i>
            </div>
            <ul class="sidenav__list">
                <li class="sidenav__list-item">Item One</li>
                <li class="sidenav__list-item">Item Two</li>
                <li class="sidenav__list-item">Item Three</li>
                <li class="sidenav__list-item">Item Four</li>
                <li class="sidenav__list-item">Item Five</li>
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
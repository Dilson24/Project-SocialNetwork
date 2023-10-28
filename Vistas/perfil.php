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
    <title>Document</title>
</head>

<body>
    <h1><?php echo $user_name; ?></h1>
    <button id="logoutButton">Log out</button>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../JS/logout.js"></script>
</body>

</html>
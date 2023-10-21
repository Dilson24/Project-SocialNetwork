<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: inicio-sesion.php');
    exit();
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
    <h1>hi</h1>
    <button id="logoutButton">Log out</button>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../JS/logout.js"></script>
</body>

</html>
<?php
require_once('../db.php');
$db = Database::getInstance();
$connection = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Paso 1: Insertar email y contraseña en la tabla de `usuario`
    $query = "INSERT INTO usuario (email, password) VALUES (?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ss", $email, $hashedPassword);

    if ($stmt->execute()) {
        // La inserción en la tabla `usuario` fue exitosa
        // Obtener el ID del usuario recién insertado
        $userId = $stmt->insert_id;

        // Paso 2: Insertar los demás datos en la tabla de `perfiles`
        $name = $_POST['name'];
        $lastName = $_POST['lastName'];
        $dateOfBirth = $_POST['dateOfBirth'];
        $country = $_POST['country'];
        $city = $_POST['city'];
        $nameFormatted = ucfirst(strtolower($name));
        $lastNameFormatted = ucfirst(strtolower($lastName));
        $dateOfBirthFormatted = date('Y-m-d', strtotime(str_replace('/', '-', $dateOfBirth)));
        $defaultProfileImage = '../Img/User-Profile.png';
        $query = "INSERT INTO perfiles (usuario_id, name, last_name, date_of_birth, country, city, imagen_perfil) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("issssss", $userId, $nameFormatted, $lastNameFormatted, $dateOfBirthFormatted, $country, $city, $defaultProfileImage);
        if ($stmt->execute()) {
            // La inserción en la tabla `perfiles` fue exitosa
            session_start();
            $_SESSION['name'] = $name;
            $_SESSION['userId'] = $userId;
            $_SESSION['profileImage'] = $defaultProfileImage;
            header('Location: perfil.php');
            exit();
        } else {
            echo "<script>alert('Error: Algo falló. Inténtelo de nuevo.');";
            echo "window.location = 'Registro.php';</script>";
            // Manejar el error en la inserción en la tabla `perfiles`
        }
    } else {
        echo "<script>alert('Error: Algo falló. Inténtelo de nuevo.');";
        echo "window.location = 'Registro.php';</script>";
        // Manejar el error en la inserción en la tabla `usuario`
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Dilson Alexander Cruz Nivia">
    <link rel="stylesheet" href="../Css/singUp.css" type="text/css">
    <title>Sing Up</title>
</head>

<body>
    <div class="login-card">
        <div class="card-header">
            <div class="log">Registrase</div>
        </div>
        <form action="registro.php" method="post" accept-charset="UTF-8">
            <div class="form-group">
                <label for="username">Nombre:</label>
                <input required="required" name="name" id="email" type="text">
            </div>
            <div class="form-group">
                <label for="lastName">Apellido:</label>
                <input required="required" name="lastName" id="password" type="text">
            </div>
            <div class="from-group">
                <label for="dateOfBirth">Fecha de nacimiento</label>
                <input id="boxdate" class="date" type="date" name="dateOfBirth" required="required">
            </div>
            <div class="from-group">
                <label for="country">País</label>
                <select id="country" class='form-control' name="country" required="required">
                    <option value="">-- País --</option>
                </select>
            </div>
            <div class="from-group">
                <label for="Region">Región</label>
                <select id="region" class='form-control'>
                    <option value="">-- Región --</option>
                </select>
            </div>
            <div class="from-group">
                <label for="city">Ciudad</label>
                <select id="city" class='form-control' name="city" required="required">
                    <option value="">-- Ciudad --</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input required="required" name="email" id="email" type="email">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input required="required" name="password" id="password" type="password">
            </div>
            <div class="form-group">
                <input value="Registrase" type="submit">
            </div>
        </form>
        <p>¿Ya tienes cuenta? <a href="inicio-sesion.php">Iniciar Sesion</a></p>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="../JS/register.js"></script>
</body>

</html>
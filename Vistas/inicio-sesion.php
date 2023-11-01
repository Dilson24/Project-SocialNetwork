<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Dilson Alexander Cruz Nivia">
    <link rel="stylesheet" href="../Css/singIn.css" type="text/css">
    <title>Sing In</title>
</head>

<body>
    <div class="login-card">
        <div class="card-header">
            <div class="log">Inicio de sesión</div>
        </div>
        <form method="post" action="../Clases/usuario.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input required="required" name="Email" id="email" type="email">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input required="required" name="password" id="password" type="password">
            </div>
            <div class="form-group">
                <input value="Iniciar Sesión" type="submit">
            </div>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
    </div>
</body>
</html>
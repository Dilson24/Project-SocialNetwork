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
        <form>
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
                <select id="country" class='form-control' name="countryR" required="required">
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
                <select id="city" class='form-control' name="cityR" required="required">
                    <option value="">-- Ciudad --</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input required="required" name="Email" id="email" type="email">
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

</body>

</html>
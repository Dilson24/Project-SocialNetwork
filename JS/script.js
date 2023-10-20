// Selecciona el botón por su ID
var btnSingIn = document.getElementById("btnSignIn");
var btnSingUp = document.getElementById("btnSignUp");

// Agrega un escuchador de eventos al botón
btnSingIn.addEventListener("click", function() {
    // Redirige a la página deseada cuando se hace clic
    window.location.href = "Vistas/inicio-sesion.php"; // Reemplaza "https://www.ejemplo.com" con la URL a la que deseas redirigir.
});

// Agrega un escuchador de eventos al botón
btnSingUp.addEventListener("click", function() {
    // Redirige a la página deseada cuando se hace clic
    window.location.href = "Vistas/registro.php"; // Reemplaza "https://www.ejemplo.com" con la URL a la que deseas redirigir.
});
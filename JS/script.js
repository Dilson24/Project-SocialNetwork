// Selecciona el botón por su ID
var btnSingIn = document.getElementById("btnSignIn");
var btnSingUp = document.getElementById("btnSignUp");

// Agrega un escuchador de eventos al botón
btnSingIn.addEventListener("click", function() {
    // Redirige a la página deseada cuando se hace clic
    window.location.href = "Vistas/inicio-sesion.php"; 
});

// Agrega un escuchador de eventos al botón
btnSingUp.addEventListener("click", function() {
    // Redirige a la página deseada cuando se hace clic
    window.location.href = "Vistas/registro.php"; 
}); 
// Función para abrir el popup
function openPopup() {
    var popup = document.getElementById("popup");
    popup.style.display = "block";
}

// Función para cerrar el popup
function closePopup() {
    var popup = document.getElementById("popup");
    popup.style.display = "none";
}

// Agrega un evento de clic al contenedor main-publishing
var mainPublishing = document.querySelector(".main-publishing");
mainPublishing.addEventListener("click", openPopup);



/*BTN LOG OUT*/ 
$(document).ready(function () {
    $("#logoutButton").click(function () {
        $.ajax({
            url: '../Vistas/logout.php', // Ruta al archivo PHP que contiene el código de cierre de sesión
            type: 'GET',
            success: function (response) {
                // Redirigir al usuario a la página de inicio después de cerrar sesión
                window.location.href = '../index.php';
            }
        });
    });
});

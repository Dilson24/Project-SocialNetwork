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

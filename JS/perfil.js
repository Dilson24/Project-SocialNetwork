// Función para abrir un popup
function openPopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = "flex";
        popup.classList.add("active");
    }
}

const showFollowers = document.querySelector(".info-followers");
showFollowers.addEventListener("click", function () {
    openPopup("showFollowers");
});

const showFollowing = document.querySelector(".info-following");
showFollowing.addEventListener("click", function () {
    openPopup("showFollowings");
});

// Función para cerrar el popup
function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = "none";
        popup.classList.remove("active");
    }
}

const closeFollowers = document.getElementById("close_Followers"); // Debes asegurarte de que este sea el botón que cierra el popup
closeFollowers.addEventListener("click", function () {
    closePopup("showFollowers");
});
const closeFollowing = document.getElementById("close_Following"); // Debes asegurarte de que este sea el botón que cierra el popup
closeFollowing.addEventListener("click", function () {
    closePopup("showFollowings");
});

// Manejo de solicitudes en jQuery
$(document).ready(function () {
    $("#logoutButton").click(function () {
        $.ajax({
            url: '../Clases/usuario.php?logout', // Ruta al archivo PHP que contiene el código de cierre de sesión
            type: 'GET',
            success: function (response) {
                // Redirigir al usuario a la página de inicio después de cerrar sesión
                window.location.href = '../index.php';
            }
        });
    });
    $(".btnUnfollow").click(function () {
        // Obtener el ID del usuario a dejar de seguir
        var seguidos_id = parseInt($(this).data("id"));
        //Crear un objeto FromData para enviar los datos
        var fromData = new FormData();
        fromData.append('usuario_id', seguidos_id);
        $.ajax({
            url: '../Clases/seguidor-seguido.php?Unfollow',
            type: 'POST',
            data: fromData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                console.log(response);
            }
        });
    });
});


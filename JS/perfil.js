// Función para abrir un popup
function openPopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = "flex";
        popup.classList.add("active");
    }
}

// Función para cerrar el popup
function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = "none";
        popup.classList.remove("active");
    }
}

// Función para cambiar el aspecto del botón Unfollow
function followBtn(button) {
    button.textContent = 'Seguir';
    button.id = 'follow';
}

// Agregar eventos y controladores de clic
document.addEventListener("DOMContentLoaded", function () {
    const showFollowers = document.querySelector(".info-followers");
    showFollowers.addEventListener("click", function () {
        openPopup("showFollowers");
    });

    const closeFollowers = document.getElementById("close_Followers");
    closeFollowers.addEventListener("click", function () {
        closePopup("showFollowers");
    });

    const showFollowing = document.querySelector(".info-following");
    showFollowing.addEventListener("click", function () {
        openPopup("showFollowings");
    });

    const closeFollowing = document.getElementById("close_Following");
    closeFollowing.addEventListener("click", function () {
        closePopup("showFollowings");
    });
    //Manejo de solioitudes
    // Logout Button
    var logoutButton = document.getElementById("logoutButton");
    if (logoutButton) {
        logoutButton.addEventListener("click", function () {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", '../Clases/usuario.php?logout', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    window.location.href = '../index.php';
                }
            };
            xhr.send();
        });
    }
    // Unfollow Button
    document.querySelectorAll(".btnUnfollow").forEach(function (button) {
        button.addEventListener("click", function () {
            // Obtener el ID del usuario a dejar de seguir
            var seguidos_id = parseInt(this.getAttribute("data-id"));

            // Crear un objeto FormData para enviar los datos
            var formData = new FormData();
            formData.append('usuario_id', seguidos_id);

            // Crear una solicitud AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../Clases/seguidor-seguido.php?Unfollow', true);

            // Configurar el encabezado necesario
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            // Manejar la respuesta de la solicitud
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Actualizar el elemento HTML con el nuevo total de seguidos
                            var totalFollowingsElement = document.querySelector(".info-following span");
                            totalFollowingsElement.textContent = response.total_seguidos;
                            console.log(response);
                            followBtn(button);
                        } else {
                            console.log("Error al dejar de seguir al usuario");
                        }
                    } catch (e) {
                        console.log("Error al analizar la respuesta JSON");
                    }
                } else {
                    console.log("Error en la solicitud: " + xhr.status);
                }
            };

            // Enviar los datos del formulario
            xhr.send(formData);
        });
    });

});


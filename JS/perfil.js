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

// Función para abrir el popup con la publicación
function openPublishing(publicacionId) {
    // Buscamos el elemento con la clase "popup-publishing" que tiene un atributo "data-publicacion-id" igual a "publicacionId"
    var popup = document.querySelector(`.popup[data-publicacion-id="${publicacionId}"]`);
    if (popup) {
        // Si se encuentra el popup, lo hacemos visible y le añadimos la clase "active"
        popup.style.display = "flex";
        popup.classList.add("active");
    }
}

// Función para cerrar el popup con la publicación
function closePopupPublishing() {
    // Buscamos el elemento con la clase "popup" que tiene la clase "active"
    var popup = document.querySelector('.popup.active');
    if (popup) {
        // Si se encuentra un popup activo, lo ocultamos y eliminamos la clase "active"
        popup.style.display = "none";
        popup.classList.remove('active');
    }
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

    const btnShowpCardProfile = document.getElementById("btnEdit");
    btnShowpCardProfile.addEventListener("click", function (){
        openPopup("showprofile");
    });

    const closeCardProfile = document.getElementById("close_profile_card");
    closeCardProfile.addEventListener("click", function(){
        closePopup("showprofile");
    });

    // Agrega un evento al contenedor de publicaciones
    const publicacionesContainer = document.getElementById("publicaciones-container");
    publicacionesContainer.addEventListener('click', function (event) {
        // Cuando se hace clic en el contenedor de publicaciones, buscamos el elemento más cercano con la clase "main-publishing"
        const element = event.target.closest('.main-publishing');
        if (element) {
            // Obtenemos el valor del atributo "data-publicacion-id" del elemento
            var publicacionId = element.getAttribute('data-publicacion-id');
            // Luego, llamamos a la función openPublishing para abrir el popup correspondiente
            openPublishing(publicacionId);
        }

        // Buscamos el botón con el id "close_popup_publishing"
        const btnClosePublishingList = document.querySelectorAll(".close-button");
        btnClosePublishingList.forEach(function (btnClosePublishing) {
            btnClosePublishing.addEventListener("click", function () {
                // Obtener el elemento "popup-publishing" relativo al botón
                const elementToClose = btnClosePublishing.closest(".popup.active");
                if (elementToClose) {
                    closePopupPublishing();
                }
            });
        });
    });
    // Prewiew image
    const input = document.getElementById('profile-picture');
        const preview = document.getElementById('preview-image');

        input.addEventListener('change', function () {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                // Si no se selecciona ningún archivo, puedes mostrar una imagen por defecto o dejarla vacía.
                preview.src = '../Img/User-Profile.png';
            }
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

    // Función para manejar el seguimiento (Follow/Unfollow) de un usuario
    function toggleFollow(button) {
        // Obtiene el ID del usuario desde el atributo "data-id" del botón
        var usuario_id = button.getAttribute("data-id");

        // Determina si se debe realizar un seguimiento o dejar de seguir
        var action = button.classList.contains("btnUnfollow") ? "Unfollow" : "Follow";

        // Crea una solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", '../Clases/seguidor-seguido.php?' + action, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Maneja la respuesta de la solicitud
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Actualiza el botón y el total de seguidos en la interfaz
                        var totalFollowingsElement = document.querySelector("#info-following span");
                        if (totalFollowingsElement) {
                            totalFollowingsElement.textContent = response.total_seguidos;
                        }
                        if (action === "Unfollow") {
                            // Si se estaba siguiendo al usuario, cambia el botón y el texto
                            button.classList.remove("btnUnfollow");
                            button.classList.add("btnFollow");
                            button.textContent = "Seguir";
                        } else {
                            // Si no se estaba siguiendo al usuario, cambia el botón y el texto
                            button.classList.remove("btnFollow");
                            button.classList.add("btnUnfollow");
                            button.textContent = "Dejar de seguir";
                        }
                        console.log(response.message);
                    } else {
                        console.log("Error al cambiar el estado de seguimiento");
                    }
                } else {
                    console.error("Error en la solicitud AJAX: " + xhr.statusText);
                }
            }
        };

        // Envía el ID del usuario al servidor
        xhr.send("usuario_id=" + usuario_id);
    }

    // Agregar evento de clic a los botones de seguimiento (Follow/Unfollow)
    document.querySelectorAll(".btnFollow, .btnUnfollow").forEach(function (button) {
        // Cuando se hace clic en un botón, llama a la función toggleFollow
        button.addEventListener("click", function () {
            toggleFollow(this);
        });
    });

});


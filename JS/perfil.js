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

// Función para restablecer valores
function restablecerValores() {
    // Obtén todos los campos
    document.getElementById("first-name").value = "";
    document.getElementById("last-name").value = "";
    document.getElementById("birthdate").value = "";
    document.getElementById("country").value = "";
    document.getElementById("city").value = "";
    document.getElementById("email").value = "";
    document.getElementById("password").value = "";
    document.getElementById("profile-picture").value = "";
    closePopup("showprofile");
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
    btnShowpCardProfile.addEventListener("click", function () {
        openPopup("showprofile");
    });

    const closeCardProfile = document.getElementById("close_profile_card");
    closeCardProfile.addEventListener("click", function () {
        closePopup("showprofile");
    });

    const closeModal = document.getElementById("close_info");
    closeModal.addEventListener("click", function () {
        closePopup("modal");
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
    // Update profile 
    var updateProfile = document.getElementById("update-button");
    if (updateProfile) {
        updateProfile.addEventListener("click", function () {
            var xhr = new XMLHttpRequest();
            var formData = new FormData();

            // Obtén todos los campos
            var name = document.getElementById("first-name").value;
            var lastName = document.getElementById("last-name").value;
            var dateOfBirth = document.getElementById("birthdate").value;
            var country = document.getElementById("country").value;
            var city = document.getElementById("city").value;
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;

            // Agrega los campos no nulos al FormData
            if (name) formData.append("name", name);
            if (lastName) formData.append("lastName", lastName);
            if (dateOfBirth) formData.append("dateOfBirth", dateOfBirth);
            if (country) formData.append("country", country);
            if (city) formData.append("city", city);
            if (email) formData.append("email", email);
            if (password) formData.append("password", password);

            // Agrega la imagen solo si se ha seleccionado
            var imageInput = document.getElementById("profile-picture");
            if (imageInput.files.length > 0) {
                formData.append("image_profile", imageInput.files[0]);
            }
            // Mostrar el contenido de FormData en la consola
            console.log("Contenido de FormData:");
            for (let pair of formData.entries()) {
                console.log(pair[0] + ', ' + pair[1]);
            }
            // Envía la solicitud AJAX
            xhr.open("POST", '../Clases/perfil.php?update', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        // Maneja la respuesta del servidor
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            console.log(response.message);
                            restablecerValores()
                            if (response.data) {
                                console.log("Datos modificados:", response.data);
                                // Realiza acciones adicionales si es necesario
                            }
                        } else {
                            console.log(response.message);
                        }
                    } else {
                        console.log("Error en la solicitud AJAX: " + xhr.statusText);
                    }
                }
            };
            xhr.send(formData);
            console
        });
    }

    // Delete button
    var deleteProfile = document.getElementById("delete-button");
    if (deleteProfile) {
        deleteProfile.addEventListener("click", function () {
            var confirmDelete = confirm("¿Estás seguro de que deseas eliminar tu perfil?");
            if (confirmDelete) {
                openPopup("modal");
                closePopup("showprofile");
            }
        });
    }

    var confirmBtn = document.getElementById("btn-confirm");
    if (confirmBtn) {
        confirmBtn.addEventListener("click", function () {
            var password = document.getElementById("password-confirm").value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", '../Clases/perfil.php?delete', true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Procesar la respuesta JSON
                    var response = JSON.parse(xhr.responseText);

                    // Mostrar la alerta según la respuesta del servidor
                    if (response.success) {
                        window.location = '../index.php';
                    } else {
                        // Fallo, muestra la alerta
                        alert(response.message);
                        document.getElementById("password-confirm").value = "";
                        closePopup("modal");
                    }
                }
            };
            // Enviar la solicitud con la contraseña como parámetro
            xhr.send("password=" + password);
        });
    }


});


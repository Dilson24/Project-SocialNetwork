// Función para abrir un popup por medio de su ID
function openPopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        // Si se encuentra el popup, lo hacemos visible y le añadimos la clase "active"
        popup.style.display = "flex";
        popup.classList.add("active");
    }
}

// Función para cerrar el popup por medio de su ID
function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        // Si se encuentra un popup activo, lo ocultamos y eliminamos la clase "active"
        popup.style.display = "none";
        popup.classList.remove("active");
    }
}

// Función para abrir el popup con la publicación por medio de su ID
function openPublishing(publicacionId) {
    // Buscamos el elemento con la clase "popup-publishing" que tiene un atributo "data-publicacion-id" igual a "publicacionId"
    var popup = document.querySelector(`.popup[data-publicacion-id="${publicacionId}"]`);
    if (popup) {
        // Si se encuentra el popup, lo hacemos visible y le añadimos la clase "active"
        popup.style.display = "flex";
        popup.classList.add("active");
    }
}

// Función para cerrar el popup con la publicación por medio de su ID
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
    // Obtén todos los campos y restablece sus valores a vacío
    document.getElementById("first-name").value = "";
    document.getElementById("last-name").value = "";
    document.getElementById("birthdate").value = "";
    document.getElementById("country").value = "";
    document.getElementById("city").value = "";
    document.getElementById("email").value = "";
    document.getElementById("password").value = "";
    document.getElementById("profile-picture").value = "";
    // Cierra el popup de perfil al restablecer los valores
    closePopup("showprofile");
}

// Agregar eventos y controladores de clic
document.addEventListener("DOMContentLoaded", function () {

    // Abre el popup de seguidores al hacer clic en el elemento con la clase "info-followers"
    const showFollowers = document.querySelector(".info-followers");
    showFollowers.addEventListener("click", function () {
        openPopup("showFollowers");
    });

    // Cierra el popup de seguidores al hacer clic en el elemento con el id "close_Followers"
    const closeFollowers = document.getElementById("close_Followers");
    closeFollowers.addEventListener("click", function () {
        closePopup("showFollowers");
    });

    // Abre el popup de seguidos al hacer clic en el elemento con la clase "info-following"
    const showFollowing = document.querySelector(".info-following");
    showFollowing.addEventListener("click", function () {
        openPopup("showFollowings");
    });

    // Cierra el popup de seguidos al hacer clic en el elemento con el id "close_Following"
    const closeFollowing = document.getElementById("close_Following");
    closeFollowing.addEventListener("click", function () {
        closePopup("showFollowings");
    });

    // Abre el popup de perfil al hacer clic en el elemento con el id "btnEdit"
    const btnShowpCardProfile = document.getElementById("btnEdit");
    // Condicional para verificar que el elemento esté en el DOM y así evitar errores en consola
    if (btnShowpCardProfile) {
        btnShowpCardProfile.addEventListener("click", function () {
            openPopup("showprofile");
        });
    }

    // Cierra el popup de perfil al hacer clic en el elemento con el id "close_profile_card"
    const closeCardProfile = document.getElementById("close_profile_card");
    // Condicional para verificar que el elemento esté en el DOM y así evitar errores en consola
    if (closeCardProfile) {
        closeCardProfile.addEventListener("click", function () {
            closePopup("showprofile");
        });
    }

    // Cierra el modal de contraseña al hacer clic en elemento con el id "close_info"
    const closeModal = document.getElementById("close_info");
    // Condicional para verificar que el elemento esté en el DOM y así evitar errores en consola
    if (closeModal) {
        closeModal.addEventListener("click", function () {
            closePopup("modal");
        });
    }

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
    // Preview image
    // Obtiene una referencia al elemento de entrada de tipo archivo con el id "profile-picture"
    const input = document.getElementById('profile-picture');
    // Obtiene una referencia al elemento de vista previa de la imagen con el id "preview-image"
    const preview = document.getElementById('preview-image');
    // Agrega un event listener al elemento de entrada de archivo para detectar cambios en la selección de archivos
    if (input) {
        input.addEventListener('change', function () {
            // Obtiene el primer archivo seleccionado (si existe)
            const file = input.files[0];
            // Verifica si se seleccionó un archivo
            if (file) {
                // Crea un objeto FileReader para leer el contenido del archivo
                const reader = new FileReader();
                // Define la función que se ejecutará cuando la lectura del archivo esté completa
                reader.onload = function (e) {
                    // Establece la fuente de la imagen de vista previa con los datos del archivo leído
                    preview.src = e.target.result;
                };
                // Inicia la lectura del archivo como una URL de datos (data URL)
                reader.readAsDataURL(file);
            }
        });
    }
    // Manejo de solicitudes
    // Logout Button
    // Obtiene una referencia al elemento de botón de cierre de sesión con el id "logoutButton"
    var logoutButton = document.getElementById("logoutButton");
    // Verifica si el botón de cierre de sesión existe antes de agregar el event listener
    if (logoutButton) {
        // Agrega un event listener al botón de cierre de sesión para detectar clics
        logoutButton.addEventListener("click", function () {
            // Crea un nuevo objeto XMLHttpRequest para realizar una solicitud al servidor
            var xhr = new XMLHttpRequest();
            // Configura la solicitud GET al archivo "usuario.php" con el parámetro "logout"
            xhr.open("GET", '../Clases/usuario.php?logout', true);
            // Define una función que se ejecutará cada vez que cambie el estado de la solicitud
            xhr.onreadystatechange = function () {
                // Verifica si la solicitud se ha completado (readyState 4) y si el estado es 200 (OK)
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Redirige al usuario a la página de inicio después de cerrar sesión
                    window.location.href = '../index.php';
                }
            };
            // Envía la solicitud al servidor
            xhr.send();
        });
    }

    // Función para cambiar el estado de seguimiento (Follow/Unfollow) al hacer clic en un botón
    function toggleFollow(button) {
        // Obtiene el ID del usuario desde el atributo "data-id" del botón
        var usuario_id = button.getAttribute("data-id");
        // Determina si se debe realizar un seguimiento o dejar de seguir
        var action = button.classList.contains("btnUnfollow") ? "Unfollow" : "Follow";
        // Crea una solicitud AJAX
        var xhr = new XMLHttpRequest();
        // Configura la solicitud POST al archivo "seguidor-seguido.php" con el parámetro de acción (Follow/Unfollow)
        xhr.open("POST", '../Clases/seguidor-seguido.php?' + action, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        // Maneja la respuesta de la solicitud
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Parsea la respuesta JSON del servidor
                    var response = JSON.parse(xhr.responseText);
                    // Verifica si la operación fue exitosa
                    if (response.success) {
                        // Actualiza el botón y el total de seguidos en la interfaz
                        var totalFollowingsElement = document.querySelector("#info-following span");
                        if (totalFollowingsElement) {
                            totalFollowingsElement.textContent = response.total_seguidos;
                        }
                        // Actualiza la apariencia y texto del botón según la acción realizada (Follow/Unfollow)
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
                        // Muestra el mensaje de la respuesta en la consola
                        console.log(response.message);
                    } else {
                        // Muestra un mensaje de error en la consola si la operación no fue exitosa
                        console.log("Error al cambiar el estado de seguimiento");
                    }
                } else {
                    // Muestra un mensaje de error en la consola si hay un error en la solicitud AJAX
                    console.error("Error en la solicitud AJAX: " + xhr.statusText);
                }
            }
        };
        // Envía el ID del usuario al servidor como datos en formato x-www-form-urlencoded
        xhr.send("usuario_id=" + usuario_id);
    }

    // Agrega un evento de clic a todos los botones de seguimiento (Follow/Unfollow) en la página
    document.querySelectorAll(".btnFollow, .btnUnfollow").forEach(function (button) {
        // Cuando se hace clic en un botón, llama a la función toggleFollow
        button.addEventListener("click", function () {
            toggleFollow(this);
        });
    });

    // Update profile 
    // Obtiene una referencia al botón de actualización del perfil con el id "update-button"
    var updateProfile = document.getElementById("update-button");
    // Verifica si el botón de actualización del perfil existe antes de agregar el event listener
    if (updateProfile) {
        // Agrega un event listener al botón de actualización del perfil para detectar clics
        updateProfile.addEventListener("click", function () {
            // Crea un nuevo objeto XMLHttpRequest para realizar una solicitud al servidor
            var xhr = new XMLHttpRequest();
            // Crea un objeto FormData para almacenar los datos del formulario
            var formData = new FormData();
            // Obtiene los valores de los campos del formulario
            var name = document.getElementById("first-name").value;
            var lastName = document.getElementById("last-name").value;
            var dateOfBirth = document.getElementById("birthdate").value;
            var country = document.getElementById("country").value;
            var city = document.getElementById("city").value;
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            // Agrega los campos no nulos al objeto FormData
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
            // Muestra el contenido de FormData en la consola para depuración
            console.log("Contenido de FormData:");
            for (let pair of formData.entries()) {
                console.log(pair[0] + ', ' + pair[1]);
            }
            // Configura la solicitud POST al archivo "perfil.php" con el parámetro de actualización
            xhr.open("POST", '../Clases/perfil.php?update', true);
            // Define una función que se ejecutará cada vez que cambie el estado de la solicitud
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        // Maneja la respuesta del servidor
                        var response = JSON.parse(xhr.responseText);
                        // Verifica si la operación fue exitosa
                        if (response.success) {
                            console.log(response.message);
                            // Restablece los valores del formulario después de una actualización exitosa
                            restablecerValores();
                            // Muestra datos adicionales en la consola si están disponibles
                            if (response.data) {
                                console.log("Datos modificados:", response.data);
                                // Realiza acciones adicionales si es necesario
                            }
                        } else {
                            // Muestra un mensaje de error en la consola si la operación no fue exitosa
                            console.log(response.message);
                        }
                    } else {
                        // Muestra un mensaje de error en la consola si hay un error en la solicitud AJAX
                        console.log("Error en la solicitud AJAX: " + xhr.statusText);
                    }
                }
            };
            // Envía la solicitud al servidor con los datos del formulario en formato FormData
            xhr.send(formData);
        });
    }

    // Delete button
    // Obtiene una referencia al botón de eliminación de perfil con el id "delete-button"
    var deleteProfile = document.getElementById("delete-button");
    // Verifica si el botón de eliminación de perfil existe antes de agregar el event listener
    if (deleteProfile) {
        // Agrega un event listener al botón de eliminación de perfil para detectar clics
        deleteProfile.addEventListener("click", function () {
            // Solicita confirmación al usuario antes de proceder con la eliminación
            var confirmDelete = confirm("¿Estás seguro de que deseas eliminar tu perfil?");
            // Verifica si el usuario confirmó la eliminación
            if (confirmDelete) {
                // Abre el popup modal al confirmar la eliminación
                openPopup("modal");
                // Cierra el popup de perfil al confirmar la eliminación
                closePopup("showprofile");
            }
        });
    }

    // Obtiene una referencia al botón de confirmación con el id "btn-confirm"
    var confirmBtn = document.getElementById("btn-confirm");
    // Verifica si el botón de confirmación existe antes de agregar el event listener
    if (confirmBtn) {
        // Agrega un event listener al botón de confirmación para detectar clics
        confirmBtn.addEventListener("click", function () {
            // Obtiene el valor del campo de contraseña de confirmación
            var password = document.getElementById("password-confirm").value;
            // Crea un nuevo objeto XMLHttpRequest para realizar una solicitud al servidor
            var xhr = new XMLHttpRequest();
            // Configura la solicitud POST al archivo "perfil.php" con el parámetro de eliminación
            xhr.open("POST", '../Clases/perfil.php?delete', true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            // Define una función que se ejecutará cada vez que cambie el estado de la solicitud
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Procesar la respuesta JSON del servidor
                    var response = JSON.parse(xhr.responseText);
                    // Mostrar la alerta según la respuesta del servidor
                    if (response.success) {
                        // Redirige al usuario a la página de inicio si la eliminación es exitosa
                        window.location = '../index.php';
                    } else {
                        // Muestra una alerta en caso de fallo y restablece el campo de contraseña
                        alert(response.message);
                        document.getElementById("password-confirm").value = "";
                        // Cierra el popup modal después de mostrar la alerta
                        closePopup("modal");
                    }
                }
            };
            // Envía la solicitud al servidor con la contraseña como parámetro en formato x-www-form-urlencoded
            xhr.send("password=" + password);
        });
    }
});
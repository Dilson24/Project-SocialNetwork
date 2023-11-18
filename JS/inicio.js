// Función para abrir un popup
function openPopup(popupId) {
    // Obtener referencia al elemento del popup por su ID
    const popup = document.getElementById(popupId);
    // Verificar si se encontró el popup
    if (popup) {
        // Mostrar el popup estableciendo su estilo de visualización a "flex"
        popup.style.display = "flex";
        // Agregar la clase "active" al popup
        popup.classList.add("active");
    }
}

// Función para cerrar un popup
function closePopup(popupId) {
    // Obtener referencia al elemento del popup por su ID
    const popup = document.getElementById(popupId);
    // Verificar si se encontró el popup
    if (popup) {
        // Ocultar el popup estableciendo su estilo de visualización a "none"
        popup.style.display = "none";
        // Remover la clase "active" del popup
        popup.classList.remove("active");
    }
}

// Función para mostrar u ocultar el contenido de imagen
function toggleImageContent() {
    // Obtener referencia al elemento de contenido de imagen por su ID
    const imageContent = document.getElementById("imageContent");
    // Verificar si se encontró el elemento de contenido de imagen
    if (imageContent) {
        // Verificar el estado actual de visualización del contenido de imagen
        if (imageContent.style.display === "none" || imageContent.style.display === "") {
            // Si está oculto o no tiene un estilo de visualización, mostrar estableciendo su estilo a "flex"
            imageContent.style.display = "flex";
        } else {
            // Si está visible, ocultar estableciendo su estilo a "none"
            imageContent.style.display = "none";
        }
    }
}

// Función para comprobar el contenido del textarea y el archivo de entrada
function checkContent() {
    // Obtener referencias a los elementos del textarea, el archivo de entrada y el botón de publicar por sus ID
    const textarea = document.getElementById("contentTextarea");
    const fileInput = document.getElementById("file-upload");
    const publishButton = document.getElementById("publishBtn");
    // Verificar si el contenido del textarea no está vacío o si se seleccionó un archivo
    if (textarea.value.trim() !== "" || fileInput.files.length > 0) {
        // Si hay contenido, agregar la clase "active" al botón de publicar
        publishButton.classList.add("active");
    } else {
        // Si no hay contenido, remover la clase "active" del botón de publicar
        publishButton.classList.remove("active");
    }
}

// Función para cambiar aspecto de boton follow
function followBtn(boton) {
    // Cambiar el texto del botón a 'Seguido'
    boton.textContent = 'Seguido';
    // Deshabilitar el botón
    boton.disabled = true;
    // Cambiar el color del texto a negro
    boton.style.color = '#000000';
    // Establecer el cursor a 'none' para indicar que no es interactivo
    boton.style.cursor = 'none';
}

/// Función para crear dinámicamente una nueva publicación
function createNewPublication(response) {
    // Crear un nuevo elemento div para la publicación
    const nuevaPublicacion = document.createElement("div");
    nuevaPublicacion.className = "main-publishing";
    nuevaPublicacion.setAttribute("data-publicacion-id", response.publicacion_id);
    // Crear y agregar contenido de usuario a la publicación
    const contentUser = document.createElement("div");
    contentUser.className = "main-publishing__users";
    contentUser.innerHTML = `
        <div class="users-info">
            <a href="../Vistas/perfil.php"><img src="${response.user_image}" alt="Img profile"></a>
            <a href="../Vistas/perfil.php">${response.user_name}</a>
        </div>
    `;
    nuevaPublicacion.appendChild(contentUser);
    // Agregar contenido de texto si existe en la respuesta
    if (response.texto) {
        const contentText = document.createElement("div");
        contentText.className = "main-publishing__content-text";
        contentText.innerHTML = `
            <div class="content-text">
                <p>${response.texto}</p>
            </div>
        `;
        nuevaPublicacion.appendChild(contentText);
    }
    // Agregar contenido de imagen si existe en la respuesta
    if (response.imagen_ruta) {
        const contentImg = document.createElement("div");
        contentImg.className = "main-publishing__content-img";
        contentImg.innerHTML = `
            <div class="content-img">
                <img src="${response.imagen_ruta}" alt="publishing img">
            </div>
        `;
        nuevaPublicacion.appendChild(contentImg);
    }
    // Agregar contenido de reacción a la publicación
    const contentReaction = document.createElement("div");
    contentReaction.className = "main-publishing__content-reation";
    contentReaction.innerHTML = `
        <div class="contet-reaction">
            <i class="fa-solid fa-heart"></i>
            <span>0</span>
        </div>
    `;
    nuevaPublicacion.appendChild(contentReaction);
    // Obtener el contenedor de publicaciones y agregar la nueva publicación al principio
    const publicacionesContainer = document.getElementById("publicaciones-container");
    publicacionesContainer.prepend(nuevaPublicacion);
}


// Función para crear dinámicamente un nuevo popup de publicación
function createPublicationPopup(response) {
    // Crear un nuevo elemento div para el popup de publicación
    const publishingPopup = document.createElement("div");
    publishingPopup.className = "popup";
    publishingPopup.id = "popup_publishing";
    publishingPopup.setAttribute("data-publicacion-id", response.publicacion_id);

    // Crear un contenedor para el contenido del popup
    const contentPopup = document.createElement("div");
    contentPopup.className = "popup-content";
    publishingPopup.appendChild(contentPopup);

    // Crear y agregar un botón para cerrar el popup
    const contentPopupBtnClose = document.createElement("span");
    contentPopupBtnClose.className = "close-button";
    contentPopupBtnClose.id = "close_popup_publishing";
    contentPopupBtnClose.innerHTML = `<i class="fa-solid fa-xmark"></i>`;
    contentPopup.appendChild(contentPopupBtnClose);

    // Agregar contenido de usuario al popup
    const contentPopupUser = document.createElement("div");
    contentPopupUser.className = "main-publishing__users";
    contentPopupUser.innerHTML = `
        <div class="users-info">
            <a href="../Vistas/perfil.php"><img src="${response.user_image}" alt="Img profile"></a>
            <a href="../Vistas/perfil.php">${response.user_name}</a>
        </div>
    `;
    contentPopup.appendChild(contentPopupUser);

    // Agregar contenido de texto al popup si existe en la respuesta
    if (response.texto) {
        const contentTextPopup = document.createElement("div");
        contentTextPopup.className = "main-publishing__content-text";
        contentTextPopup.innerHTML = `
            <div class="content-text">
                <p>${response.texto}</p>
            </div>
        `;
        contentPopup.appendChild(contentTextPopup);
    }

    // Agregar contenido de imagen al popup si existe en la respuesta
    if (response.imagen_ruta) {
        const contentImgPopup = document.createElement("div");
        contentImgPopup.className = "main-publishing__content-img";
        contentImgPopup.innerHTML = `
            <div class="content-img">
                <img src="${response.imagen_ruta}" alt="publishing img">
            </div>
        `;
        contentPopup.appendChild(contentImgPopup);
    }

    // Agregar contenido de reacción al popup
    const contentReactionPopup = document.createElement("div");
    contentReactionPopup.className = "main-publishing__content-reation";
    contentReactionPopup.innerHTML = `
        <div class="contet-reaction">
            <i class="fa-solid fa-heart"></i>
            <span>0</span>
        </div>
    `;
    contentPopup.appendChild(contentReactionPopup);

    // Obtener el contenedor de publicaciones y agregar el nuevo popup al principio
    const publicacionesContainer = document.getElementById("publicaciones-container");
    publicacionesContainer.prepend(publishingPopup);
}

// Función para restablecer los valores
function restablecerValores() {
    // Cierra el popup 'popup_create' al restablecer los valores
    closePopup("popup_create");
    // Obtén todos los campos y restablece sus valores a vacío
    document.getElementById("contentTextarea").value = "";
    document.getElementById("file-upload").value = "";
    // Oculta el contenido de imagen estableciendo su estilo de visualización a "none"
    document.getElementById("imageContent").style.display = "none";
    // Oculta el elemento con el id 'file-image' añadiendo la clase 'hidden'
    document.getElementById('file-image').classList.add("hidden");
    // Muestra el elemento con el id 'start' eliminando la clase 'hidden'
    document.getElementById('start').classList.remove("hidden");
    // Restablece el origen de la imagen en el formulario con el id 'file-upload-form'
    document.getElementById("file-image").src = "";
}

// Función para abrir el popup con la publicación
function openPublishing(publicacionId) {
    // Buscamos el elemento con la clase "popup" que tiene un atributo "data-publicacion-id" igual a "publicacionId"
    var popup = document.querySelector(`.popup[data-publicacion-id="${publicacionId}"]`);
    if (popup) {
        // Si se encuentra el popup, lo hacemos visible y le añadimos la clase "active"
        popup.style.display = "flex";
        popup.classList.add("active");
    }
}

// Función para cerrar el popup con la publicación
function closePopupPublishing() {
    // Buscamos el elemento con la clase "popup active" que tiene la clase "active"
    var popup = document.querySelector('.popup.active');
    if (popup) {
        // Si se encuentra un popup activo, lo ocultamos y eliminamos la clase "active"
        popup.style.display = "none";
        popup.classList.remove('active');
    }
}

// Agregar eventos y controladores de clic
document.addEventListener("DOMContentLoaded", function () {

    // Verificar si hay un fragmento en la URL
    if (window.location.hash === "#crear") {
        // Activar el controlador al abrir el popup "popup_create" si el fragmento es "#crear"
        openPopup("popup_create");
    }

    // Evento al hacer clic en el elemento con la clase "create__new-content" para abrir el popup "popup_create"
    const createNewContent = document.querySelector(".create__new-content");
    createNewContent.addEventListener("click", function () {
        openPopup("popup_create");
    });

    // Evento al hacer clic en el botón con el id "crear" para abrir el popup "popup_create"
    const btnCrear = document.getElementById("crear");
    btnCrear.addEventListener("click", function () {
        openPopup("popup_create");
    });

    // Evento al hacer clic en el botón con el id "showAllUsers" para abrir el popup "show_sugerencias"
    const btnShowAllUsers = document.getElementById("showAllUsers");
    btnShowAllUsers.addEventListener("click", function () {
        openPopup("show_sugerencias");
    });

    // Evento al hacer clic en el elemento con la clase "icons__new-image" para abrir el popup "popup_create" y alternar el contenido de la imagen
    const createNewContentTwo = document.querySelector(".icons__new-image");
    createNewContentTwo.addEventListener("click", function () {
        openPopup("popup_create");
        toggleImageContent();
    });

    // Evento al hacer clic en el botón con el id "close_popup_create" para cerrar el popup "popup_create"
    const closeButtonCreate = document.getElementById("close_popup_create");
    if (closeButtonCreate) {
        closeButtonCreate.addEventListener("click", function () {
            closePopup("popup_create");
        });
    }

    // Evento al hacer clic en el botón con el id "close_suggestions" para cerrar el popup "show_sugerencias"
    const closeButtonSuggestions = document.getElementById("close_suggestions");
    if (closeButtonSuggestions) {
        closeButtonSuggestions.addEventListener("click", function () {
            closePopup("show_sugerencias");
        });
    }

    // Evento al hacer clic en el elemento con el id "showImageContent" para alternar el contenido de la imagen
    const showImageContent = document.getElementById("showImageContent");
    if (showImageContent) {
        showImageContent.addEventListener("click", toggleImageContent);
    }

    // Obtener referencias a los elementos del textarea y el archivo de entrada
    const textarea = document.getElementById("contentTextarea");
    const fileInput = document.getElementById("file-upload");

    // Evento de entrada en el textarea y cambio en el archivo de entrada para verificar el contenido
    textarea.addEventListener("input", checkContent);
    fileInput.addEventListener("change", checkContent);

    // Evento al hacer clic en el contenedor de publicaciones para abrir el popup correspondiente
    const publicacionesContainer = document.getElementById("publicaciones-container");
    publicacionesContainer.addEventListener('click', function (event) {
        // Buscar el elemento más cercano con la clase "main-publishing"
        const element = event.target.closest('.main-publishing');
        if (element) {
            // Obtener el valor del atributo "data-publicacion-id" del elemento
            var publicacionId = element.getAttribute('data-publicacion-id');
            // Llamar a la función openPublishing para abrir el popup correspondiente
            openPublishing(publicacionId);
        }
        // Buscar el botón con el id "close_popup_publishing" dentro de la lista de botones con la clase "close-button"
        const btnClosePublishingList = document.querySelectorAll(".close-button");
        btnClosePublishingList.forEach(function (btnClosePublishing) {
            btnClosePublishing.addEventListener("click", function () {
                // Obtener el elemento "popup-publishing" relativo al botón y cerrarlo
                const elementToClose = btnClosePublishing.closest(".popup.active");
                if (elementToClose) {
                    closePopupPublishing();
                }
            });
        });
    });


    //Manejo de solioitudes
    // Logout Button
    // Obtener referencia al botón de cierre de sesión con el id "logoutButton"
    var logoutButton = document.getElementById("logoutButton");
    // Verificar si se encontró el botón de cierre de sesión
    if (logoutButton) {
        // Agregar un evento de clic al botón de cierre de sesión
        logoutButton.addEventListener("click", function () {
            // Crear una nueva instancia de XMLHttpRequest
            var xhr = new XMLHttpRequest();
            // Configurar la solicitud GET para cerrar sesión, utilizando la URL '../Clases/usuario.php?logout'
            xhr.open("GET", '../Clases/usuario.php?logout', true);
            // Definir la función que manejará los cambios de estado de la solicitud
            xhr.onreadystatechange = function () {
                // Verificar si la solicitud se ha completado (readyState: 4) y si la respuesta fue exitosa (status: 200)
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Redireccionar a la página de inicio '../index.php' después de cerrar sesión
                    window.location.href = '../index.php';
                }
            };
            // Enviar la solicitud
            xhr.send();
        });
    }


    // Publish Button
    // Agregar un evento de clic al botón con el id "publishBtn"
    document.getElementById("publishBtn").addEventListener("click", function () {
        // Obtener el contenido del textarea y el archivo seleccionado
        var contenido = document.getElementById("contentTextarea").value;
        var archivo = document.getElementById("file-upload").files[0];
        // Verificar si al menos uno de los dos campos está presente
        if (contenido || archivo) {
            // Crear un objeto FormData para enviar los datos
            var formData = new FormData();
            formData.append('texto', contenido);
            formData.append('imagen_ruta', archivo);
            // Crear una solicitud XMLHttpRequest
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '../Clases/publicacion.php?subirDatos', true);
            // Definir la función que manejará los cambios de estado de la solicitud
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) { // Verificar si la solicitud ha sido completada
                    if (xhr.status === 200) { // Verificar si la respuesta fue exitosa
                        // Manejar la respuesta del servidor
                        var response = JSON.parse(xhr.responseText);
                        console.log(response);
                        if (response.success) {
                            // La publicación se guardó con éxito
                            console.log(response.message);
                            // Restablecer valores de campos y elementos relacionados
                            restablecerValores();
                            // Crear dinámicamente una nueva publicación en el feed principal
                            createNewPublication(response);
                            // Crear dinámicamente un nuevo popup de publicación
                            createPublicationPopup(response);
                        } else {
                            // Hubo un error al guardar la publicación
                            console.log(response.message);
                        }
                    } else {
                        // Error en la solicitud AJAX
                        console.log('Error en la solicitud AJAX');
                    }
                }
            };
            // Enviar la solicitud con los datos del formulario FormData
            xhr.send(formData);
        } else {
            // Mostrar un mensaje de error al usuario o realizar alguna acción adecuada
            console.log('Debes proporcionar al menos un campo (texto o imagen)');
        }
    });

    // Follow Buttons
    // Obtener todos los botones con la clase "follow-button"
    var followButtons = document.querySelectorAll(".follow-button");
    // Iterar sobre cada botón obtenido
    followButtons.forEach(function (button) {
        // Agregar un evento de clic a cada botón
        button.addEventListener("click", function () {
            // Obtener el ID de usuario asociado al botón
            var usuario_id = button.getAttribute("data-usuario-id");
            // Crear una nueva instancia de XMLHttpRequest
            var xhr = new XMLHttpRequest();
            // Configurar la solicitud POST para seguir al usuario, utilizando la URL '../Clases/seguidor-seguido.php?Follow'
            xhr.open("POST", '../Clases/seguidor-seguido.php?Follow', true);
            // Establecer la cabecera Content-Type para la solicitud
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            // Definir la función que manejará los cambios de estado de la solicitud
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) { // Verificar si la solicitud ha sido completada
                    if (xhr.status === 200) { // Verificar si la respuesta fue exitosa
                        // Manejar la respuesta del servidor
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Cambiar el aspecto del botón de seguir llamando a la función followBtn
                            followBtn(button);
                            console.log(response.message);
                        } else {
                            // Mostrar un mensaje de error si no se pudo seguir al usuario
                            console.log(response.message);
                        }
                    } else {
                        // Mostrar un mensaje de error en la consola si la solicitud no fue exitosa
                        console.error("Error en la solicitud AJAX: " + xhr.statusText);
                    }
                }
            };
            // Enviar la solicitud con el ID del usuario como datos
            xhr.send("usuario_id=" + usuario_id);
        });
    });


    // Follow Buttons Suggestions
    // Función para manejar el seguimiento (Follow/Unfollow) de un usuario
    function toggleFollow(button) {
        // Obtiene el ID del usuario desde el atributo "data-id" del botón
        var usuario_id = button.getAttribute("data-id");
        // Determina si se debe realizar un seguimiento o dejar de seguir
        var action = button.classList.contains("btnFollow") ? "Follow" : "Unfollow";
        // Crea una solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", '../Clases/seguidor-seguido.php?' + action, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        // Maneja la respuesta de la solicitud
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) { // Verificar si la solicitud ha sido completada
                if (xhr.status === 200) { // Verificar si la respuesta fue exitosa
                    // Parsea la respuesta JSON del servidor
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Modifica el aspecto y el texto del botón según la acción realizada
                        if (action === "Follow") {
                            // Si se estaba siguiendo al usuario, cambia el botón y el texto a "Dejar de seguir"
                            button.classList.remove("btnFollow");
                            button.classList.add("btnUnfollow");
                            button.textContent = "Dejar de seguir";
                        } else {
                            // Si no se estaba siguiendo al usuario, cambia el botón y el texto a "Seguir"
                            button.classList.remove("btnUnfollow");
                            button.classList.add("btnFollow");
                            button.textContent = "Seguir";
                        }
                        console.log(response.message);
                    } else {
                        // Muestra un mensaje de error si no se pudo cambiar el estado de seguimiento
                        console.log("Error al cambiar el estado de seguimiento");
                    }
                } else {
                    // Muestra un mensaje de error en la consola si la solicitud no fue exitosa
                    console.error("Error en la solicitud AJAX: " + xhr.statusText);
                }
            }
        };
        // Envía el ID del usuario al servidor como datos de la solicitud
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
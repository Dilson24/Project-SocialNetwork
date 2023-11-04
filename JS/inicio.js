// Función para abrir un popup
function openPopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = "flex";
        popup.classList.add("active");
    }
}

// Función para cerrar un popup
function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = "none";
        popup.classList.remove("active");
    }
}

// Función para mostrar u ocultar el contenido de imagen
function toggleImageContent() {
    const imageContent = document.getElementById("imageContent");
    if (imageContent) {
        if (imageContent.style.display === "none" || imageContent.style.display === "") {
            imageContent.style.display = "flex";
        } else {
            imageContent.style.display = "none";
        }
    }
}

// Función para comprobar el contenido del textarea y el archivo de entrada
function checkContent() {
    const textarea = document.getElementById("contentTextarea");
    const fileInput = document.getElementById("file-upload");
    const publishButton = document.getElementById("publishBtn");

    if (textarea.value.trim() !== "" || fileInput.files.length > 0) {
        publishButton.classList.add("active");
    } else {
        publishButton.classList.remove("active");
    }
}

// Función para crear dinámicamente una nueva publicación
function createNewPublication(response) {
    const nuevaPublicacion = document.createElement("div");
    nuevaPublicacion.className = "main-publishing";
    nuevaPublicacion.setAttribute("data-publicacion-id", response.publicacion_id);

    // Crear contenido de usuario
    const contentUser = document.createElement("div");
    contentUser.className = "main-publishing__users";
    contentUser.innerHTML = `
        <div class="users-info">
            <a href="../Vistas/perfil.php"><img src="${response.user_image}" alt="Img profile"></a>
            <a href="../Vistas/perfil.php">${response.user_name}</a>
        </div>
    `;
    nuevaPublicacion.appendChild(contentUser);

    // Agregar contenido de texto si existe
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

    // Agregar contenido de imagen si existe
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

    // Agregar contenido de reacción
    const contentReaction = document.createElement("div");
    contentReaction.className = "main-publishing__content-reation";
    contentReaction.innerHTML = `
        <div class="contet-reaction">
            <i class="fa-solid fa-heart"></i>
            <span>0</span>
        </div>
    `;
    nuevaPublicacion.appendChild(contentReaction);

    // Agregar la nueva publicación al contenedor
    const publicacionesContainer = document.getElementById("publicaciones-container");
    publicacionesContainer.prepend(nuevaPublicacion);
}

// Función para crear dinámicamente un nuevo popup de publicación
function createPublicationPopup(response) {
    const publishingPopup = document.createElement("div");
    publishingPopup.className = "popup";
    publishingPopup.id = "popup_publishing";
    publishingPopup.setAttribute("data-publicacion-id", response.publicacion_id);

    // Crear contenido del popup
    const contentPopup = document.createElement("div");
    contentPopup.className = "popup-content";
    publishingPopup.appendChild(contentPopup);

    // Botón cerrar popup
    const contentPopupBtnClose = document.createElement("span");
    contentPopupBtnClose.className = "close-button";
    contentPopupBtnClose.id = "close_popup_publishing";
    contentPopupBtnClose.innerHTML = `<i class="fa-solid fa-xmark"></i>`;
    contentPopup.appendChild(contentPopupBtnClose);

    // Agregar el contenido de usuario al popup
    const contentPopupUser = document.createElement("div");
    contentPopupUser.className = "main-publishing__users";
    contentPopupUser.innerHTML = `
        <div class="users-info">
            <a href="../Vistas/perfil.php"><img src="${response.user_image}" alt="Img profile"></a>
            <a href="../Vistas/perfil.php">${response.user_name}</a>
        </div>
    `;
    contentPopup.appendChild(contentPopupUser);

    // Agregar contenido de texto si existe
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

    // Agregar contenido de imagen si existe
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

    // Agregar contenido de reacción
    const contentReactionPopup = document.createElement("div");
    contentReactionPopup.className = "main-publishing__content-reation";
    contentReactionPopup.innerHTML = `
        <div class="contet-reaction">
            <i class="fa-solid fa-heart"></i>
            <span>0</span>
        </div>
    `;
    contentPopup.appendChild(contentReactionPopup);

    // Agregar el nuevo popup al contenedor
    const publicacionesContainer = document.getElementById("publicaciones-container");
    publicacionesContainer.prepend(publishingPopup);
}

// Funcion para restablecer los valores¨
function restablecerValores() {
    // Cierra el popup
    closePopup("popup_create");
    // Restablece los campos de entrada
    document.getElementById("contentTextarea").value = "";
    document.getElementById("file-upload").value = "";
    document.getElementById("imageContent").style.display = "none";
    // Ocultar elemento con el id 'file-image'
    document.getElementById('file-image').classList.add("hidden");
    // Mostrar elemento con el id 'start'
    document.getElementById('start').classList.remove("hidden");
    // Restablecer el formulario con el id 'file-upload-form'
    document.getElementById("file-image").src = "";
}


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

    $("#publishBtn").click(function () {
        // Obtén el contenido del textarea y el archivo seleccionado
        var contenido = $("#contentTextarea").val();
        var archivo = $("#file-upload")[0].files[0];

        // Verifica si al menos uno de los dos campos está presente
        if (contenido || archivo) {
            // Crea un objeto FormData para enviar los datos
            var formData = new FormData();
            formData.append('texto', contenido);
            formData.append('imagen_ruta', archivo);

            // Realiza la solicitud AJAX
            $.ajax({
                url: '../Clases/publicacion.php?subirDatos', // Archivo de destino
                type: 'POST',
                data: formData,
                processData: false, // Evita que jQuery procese los datos
                contentType: false, // Evita que jQuery establezca el tipo de contenido
                dataType: 'json', // Especificamos que esperamos una respuesta JSON
                success: function (response) {
                    // Manejar la respuesta del servidor
                    console.log(response);
                    if (response.success) {
                        // La publicación se guardó con éxito
                        console.log(response.message);
                        restablecerValores();
                        createNewPublication(response);
                        createPublicationPopup(response);

                        function openPublishing(publicacionId) {
                            const popup = document.querySelector(`.popup[data-publicacion-id="${publicacionId}"]`);
                            if (popup) {
                                popup.style.display = "flex";
                                popup.classList.add("active");
                            }
                        }
                        // Agregar un controlador de clic a la publicación
                        $('.main-publishing').on('click', function () {
                            var publicacionID = $(this).data('publicacion-id');
                            openPublishing(publicacionID); // Pasa solo el publicacionID, no el selector completo
                        });
                        // Agregar un controlador de clic al icono de cierre general
                        $('#close_popup_publishing').on('click', function () {
                            // Cerrar todos los popups
                            $('.popup.active').each(function () {
                                $(this).hide(); // Otra opción: $(this).css('display', 'none');
                                $(this).removeClass('active');
                            });
                        });
                    } else {
                        // Hubo un error al guardar la publicación
                        console.log(response.message);
                    }
                },
                error: function () {
                    console.log('Error en la solicitud AJAX');
                }
            });
        } else {
            // Muestra un mensaje de error al usuario o realiza alguna acción adecuada
            console.log('Debes proporcionar al menos un campo (texto o imagen)');
        }
    });
});

// Agregar eventos y controladores de clic
document.addEventListener("DOMContentLoaded", function () {
    const createNewContent = document.querySelector(".create__new-content");
    createNewContent.addEventListener("click", function () {
        openPopup("popup_create");
    });

    const createNewContentTwo = document.querySelector(".icons__new-image");
    createNewContentTwo.addEventListener("click", function () {
        openPopup("popup_create");
        toggleImageContent();
    });

    const closeButtonCreate = document.getElementById("close_popup_create");
    if (closeButtonCreate) {
        closeButtonCreate.addEventListener("click", function () {
            closePopup("popup_create");
        });
    }

    const showImageContent = document.getElementById("showImageContent");
    if (showImageContent) {
        showImageContent.addEventListener("click", toggleImageContent);
    }

    const textarea = document.getElementById("contentTextarea");
    const fileInput = document.getElementById("file-upload");
    textarea.addEventListener("input", checkContent);
    fileInput.addEventListener("change", checkContent);

});

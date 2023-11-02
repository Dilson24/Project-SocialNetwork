// EVENTOS POPUPS
function openPopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = "block";
        popup.classList.add("active");
    }
}
var createNewContent = document.querySelector(".create__new-content");
createNewContent.addEventListener("click", function () {
    openPopup("popup_create");
});

var createNewContentTwo = document.querySelector(".icons__new-image");
createNewContentTwo.addEventListener("click", function () {
    openPopup("popup_create");
    imageContent.style.display = "flex";
});

var mainPublishing = document.querySelector(".main-publishing");
mainPublishing.addEventListener("click", function () {
    openPopup("popup_publishing");
});


function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = "none";
        popup.classList.remove("active");
    }
}

const closeButtonCreate = document.getElementById("close_popup_create");
if (closeButtonCreate) {
    closeButtonCreate.addEventListener("click", function () {
        closePopup("popup_create");
    });
}

const closeButtonPublishing = document.getElementById("close_popup_publishing");
if (closeButtonPublishing) {
    closeButtonPublishing.addEventListener("click", function () {
        closePopup("popup_publishing");
    });
}

//POPUP CREATE

// Agregar un event listener al div con la clase icons__new-image
const showImageContent = document.getElementById("showImageContent");
const imageContent = document.getElementById("imageContent");

showImageContent.addEventListener("click", function () {
    // Cambiar la visibilidad de la clase create__content-img
    if (imageContent.style.display === "none" || imageContent.style.display === "") {
        imageContent.style.display = "flex";
    } else {
        imageContent.style.display = "none";
    }
});


const textarea = document.getElementById("contentTextarea");
const fileInput = document.getElementById("file-upload");
const publishButton = document.getElementById("publishBtn");

// Agrega un evento de escucha para el evento 'input' en el textarea y el input de tipo file
textarea.addEventListener("input", checkContent);
fileInput.addEventListener("change", checkContent);

function checkContent() {
    // Verifica si el textarea contiene contenido o si se seleccionó un archivo en el input de tipo file
    if (textarea.value.trim() !== "" || fileInput.files.length > 0) {
        // Si hay contenido, agrega la clase 'active' al botón
        publishButton.classList.add("active");
    } else {
        // Si no hay contenido, elimina la clase 'active' del botón
        publishButton.classList.remove("active");
    }
}

// Manejo de solicitudes
$(document).ready(function () {
    /*BTN LOG OUT*/
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
    $("#publishBtn").click(function() {
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
                success: function(response) {
                    // Maneja la respuesta del servidor aquí
                    console.log(response);
                    if (response.success) {
                        // La publicación se guardó con éxito
                        console.log(response.message);
                        // Puedes realizar acciones adicionales si es necesario
                    } else {
                        // Hubo un error al guardar la publicación
                        console.log(response.message);
                    }
                },
                error: function() {
                    console.log('Error en la solicitud AJAX');
                }
            });
        } else {
            // Muestra un mensaje de error al usuario o realiza alguna acción adecuada
            console.log('Debes proporcionar al menos un campo (texto o imagen)');
        }
    });
    
    
    
    
});

// File Upload
function ekUpload() {
    function Init() {
        console.log("Upload Initialised");
        var fileSelect = document.getElementById('file-upload'),
            fileDrag = document.getElementById('file-drag');
        // submitButton = document.getElementById('submit-button');
        fileSelect.addEventListener('change', fileSelectHandler, false);
        // Is XHR2 available?
        var xhr = new XMLHttpRequest();
        if (xhr.upload) {
            // File Drop
            fileDrag.addEventListener('dragover', fileDragHover, false);
            fileDrag.addEventListener('dragleave', fileDragHover, false);
            fileDrag.addEventListener('drop', fileSelectHandler, false);
        }
    }

    function fileDragHover(e) {
        var fileDrag = document.getElementById('file-drag');
        e.stopPropagation();
        e.preventDefault();
        fileDrag.className = (e.type === 'dragover' ? 'hover' : 'modal-body file-upload');
    }

    function fileSelectHandler(e) {
        // Fetch FileList object
        var files = e.target.files || e.dataTransfer.files;
        // Cancel event and hover styling
        fileDragHover(e);
        // Process all File objects
        for (var i = 0, f; f = files[i]; i++) {
            parseFile(f);
        }
    }

    // Output
    function output(msg) {
        alert(msg);
    }

    function parseFile(file) {
        var imageName = file.name;
        var isGood = (/\.(?=gif|jpg|png|jpeg)/gi).test(imageName);
        if (isGood) {
            document.getElementById('start').classList.add("hidden");
            document.getElementById('file-image').classList.remove("hidden");
            document.getElementById('file-image').src = URL.createObjectURL(file);
        }
        else {
            document.getElementById('file-image').classList.add("hidden");
            document.getElementById('start').classList.remove("hidden");
            document.getElementById("file-upload-form").reset();
        }
    }

    function uploadFile(file) {
        var xhr = new XMLHttpRequest(),
            fileSizeLimit = 1024; // In MB
        if (xhr.upload) {
            // Check if file is less than x MB
            if (file.size <= fileSizeLimit * 1024 * 1024) {
                xhr.onreadystatechange = function (e) {
                    if (xhr.readyState == 4) {
                    }
                };

                // Start upload
                xhr.open('POST', document.getElementById('file-upload-form').action, true);
                xhr.setRequestHeader('X-File-Name', file.name);
                xhr.setRequestHeader('X-File-Size', file.size);
                xhr.setRequestHeader('Content-Type', 'multipart/form-data');
                xhr.send(file);
            } else {
                output('Please upload a smaller file (< ' + fileSizeLimit + ' MB).');
            }
        }
    }
    // Check for the various File API support.
    if (window.File && window.FileList && window.FileReader) {
        Init();
    } else {
        document.getElementById('file-drag').style.display = 'none';
    }
}
ekUpload();
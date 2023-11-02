// Función para abrir el popup
function openPopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = "block";
        popup.classList.add("active"); // Agregar la clase "active" al abrir
    }
}
// Función para cerrar el popup activo
function closePopup() {
    const activePopup = document.querySelector(".popup.active");
    if (activePopup) {
        activePopup.style.display = "none";
        activePopup.classList.remove("active"); // Quitar la clase "active" al cerrar
    }
}

const closeButton = document.getElementById("close_popup");
if (closeButton) {
    closeButton.addEventListener("click", function () {
        closePopup();
    });
}

// Agrega un evento de clic al contenedor main-publishing
var mainPublishing = document.querySelector(".main-publishing");
mainPublishing.addEventListener("click", function () {
    openPopup("popup_publishing");
});

// Agrega un evento de clic al contenedor create__new-content
var createNewContent = document.querySelector(".create__new-content");
createNewContent.addEventListener("click", function () {
    openPopup("popup_create");
});


const textarea = document.getElementById("contentTextarea");
const publishButton = document.getElementById("publishBtn");

// Agrega un evento de escucha para el evento 'input' en el textarea
textarea.addEventListener("input", function () {
    // Verifica si el textarea contiene contenido
    if (textarea.value.trim() !== "") {
        // Si hay contenido, agrega la clase 'active' al botón
        publishButton.classList.add("active");
    } else {
        // Si no hay contenido, elimina la clase 'active' del botón
        publishButton.classList.remove("active");
    }
});

/*BTN LOG OUT*/
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
});

// File Upload
// 
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
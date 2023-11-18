// Función principal para la carga de archivos
function ekUpload() {
        // Inicialización de la carga de archivos
    function Init() {
        console.log("Upload Initialised"); // Mensaje de inicio en la consola
        var fileSelect = document.getElementById('file-upload'), // Selector de archivo de entrada
            fileDrag = document.getElementById('file-drag'); // Área de arrastrar y soltar archivos
        // Verificar si XHR2 está disponible
        var xhr = new XMLHttpRequest();
        if (xhr.upload) {
            // Configurar eventos de arrastrar y soltar archivos
            fileDrag.addEventListener('dragover', fileDragHover, false);
            fileDrag.addEventListener('dragleave', fileDragHover, false);
            fileDrag.addEventListener('drop', fileSelectHandler, false);
        }
        // Configurar el manejador de eventos para la selección de archivos
        fileSelect.addEventListener('change', fileSelectHandler, false);
    }
    // Manejador de eventos para la apariencia durante el arrastre de archivos
    function fileDragHover(e) {
        var fileDrag = document.getElementById('file-drag');
        e.stopPropagation();
        e.preventDefault();
        fileDrag.className = (e.type === 'dragover' ? 'hover' : 'modal-body file-upload');
    }
    // Manejador de eventos para la selección de archivos
    function fileSelectHandler(e) {
        // Obtener el objeto FileList
        var files = e.target.files || e.dataTransfer.files;
        // Cancelar el evento y cambiar el estilo de apariencia
        fileDragHover(e);
        // Procesar todos los objetos File
        for (var i = 0, f; f = files[i]; i++) {
            parseFile(f);
        }
    }
    // Función para mostrar mensajes de salida
    function output(msg) {
        alert(msg);
    }
    // Función para analizar y mostrar la imagen del archivo
    function parseFile(file) {
        var imageName = file.name;
        var isGood = (/\.(?=gif|jpg|png|jpeg)/gi).test(imageName);
        if (isGood) {
            // Ocultar el mensaje inicial y mostrar la imagen seleccionada
            document.getElementById('start').classList.add("hidden");
            document.getElementById('file-image').classList.remove("hidden");
            document.getElementById('file-image').src = URL.createObjectURL(file);
        }
        else {
            // Ocultar la imagen y mostrar el mensaje inicial
            document.getElementById('file-image').classList.add("hidden");
            document.getElementById('start').classList.remove("hidden");
            document.getElementById("file-image").src = "";
        }
    }
    // Función para subir el archivo al servidor
    function uploadFile(file) {
        var xhr = new XMLHttpRequest(),
            fileSizeLimit = 1024; // Límite de tamaño del archivo en MB
        if (xhr.upload) {
            // Verificar si el archivo es menor que x MB
            if (file.size <= fileSizeLimit * 1024 * 1024) {
                xhr.onreadystatechange = function (e) {
                    if (xhr.readyState == 4) {
                        // Lógica adicional después de completar la carga
                    }
                };
                // Iniciar la carga
                xhr.open('POST', document.getElementById('file-upload-form').action, true);
                xhr.setRequestHeader('X-File-Name', file.name);
                xhr.setRequestHeader('X-File-Size', file.size);
                xhr.setRequestHeader('Content-Type', 'multipart/form-data');
                xhr.send(file);
            } else {
                // Mostrar mensaje si el archivo es demasiado grande
                output('Por favor, carga un archivo más pequeño (< ' + fileSizeLimit + ' MB).');
            }
        }
    }
    // Verificar el soporte de las distintas API de archivos
    if (window.File && window.FileList && window.FileReader) {
        Init();
    } else {
        // Ocultar la interfaz de arrastrar y soltar si no es compatible
        document.getElementById('file-drag').style.display = 'none';
    }
}
// Iniciar la función de carga de archivos
ekUpload();
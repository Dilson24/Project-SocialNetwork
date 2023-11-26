<?php
// Incluir el archivo que contiene la lógica de la base de datos y la clase Database
require_once('../db.php');
// Incluir el archivo con la clase 'Perfil'
require_once('perfil.php');
// Definir la clase 'Publicacion'
class Publicacion
{
    private $db;
    private $connection;
    // Constructor de la clase
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
        $this->connection->set_charset("utf8mb4");
    }

    // Método que crea el HTML para la interfaz de creación de publicaciones.
    public function crearPublicacion()
    {
        // Instancia un objeto Perfil para obtener datos del usuario actual.
        $perfil = new Perfil();
        $userData = $perfil->obtenerDatosUsuario();
        $user_name = $userData['name'];
        $user_image = $userData['imagen_perfil'];
        // Construye la cadena HTML para la interfaz de creación de publicaciones.
        $createHTML = '';
        $createHTML .= '<div class="popup-content">';
        $createHTML .= '<h2 class="create__title">Crear publicación</h2>';
        $createHTML .= '<span class="close-button" id="close_popup_create"><i class="fa-solid fa-xmark"></i></span>';
        $createHTML .= '<div class="create__line"></div>';
        $createHTML .= '<div class="main-publishing__users">';
        $createHTML .= '<div class="users-info">';
        $createHTML .= '<a href="link-perfil-user"><img src="' . $user_image . '" alt="Img profile"></a>';
        $createHTML .= '<a href="link-perfil-user">' . $user_name . '</a>';
        $createHTML .= '</div>';
        $createHTML .= '</div>';
        $createHTML .= '<div class="create__content-text">';
        $createHTML .= '<div class="create__text">';
        $createHTML .= '<textarea placeholder="¿Algo que quieras compartir,' . $user_name . '?" id="contentTextarea"></textarea>';
        $createHTML .= '</div>';
        $createHTML .= '</div>';
        $createHTML .= '<div class="create__content-img" id="imageContent">';
        $createHTML .= '<h3 class="create__title">Añade fotos o imágenes</h3>';
        $createHTML .= '<div id="file-upload-form" class="uploader">';
        $createHTML .= '<input id="file-upload" type="file" name="fileUpload" accept="image/*" />';
        $createHTML .= '<label for="file-upload" id="file-drag">';
        $createHTML .= '<img id="file-image" src="#" alt="Preview" class="hidden">';
        $createHTML .= '<div id="start">';
        $createHTML .= '<i class="fa fa-download" aria-hidden="true"></i>';
        $createHTML .= '<div>Selecciona un archivo o arrástralo aquí</div>';
        $createHTML .= '<div id="notimage" class="hidden">Por favor selecciona una imagen</div>';
        $createHTML .= '<span id="file-upload-btn" class="btn btn-primary">Selecciona un archivo</span>';
        $createHTML .= '</div>';
        $createHTML .= '</label>';
        $createHTML .= '</div>';
        $createHTML .= '</div>';
        $createHTML .= '<div class="create__content-btn-image" id="showImageContent">';
        $createHTML .= '<div class="icons__new-image"><i class="fa-solid fa-file-image"></i><span>Imagen</span></div>';
        $createHTML .= '</div>';
        $createHTML .= '<div class="create__content-btn-publish">';
        $createHTML .= '<div class="icons__btn-publish"><button id="publishBtn">Publicar</button></div>';
        $createHTML .= '</div>';
        $createHTML .= '</div>';
        // Retorna la cadena HTML generada.
        return $createHTML;
    }
    // Método para procesar y subir los datos de una publicación
    public function subirDatos()
    {
        // Verifica si la solicitud es de tipo POST.
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Asegúrate de que la sesión esté iniciada antes de intentar acceder a $_SESSION.
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            // Obtén el ID del usuario de la sesión (asegúrate de que la sesión esté iniciada).
            $usuario_id = $_SESSION['user_id'];
            // Obtiene el contenido de texto de la publicación, si está presente en la solicitud.
            $texto = isset($_POST['texto']) ? $_POST['texto'] : "";
            // Verifica si se ha enviado una imagen junto con la publicación.
            if (isset($_FILES['imagen_ruta'])) {
                // Invoca el método para procesar la subida de la imagen.
                $this->subirImagen($usuario_id, $texto);
            } else {
                // Si no hay imagen, invoca el método para guardar la publicación solo con texto.
                $this->guardarPublicacion($usuario_id, $texto);
            }
        }
    }
    // Método procesar la imagen de una publicación
    private function subirImagen($usuario_id, $texto)
    {
        // Directorio donde se almacenarán las imágenes del usuario.
        $directorio_usuario = '../Img/Perfil_ID__' . $usuario_id . '/';
        // Verifica si el directorio del usuario ya existe, si no, créalo.
        if (!file_exists($directorio_usuario)) {
            mkdir($directorio_usuario, 0777, true);
        }
        // Obtiene información sobre la imagen cargada.
        $nombre_archivo_original = $_FILES['imagen_ruta']['name']; // Obtenemos el nombre original del archivo.
        $extension = pathinfo($nombre_archivo_original, PATHINFO_EXTENSION);
        $nombre_archivo = uniqid() . '.' . $extension; // Generamos un nombre único.
        $ruta_archivo = $directorio_usuario . $nombre_archivo;
        // Intenta mover la imagen cargada al directorio del usuario.
        if (move_uploaded_file($_FILES['imagen_ruta']['tmp_name'], $ruta_archivo)) {
            // Si la operación de subida es exitosa, invoca el método para guardar la publicación en la base de datos.
            return $this->guardarPublicacion($usuario_id, $texto, $ruta_archivo);
        } else {
            // Si hay algún error en la subida de la imagen, retorna un array indicando el fallo.
            return array('success' => false, 'message' => 'Error al subir la imagen');
        }
    }
    // Método para guardar una publicación en la base de datos
    private function guardarPublicacion($usuario_id, $texto, $imagen_ruta = null)
    {
        // Construye la consulta SQL para insertar la publicación en la base de datos.
        $querySubirDatos = "INSERT INTO publicaciones (usuario_id, texto, imagen_ruta, fecha_publicacion) VALUES (?, ?, ?, NOW())";
        // Si no se proporciona una ruta de imagen, ajusta la consulta SQL.
        if ($imagen_ruta === null) {
            $querySubirDatos = "INSERT INTO publicaciones (usuario_id, texto, fecha_publicacion) VALUES (?, ?, NOW())";
        }
        // Prepara la consulta SQL.
        $resultSubirDatos = $this->connection->prepare($querySubirDatos);
        // Verifica si la preparación de la consulta fue exitosa.
        if ($resultSubirDatos) {
            // Vincula los parámetros a la consulta preparada.
            if ($imagen_ruta === null) {
                $resultSubirDatos->bind_param("is", $usuario_id, $texto);
            } else {
                $resultSubirDatos->bind_param("iss", $usuario_id, $texto, $imagen_ruta);
            }
            // Inicializa variables para el resultado de la operación y detalles de la publicación.
            $success = false;
            $message = 'Error en la preparación de la sentencia SQL';
            $publicacion_id = null;
            // Ejecuta la consulta SQL.
            if ($resultSubirDatos->execute()) {
                $success = true;
                $message = 'Publicación guardada con éxito';
                $publicacion_id = $this->connection->insert_id; // Obtiene el ID de la publicación recién insertada.
            } else {
                $message = 'Error al guardar la publicación';
            }
            // Cierra la consulta preparada.
            $resultSubirDatos->close();
            // Instancia un objeto Perfil para obtener datos del usuario actual.
            $perfil = new Perfil();
            $userData = $perfil->obtenerDatosUsuario();
            $user_name = $userData['name'];
            $user_image = $userData['imagen_perfil'];
            // Imprime en formato JSON la información sobre la publicación y el usuario asociado.
            echo json_encode(
                array(
                    'success' => $success,
                    'message' => $message,
                    'user_name' => $user_name,
                    'user_image' => $user_image,
                    'publicacion_id' => $publicacion_id,
                    'texto' => $texto,
                    'imagen_ruta' => $imagen_ruta
                )
            );
        } else {
            // Si la preparación de la sentencia SQL falla, imprime un mensaje de error en formato JSON.
            echo json_encode(array('success' => false, 'message' => 'Error en la preparación de la sentencia SQL'));
        }
    }
    // Método para obtener el total de publicaciones del usuario
    public function obtenerTotalPublicaciones()
    {
        // Verifica si la sesión está iniciada antes de intentar acceder a $_SESSION.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Obtiene el ID del usuario de la sesión (asegúrate de que la sesión esté iniciada).
        $user_id = $_SESSION['user_id'];
        // Construye la consulta SQL para obtener el número total de publicaciones del usuario actual.
        $query = "SELECT COUNT(*) AS numero_publicaciones FROM publicaciones WHERE usuario_id = $user_id";
        // Ejecuta la consulta SQL.
        $result = $this->connection->query($query);
        // Verifica si la consulta fue exitosa.
        if ($result) {
            // Verifica si hay al menos una fila de resultado.
            if ($result->num_rows > 0) {
                // Obtiene el valor del número total de publicaciones.
                $row = $result->fetch_assoc();
                $numero_publicaciones = $row['numero_publicaciones'];
                return $numero_publicaciones;
            } else {
                // Maneja el caso en el que no hay publicaciones para el usuario actual.
                echo "Error: La consulta no devolvió resultados";
            }
        } else {
            // Maneja el caso en el que la consulta SQL falla.
            echo "Error: La consulta falló";
        }
    }
    // Método para obtener el total de publicaciones realizadas por un usuario específico.
    public function obtenerTotalPublicacionesPublic($profile_id)
    {
        // Construye la consulta SQL para obtener el número total de publicaciones del usuario especificado.
        $query = "SELECT COUNT(*) AS numero_publicaciones FROM publicaciones WHERE usuario_id = $profile_id";
        // Ejecuta la consulta SQL.
        $result = $this->connection->query($query);
        // Verifica si la consulta fue exitosa.
        if ($result) {
            // Verifica si hay al menos una fila de resultado.
            if ($result->num_rows > 0) {
                // Obtiene el valor del número total de publicaciones.
                $row = $result->fetch_assoc();
                $numero_publicaciones = $row['numero_publicaciones'];
                return $numero_publicaciones;
            } else {
                // Maneja el caso en el que no hay publicaciones para el usuario especificado.
                echo "Error: La consulta no devolvió resultados";
            }
        } else {
            // Maneja el caso en el que la consulta SQL falla.
            echo "Error: La consulta falló";
        }
    }
    // Método qué genera y muestra el HTML de una nueva publicación con la información proporcionada.
    public function publication($user_name, $user_image, $publicacion_id, $texto, $imagen_ruta)
    {
        // Inicia la estructura HTML para la nueva publicación con el ID de la publicación.
        $nuevaPublicacion = '<div class="main-publishing" data-publicacion-id="' . $publicacion_id . '">';
        // Crear contenido de usuario
        $contentUser = '<div class="main-publishing__users">
            <div class="users-info">
                <a href="../Vistas/perfil.php"><img src="' . $user_image . '" alt="Img profile"></a>
                <a href="../Vistas/perfil.php">' . $user_name . '</a>
            </div>
        </div>';
        $nuevaPublicacion .= $contentUser;
        // Agregar contenido de texto si existe
        if (!empty($texto)) {
            $contentText = '<div class="main-publishing__content-text">
            <div class="content-text">
                <p>' . $texto . '</p>
            </div>
        </div>';
            $nuevaPublicacion .= $contentText;
        }
        // Agregar contenido de imagen si existe
        if (!empty($imagen_ruta)) {
            $contentImg = '<div class="main-publishing__content-img">
            <div class="content-img">
                <img src="' . $imagen_ruta . '" alt="publishing img">
            </div>
        </div>';
            $nuevaPublicacion .= $contentImg;
        }
        // Agregar contenido de reacción
        $contentReaction = '<div class="main-publishing__content-reation">
            <div class="contet-reaction">
                <i class="fa-solid fa-heart"></i>
                <span>0</span>
            </div>
        </div>';
        $nuevaPublicacion .= $contentReaction;
        // Cerrar la estructura HTML de la nueva publicación.
        $nuevaPublicacion .= '</div>';
        // Imprime el HTML de la nueva publicación.
        echo $nuevaPublicacion;
    }
    // Método qué genera y muestra el contenido HTML de una publicación dentro de un popup con la información proporcionada.
    public function publicationPopup($user_name, $user_image, $publicacion_id, $texto, $imagen_ruta)
    {
        // Contenedor principal del popup
        $contentPopup = '<div class="popup-content">';
        // Elemento 1 del contenedor (Botón de cierre)
        $btnClose = '<span class="close-button" id="close_popup_publishing"><i class="fa-solid fa-xmark"></i></span>';
        $contentPopup .= $btnClose;
        // Elemento 2 del contenedor (Información del usuario)
        $contentUser = '<div class="main-publishing__users">
            <div class="users-info">
                <a href="../Vistas/perfil.php"><img src="' . $user_image . '" alt="Img profile"></a>
                <a href="../Vistas/perfil.php">' . $user_name . '</a>
            </div>
        </div>';
        $contentPopup .= $contentUser;
        // Elemento 3 del contenedor (Contenido de texto si existe)
        if (!empty($texto)) {
            $contentTextPopup = '<div class="main-publishing__content-text">
            <div class="content-text">
                <p>' . $texto . '</p>
            </div>
        </div>';
            $contentPopup .= $contentTextPopup;
        }
        // Elemento 4 del contenedor (Contenido de imagen si existe)
        if (!empty($imagen_ruta)) {
            $contentImgPopup = '<div class="main-publishing__content-img">
            <div class="content-img">
                <img src="' . $imagen_ruta . '" alt="publishing img">
            </div>
        </div>';
            $contentPopup .= $contentImgPopup;
        }
        // Elemento 5 del contenedor (Contenido de reacción)
        $contentReaction = '<div class="main-publishing__content-reation">
            <div class="contet-reaction">
                <i class="fa-solid fa-heart"></i>
                <span>0</span>
            </div>
        </div>';
        $contentPopup .= $contentReaction;
        // Cierre del popup
        $contentPopup .= '</div>';
        // Imprime el HTML del contenido del popup.
        echo $contentPopup;
    }
    // Método qué obtiene y muestra las publicaciones del perfil del usuario actual en orden cronológico.
    public function obtenerPublicacionesPerfil()
    {
        // Inicia la sesión si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Obtiene los datos del usuario actual
        $perfil = new Perfil();
        $userData = $perfil->obtenerDatosUsuario();
        $user_name = $userData['name'];
        $user_image = $userData['imagen_perfil'];
        // Obtiene el ID del usuario actual
        $user_id = $_SESSION['user_id'];
        // Consulta las publicaciones del usuario en orden cronológico
        $query = "SELECT * FROM publicaciones WHERE usuario_id = $user_id ORDER BY fecha_publicacion DESC";
        $result = $this->connection->query($query);
        // Si la consulta es exitosa, muestra las publicaciones
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $publicacion_id = $row['publicacion_id'];
                $texto = $row['texto'];
                $imagen_ruta = $row['imagen_ruta'];
                // Llama a la función 'publication' para mostrar el 'main-publishing'
                $this->publication($user_name, $user_image, $publicacion_id, $texto, $imagen_ruta);
                // Imprime el contenedor del popup
                echo '<div class="popup" id="popup_publishing" data-publicacion-id="' . $publicacion_id . '">';
                // Llama a la función 'publicationPopup' para mostrar el 'popup-publishing'
                $this->publicationPopup($user_name, $user_image, $publicacion_id, $texto, $imagen_ruta);
                // Cierra el contenedor del popup
                echo '</div>';
            }
        } else {
            // Si hay un error en la consulta, imprime un mensaje de error
            echo "Error al obtener las publicaciones del perfil.";
        }
    }
    // Método qué obtiene y muestra las publicaciones del perfil de un usuario público en orden cronológico.
    public function obtenerPublicacionesPerfilPublic($profile_id)
    {
        // Obtiene los datos del perfil del usuario público
        $perfil = new Perfil();
        $userData_P = $perfil->obtenerDatosUsuarioPublico($profile_id);
        $user_name = $userData_P['name'];
        $user_image = $userData_P['imagen_perfil'];
        // Consulta las publicaciones del usuario público en orden cronológico
        $query = "SELECT * FROM publicaciones WHERE usuario_id = $profile_id ORDER BY fecha_publicacion DESC";
        $result = $this->connection->query($query);
        // Si la consulta es exitosa, muestra las publicaciones
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $publicacion_id = $row['publicacion_id'];
                $texto = $row['texto'];
                $imagen_ruta = $row['imagen_ruta'];
                // Llama a la función 'publication' para mostrar el 'main-publishing'
                $this->publication($user_name, $user_image, $publicacion_id, $texto, $imagen_ruta);
                // Imprime el contenedor del popup
                echo '<div class="popup" id="popup_publishing" data-publicacion-id="' . $publicacion_id . '">';
                // Llama a la función 'publicationPopup' para mostrar el 'popup-publishing'
                $this->publicationPopup($user_name, $user_image, $publicacion_id, $texto, $imagen_ruta);
                // Cierra el contenedor del popup
                echo '</div>';
            }
        } else {
            // Si hay un error en la consulta, imprime un mensaje de error
            echo "Error al obtener las publicaciones del perfil.";
        }
    }
    // Método qué obtiene y muestra las publicaciones más recientes de los usuarios seguidos en la página de inicio.
    public function obtenerPublicacionesPaginaInicio()
    {
        // Verifica si la sesión está iniciada antes de acceder a $_SESSION
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Obtiene el ID del usuario de la sesión
        $user_id = $_SESSION['user_id'];
        // Consulta para obtener la lista de usuarios seguidos y sus publicaciones más recientes
        $query = "SELECT s.usuario_id AS seguido_id, p.publicacion_id, p.texto, p.imagen_ruta, p.fecha_publicacion, pr.name, pr.imagen_perfil
        FROM seguidos s
        JOIN publicaciones p ON s.usuario_id = p.usuario_id
        JOIN perfiles pr ON s.usuario_id = pr.usuario_id
        WHERE s.seguidor_id = $user_id
        ORDER BY p.fecha_publicacion DESC";
        $result = $this->connection->query($query);
        // Si la consulta es exitosa, muestra las publicaciones
        if ($result) {
            $publicaciones = array();
            while ($row = $result->fetch_assoc()) {
                $publicaciones[] = $row;
            }
            // Mezcla aleatoriamente el array de publicaciones
            shuffle($publicaciones);
            // Recorre las publicaciones y las muestra
            foreach ($publicaciones as $row) {
                $publicacion_id = $row['publicacion_id'];
                $texto = $row['texto'];
                $imagen_ruta = $row['imagen_ruta'];
                $user_name = $row['name'];
                $user_image = $row['imagen_perfil'];
                // Llama a la función 'publication' para mostrar el 'main-publishing'
                $this->publication($user_name, $user_image, $publicacion_id, $texto, $imagen_ruta);
                // Imprime el contenedor del popup
                echo '<div class="popup" id="popup_publishing" data-publicacion-id="' . $publicacion_id . '">';
                // Llama a la función 'publicationPopup' para mostrar el 'popup-publishing'
                $this->publicationPopup($user_name, $user_image, $publicacion_id, $texto, $imagen_ruta);
                // Cierra el contenedor del popup
                echo '</div>';
            }
        } else {
            // Si hay un error en la consulta, imprime un mensaje de error
            echo "Error al obtener las publicaciones de la página de inicio.";
        }
    }
}
// Crea una instancia de la clase 'Publicacion' 
$publicacion = new Publicacion();
// Verificar si se están subiendo datos
if (isset($_GET['subirDatos'])) {
    $publicacion->subirDatos();
}
?>
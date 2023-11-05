<?php
require_once('../db.php');
require_once('perfil.php');
class Publicacion
{
    private $db;
    private $connection;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
        $this->connection->set_charset("utf8mb4");
    }

    // Función para crear una nuvea publicación
    public function crearPublicacion()
    {
        $perfil = new Perfil();
        $user_name = $perfil->obtenerNombre();
        $user_image = $perfil->obtenerImagen();

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

        return $createHTML;
    }

    // Función para verificar los datos del lado del cliente
    public function subirDatos()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $perfil = new Perfil();
            $user_name = $perfil->obtenerNombre();
            // Asegúrate de que la sesión esté iniciada antes de intentar acceder a $_SESSION
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Obtén el ID del usuario de la sesión (asegúrate de que la sesión esté iniciada)
            $usuario_id = $_SESSION['user_id'];
            $texto = isset($_POST['texto']) ? $_POST['texto'] : "";

            // Verifica si se ha enviado una imagen
            if (isset($_FILES['imagen_ruta'])) {
                $this->subirImagen($user_name, $usuario_id, $texto);
            } else {
                $this->guardarPublicacion($usuario_id, $texto);
            }

        }
    }

    // Función para procesar la imagen enviada
    private function subirImagen($user_name, $usuario_id, $texto)
    {
        $directorio_usuario = '../Img/' . $user_name . '-' . $usuario_id . '/';

        // Verifica si el directorio del usuario ya existe, si no, créalo
        if (!file_exists($directorio_usuario)) {
            mkdir($directorio_usuario, 0777, true);
        }

        $nombre_archivo_original = $_FILES['imagen_ruta']['name']; // Obtenemos el nombre original del archivo
        $nombre_archivo = $this->generarNombreArchivo($nombre_archivo_original); // Generamos un nombre único
        $ruta_archivo = $directorio_usuario . $nombre_archivo;

        if (move_uploaded_file($_FILES['imagen_ruta']['tmp_name'], $ruta_archivo)) {
            // Luego, puedes guardar $ruta_archivo en la base de datos junto con otros datos
            return $this->guardarPublicacion($usuario_id, $texto, $ruta_archivo);
        } else {
            return array('success' => false, 'message' => 'Error al subir la imagen');
        }
    }

    // Función para cambiar el nombre del archivo subido
    private function generarNombreArchivo($nombreArchivoOriginal)
    {
        // Extrae la extensión del archivo original
        $extension = pathinfo($nombreArchivoOriginal, PATHINFO_EXTENSION);

        // Genera un nombre único para el archivo utilizando uniqid() y agrega la extensión
        return uniqid() . '.' . $extension;
    }

    // Función para hacer la inserción en la BD
    private function guardarPublicacion($usuario_id, $texto, $imagen_ruta = null)
    {
        $querySubirDatos = "INSERT INTO publicaciones (usuario_id, texto, imagen_ruta, fecha_publicacion) VALUES (?, ?, ?, NOW())";

        if ($imagen_ruta === null) {
            $querySubirDatos = "INSERT INTO publicaciones (usuario_id, texto, fecha_publicacion) VALUES (?, ?, NOW())";
        }

        $resultSubirDatos = $this->connection->prepare($querySubirDatos);

        if ($resultSubirDatos) {
            if ($imagen_ruta === null) {
                $resultSubirDatos->bind_param("is", $usuario_id, $texto);
            } else {
                $resultSubirDatos->bind_param("iss", $usuario_id, $texto, $imagen_ruta);
            }

            $success = false;
            $message = 'Error en la preparación de la sentencia SQL';
            $publicacion_id = null; // Agregamos una variable para almacenar el ID de la publicación

            if ($resultSubirDatos->execute()) {
                $success = true;
                $message = 'Publicación guardada con éxito';
                $publicacion_id = $this->connection->insert_id; // Obtenemos el ID de la publicación
            } else {
                $message = 'Error al guardar la publicación';
            }

            $resultSubirDatos->close();

            // Obtener el nombre y la imagen del usuario
            $perfil = new Perfil();
            $user_name = $perfil->obtenerNombre();
            $user_image = $perfil->obtenerImagen();

            // Devolvemos los datos de la publicación junto con el nombre e imagen del usuario
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
            // Si la preparación de la sentencia SQL falla, retornamos un mensaje de error
            echo json_encode(array('success' => false, 'message' => 'Error en la preparación de la sentencia SQL'));
        }
    }

    // Función para conocer el total de publicaciones hechas por un usuario
    public function obtenerTotalPublicaciones()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $user_id = $_SESSION['user_id'];

        $query = "SELECT COUNT(*) AS numero_publicaciones FROM publicaciones WHERE usuario_id = $user_id";
        $result = $this->connection->query($query);
        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $numero_publicaciones = $row['numero_publicaciones'];
                return $numero_publicaciones;
            } else {
                // Manejar el error
                echo "Error: La consulta falló";
            }
        }
    }

    // Funciones para crear las publicaciones dinamicamente
    function publication($user_name, $user_image, $publicacion_id, $texto, $imagen_ruta)
    {
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

        // Cerrar la nueva publicación
        $nuevaPublicacion .= '</div>';


        echo $nuevaPublicacion;
    }

    function publicationPopup($user_name, $user_image, $publicacion_id, $texto, $imagen_ruta)
    {
        // Contenedro hijo
        $contentPopup = '<div class="popup-publishing_content">';

        // Elemento hijo1 del contenedor hijo
        $btnClose = '<span class="close-button" id="close_popup_publishing"><i class="fa-solid fa-xmark"></i></span>';
        $contentPopup .= $btnClose;

        //Elemento hijo2 del contenedor hijo
        $contentUser = '<div class="main-publishing__users">
            <div class="users-info">
                <a href="../Vistas/perfil.php"><img src="' . $user_image . '" alt="Img profile"></a>
                <a href="../Vistas/perfil.php">' . $user_name . '</a>
            </div>
        </div>';
        $contentPopup .= $contentUser;

        //Elemento hijo3 del contenedor hijo
        // // Agregar contenido de texto si existe
        if (!empty($texto)) {
            $contentTextPopup = '<div class="main-publishing__content-text">
                <div class="content-text">
                    <p>' . $texto . '</p>
                </div>
            </div>';
            $contentPopup .= $contentTextPopup;
        }

        //Elemento hijo4 del contenedor hijo
        // // Agregar contenido de imagen si existe
        if (!empty($imagen_ruta)) {
            $contentImgPopup = '<div class="main-publishing__content-img">
                <div class="content-img">
                    <img src="' . $imagen_ruta . '" alt="publishing img">
                </div>
            </div>';
            $contentPopup .= $contentImgPopup;
        }
        //Elemento hijo5 del contenedor hijo

        $contentReaction = '<div class="main-publishing__content-reation">
            <div class="contet-reaction">
                <i class="fa-solid fa-heart"></i>
                <span>0</span>
            </div>
        </div>';
        $contentPopup .= $contentReaction;
        // Cerrar el popup
        $contentPopup .= '</div>';

        echo $contentPopup;
    }
    public function obtenerPublicacionesPerfil()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $perfil = new Perfil();
        $user_name = $perfil->obtenerNombre();
        $user_image = $perfil->obtenerImagen();

        $user_id = $_SESSION['user_id'];

        // Consultar las publicaciones del usuario en orden cronológico
        $query = "SELECT * FROM publicaciones WHERE usuario_id = $user_id ORDER BY fecha_publicacion DESC";
        $result = $this->connection->query($query);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $publicacion_id = $row['publicacion_id'];
                $texto = $row['texto'];
                $imagen_ruta = $row['imagen_ruta'];

                // Llama a la función 'publication' para mostrar el 'main-publishing'
                $this->publication($user_name, $user_image, $publicacion_id, $texto, $imagen_ruta);
                echo '<div class="popup-publishing" id="popup_publishing" data-publicacion-id="'. $publicacion_id .'">';
                // Llama a la función 'publicationPopup' para mostrar el 'popup-publishing'
                $this->publicationPopup($user_name, $user_image, $publicacion_id, $texto, $imagen_ruta);
                echo '</div>';
            }
        } else {
            echo "Error al obtener las publicaciones del perfil.";
        }
    }



    public function obtenerPublicacionesSeguidos()
    {
        $response = array();
        // Obtener datos de la publicación para perfiles seguidos
        // ...
        // $this->createPublication($response);
    }

    public function obtenerPublicacionesPaginaInicio()
    {
        $response = array();
        // Obtener datos de la publicación para la página de inicio
        // ...
        // $this->createPublication($response, true); // Es un popup
    }
}
$publicacion = new Publicacion();

if (isset($_GET['subirDatos'])) {
    $publicacion->subirDatos();
}
?>
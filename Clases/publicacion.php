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
    public function crearPublicacion()
    {
        $user_id = $_SESSION['user_id'];
        $queryCreate = "SELECT name, imagen_perfil FROM perfiles WHERE usuario_id = $user_id";
        $resultCreate = $this->connection->query($queryCreate);
        $createHTML = '';
        if ($resultCreate && $resultCreate->num_rows > 0) {
            $row = $resultCreate->fetch_assoc();
            $nombre = $row['name'];
            $imagen = $row['imagen_perfil'];
            $createHTML .= '<div class="popup-content">';
            $createHTML .= '<h2 class="create__title">Crear publicación</h2>';
            $createHTML .= '<span class="close-button" id="close_popup_create"><i class="fa-solid fa-xmark"></i></span>';
            $createHTML .= '<div class="create__line"></div>';
            $createHTML .= '<div class="main-publishing__users">';
            $createHTML .= '<div class="users-info">';
            $createHTML .= '<a href="link-perfil-user"><img src="' . $imagen . '" alt="Img profile"></a>';
            $createHTML .= '<a href="link-perfil-user">' . $nombre . '</a>';
            $createHTML .= '</div>';
            $createHTML .= '</div>';
            $createHTML .= '<div class="create__content-text">';
            $createHTML .= '<div class="create__text">';
            $createHTML .= '<textarea placeholder="¿Algo que quieras compartir,' . $nombre . '?" id="contentTextarea"></textarea>';
            $createHTML .= '</div>';
            $createHTML .= '</div>';
            $createHTML .= '<div class="create__content-img" id="imageContent">';
            $createHTML .= '<h3 class="create__title">Añade fotos o imagenes</h3>';
            $createHTML .= '<div id="file-upload-form" class="uploader">';
            $createHTML .= '<input id="file-upload" type="file" name="fileUpload" accept="image/*" />';
            $createHTML .= '<label for="file-upload" id="file-drag">';
            $createHTML .= '<img id="file-image" src="#" alt="Preview" class="hidden">';
            $createHTML .= '<div id="start">';
            $createHTML .= '<i class="fa fa-download" aria-hidden="true"></i>';
            $createHTML .= '<div>Selecciona un archvio o arrastralo aquí</div>';
            $createHTML .= '<div id="notimage" class="hidden">Por favor selecciona una imagen</div>';
            $createHTML .= '<span id="file-upload-btn" class="btn btn-primary">Selecciona un archivio</span>';
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
        }

        return $createHTML;
    }

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


    private function generarNombreArchivo($nombreArchivoOriginal)
    {
        // Extrae la extensión del archivo original
        $extension = pathinfo($nombreArchivoOriginal, PATHINFO_EXTENSION);

        // Genera un nombre único para el archivo utilizando uniqid() y agrega la extensión
        return uniqid() . '.' . $extension;
    }



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
}
$publicacion = new Publicacion();

if (isset($_GET['subirDatos'])) {
    $publicacion->subirDatos();
}
?>
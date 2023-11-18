<?php
// Incluir el archivo que contiene la lógica de la base de datos y la clase Database
require_once('../db.php');
// Definir la clase 'Perfil'
class Perfil
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
    // Método para obtener el nombre y foto de perfil del usuario
    public function obtenerDatosUsuario()
    {
        // Verifica si la sesión está iniciada, y si no, la inicia
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Obtiene el ID de usuario de la sesión actual
        $user_id = $_SESSION['user_id'];
        // Construye la consulta SQL para obtener el nombre e imagen de perfil del usuario
        $query = "SELECT name, imagen_perfil FROM perfiles WHERE usuario_id = $user_id";
        // Ejecuta la consulta en la conexión a la base de datos
        $result = $this->connection->query($query);
        // Verifica si la consulta se ejecutó con éxito
        if ($result) {
            if ($result->num_rows > 0) {
                // Verifica si se obtuvieron resultados de la consulta
                $row = $result->fetch_assoc();
                // Extrae el nombre de usuario y la imagen de perfil de la fila
                $user_name = $row['name'];
                $user_image = $row['imagen_perfil'];
            } else {
                // Si no se encontraron resultados, establece valores predeterminados
                $user_name = "Usuario Desconocido";
                $user_image = "../Img/User-Profile.png"; // Ruta predeterminada
            }
        } else {
            // Si hubo un error en la consulta, establece valores predeterminados
            $user_name = "Error en la consulta";
            $user_image = "../Img/User-Profile.png"; // Ruta predeterminada
        }
        // Devuelve un arreglo asociativo con el nombre de usuario y la imagen de perfil
        return ['name' => $user_name, 'imagen_perfil' => $user_image];
    }
    // Método para obtener el nombre y la foto de perfil de un perfil publico
    public function obtenerDatosUsuarioPublico($profile_id)
    {
        // Construye la consulta SQL para obtener el nombre e imagen de perfil del usuario público
        $query = "SELECT name, imagen_perfil FROM perfiles WHERE usuario_id = $profile_id";
        // Ejecuta la consulta en la conexión a la base de datos
        $result = $this->connection->query($query);
        // Verifica si la consulta se ejecutó con éxito
        if ($result) {
            // Verifica si se obtuvieron resultados de la consulta
            if ($result->num_rows > 0) {
                // Obtiene la primera fila de los resultados
                $row = $result->fetch_assoc();
                // Extrae el nombre de usuario y la imagen de perfil de la fila
                $user_name_p = $row['name'];
                $user_image_p = $row['imagen_perfil'];
            } else {
                // Si no se encontraron resultados, establece valores predeterminados
                $user_name_p = "Usuario Desconocido";
                $user_image_p = "../Img/User-Profile.png"; // Ruta predeterminada
            }
        } else {
            // Si hubo un error en la consulta, establece valores predeterminados
            $user_name_p = "Error en la consulta";
            $user_image_p = "../Img/User-Profile.png"; // Ruta predeterminada
        }
        // Devuelve un arreglo asociativo con el nombre de usuario y la imagen de perfil del usuario público
        return ['name' => $user_name_p, 'imagen_perfil' => $user_image_p];
    }
    // Método para procesar y subir los datos proporcionados para actulizar perfil
    public function updateUser()
    {
        // Verifica si la sesión está iniciada, y si no, la inicia
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Verifica si la solicitud es de tipo POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene el ID de usuario de la sesión actual
            $user_id = $_SESSION['user_id'];
            // Recoge los datos del formulario
            $name = isset($_POST['name']) ? $_POST['name'] : null;
            $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : null;
            $dateOfBirth = isset($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] : null;
            $country = isset($_POST['country']) ? $_POST['country'] : null;
            $city = isset($_POST['city']) ? $_POST['city'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;
            // Genera un hash para la contraseña
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Verifica si se ha subido una imagen
            if (isset($_FILES['image_profile'])) {
                // Procesa la imagen y obtiene su ruta
                $image_profile = $this->procesarImagen($user_id, $_FILES['image_profile']);
                // Verifica si hubo un error al subir la imagen
                if (!$image_profile) {
                    return json_encode(['success' => false, 'message' => 'Error al subir la imagen']);
                }
            } else {
                // Si no se subió una imagen, establece la variable como nula
                $image_profile = null;
            }
            // Aplica formato a los datos si es necesario
            $nameFormatted = ($name !== null) ? ucfirst(strtolower($name)) : null;
            $lastNameFormatted = ($lastName !== null) ? ucfirst(strtolower($lastName)) : null;
            $dateOfBirthFormatted = ($dateOfBirth !== null) ? date('Y-m-d', strtotime(str_replace('/', '-', $dateOfBirth))) : null;
            // Actualiza los datos de la tabla "perfiles"
            $this->updateProfileData($user_id, $nameFormatted, $lastNameFormatted, $dateOfBirthFormatted, $country, $city, $image_profile);
            // Actualiza los datos de la tabla "usuario"
            $this->updateUserData($user_id, $email, $hashedPassword);
        }
    }
    // Método para crear una consulta prepara con los datos proporcionados (tabla perfiles)
    public function updateProfileData($user_id, $name, $lastName, $dateOfBirth, $country, $city, $image_profile)
    {
        // Arreglos para almacenar los campos de actualización y los parámetros
        $updateFields = [];
        $params = [];
        $responseData = [];
        // Verifica y construye la actualización para el campo 'name'
        if ($name !== null) {
            $updateFields[] = "name = ?";
            $params[] = $name;
            $responseData['name'] = $name;
        }
        // Verifica y construye la actualización para el campo 'last_name'
        if ($lastName !== null) {
            $updateFields[] = "last_name = ?";
            $params[] = $lastName;
            $responseData['last_name'] = $lastName;
        }
        // Verifica y construye la actualización para el campo 'date_of_birth'
        if ($dateOfBirth !== null) {
            $updateFields[] = "date_of_birth = ?";
            $params[] = $dateOfBirth;
            $responseData['date_of_birth'] = $dateOfBirth;
        }
        // Verifica y construye la actualización para el campo 'country'
        if ($country !== null) {
            $updateFields[] = "country = ?";
            $params[] = $country;
            $responseData['country'] = $country;
        }
        // Verifica y construye la actualización para el campo 'city'
        if ($city !== null) {
            $updateFields[] = "city = ?";
            $params[] = $city;
            $responseData['city'] = $city;
        }
        // Verifica y construye la actualización para el campo 'imagen_perfil'
        if ($image_profile !== null) {
            $updateFields[] = "imagen_perfil = ?";
            $params[] = $image_profile;
            $responseData['imagen_perfil'] = $image_profile;
        }
        // Verifica si hay campos para actualizar
        if (!empty($updateFields)) {
            // Construye la parte SET de la consulta SQL
            $updateFieldsStr = implode(', ', $updateFields);
            // Construye la consulta SQL completa
            $query = "UPDATE perfiles SET $updateFieldsStr WHERE usuario_id = ?";
            // Prepara la consulta
            $stmt = $this->connection->prepare($query);
            // Verifica si la preparación fue exitosa
            if ($stmt) {
                // Añade el ID de usuario como último parámetro
                $params[] = $user_id;
                // Construye la cadena de tipos de parámetros
                $types = str_repeat('s', count($params));
                // Vincula los parámetros a la consulta preparada
                $stmt->bind_param($types, ...$params);
                // Ejecuta la consulta preparada
                $result = $stmt->execute();
                // Cierra la consulta preparada
                $stmt->close();
                // Verifica el resultado de la ejecución
                if ($result) {
                    // Actualización exitosa
                    echo json_encode(['success' => true, 'message' => 'Actualización exitosa', 'data' => $responseData]);
                    exit();
                } else {
                    // Error en la ejecución de la consulta
                    echo json_encode(['success' => false, 'message' => 'Error en la actualización del usuario: ' . $this->connection->error]);
                    exit();
                }
            } else {
                // Error en la preparación de la consulta
                echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta del usuario: ' . $this->connection->error]);
                exit();
            }
        }
        // No hay campos para actualizar
        echo json_encode(['success' => false, 'message' => 'No hay campos para actualizar']);
        exit();
    }
    // Método para crear una consulta prepara con los datos proporcionados (tabla usuario)
    public function updateUserData($user_id, $email, $password)
    {
        // Arreglos para almacenar los campos de actualización y los parámetros
        $updateFields = [];
        $params = [];
        $responseData = [];
        // Verifica y construye la actualización para el campo 'email'
        if ($email !== null) {
            $updateFields[] = "email = ?";
            $params[] = $email;
            $responseData['email'] = $email;
        }
        // Verifica y construye la actualización para el campo 'password'
        if ($password !== null) {
            $updateFields[] = "password = ?";
            $params[] = $password;
            $responseData['password'] = $password;
        }
        // Verifica si hay campos para actualizar
        if (!empty($updateFields)) {
            // Construye la parte SET de la consulta SQL
            $updateFieldsStr = implode(', ', $updateFields);
            // Construye la consulta SQL completa
            $query = "UPDATE usuario SET $updateFieldsStr WHERE usuario_id = ?";
            // Prepara la consulta
            $stmt = $this->connection->prepare($query);
            // Verifica si la preparación fue exitosa
            if ($stmt) {
                // Añade el ID de usuario como último parámetro
                $params[] = $user_id;
                // Construye la cadena de tipos de parámetros
                $types = str_repeat('s', count($params));
                // Vincula los parámetros a la consulta preparada
                $stmt->bind_param($types, ...$params);
                // Ejecuta la consulta preparada
                $result = $stmt->execute();
                // Cierra la consulta preparada
                $stmt->close();
                // Verifica el resultado de la ejecución
                if ($result) {
                    // Actualización exitosa
                    echo json_encode(['success' => true, 'message' => 'Actualización exitosa', 'data' => $responseData]);
                    exit();
                } else {
                    // Error en la ejecución de la consulta
                    echo json_encode(['success' => false, 'message' => 'Error en la actualización del usuario: ' . $this->connection->error]);
                    exit();
                }
            } else {
                // Error en la preparación de la consulta
                echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta del usuario: ' . $this->connection->error]);
                exit();
            }
        }
        // No hay campos para actualizar
        echo json_encode(['success' => false, 'message' => 'No hay campos para actualizar']);
        exit();
    }
    //Método para procesar la imagen de perfil suministrada por un usuario
    private function procesarImagen($user_id, $imagen)
    {
        // Lógica para obtener el directorio y generar un nombre de archivo único.
        $directorio_usuario = '../Img/Perfil_ID__' . $user_id . '/';
        // Verifica si el directorio del usuario ya existe, si no, créalo
        if (!file_exists($directorio_usuario)) {
            mkdir($directorio_usuario, 0777, true);
        }
        // Obtiene el nombre original del archivo
        $nombre_archivo_original = $imagen['name'];
        // Obtiene la extensión del archivo
        $extension = pathinfo($nombre_archivo_original, PATHINFO_EXTENSION);
        // Genera un nombre único para el archivo
        $nombre_archivo = uniqid() . '.' . $extension;
        // Construye la ruta completa del archivo
        $ruta_archivo = $directorio_usuario . $nombre_archivo;
        // Intenta mover el archivo a la ruta especificada
        if (move_uploaded_file($imagen['tmp_name'], $ruta_archivo)) {
            // Si la operación fue exitosa, devuelve la ruta del archivo
            return $ruta_archivo;
        } else {
            // Si hubo un error al mover el archivo, devuelve false
            return false;
        }
    }
    // Método para manejar la "eliminación" de un usuario
    public function deleteUser()
    {
        // Verifica si la sesión está iniciada, y si no, la inicia
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Obtiene el ID de usuario de la sesión actual
        $user_id = $_SESSION['user_id'];
        // Obtiene la contraseña proporcionada en el formulario
        $password = $_POST['password'];
        // Inicializa $hashed_password con un valor predeterminado
        $hashed_password = null;
        // Obtiene la contraseña almacenada en la base de datos
        $query = "SELECT password FROM usuario WHERE usuario_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();
        // Verifica la contraseña
        if (!is_null($hashed_password) && password_verify($password, $hashed_password)) {
            // Contraseña válida, procede con la eliminación
            // Actualiza el estado del usuario a 'Inactivo'
            $query = "UPDATE usuario SET state = 'Inactivo' WHERE usuario_id = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
            // Envía una respuesta JSON indicando éxito
            echo json_encode(["success" => true]);
        } else {
            // Contraseña incorrecta
            // Envía una respuesta JSON indicando fallo
            echo json_encode(["success" => false, "message" => "Contraseña incorrecta, intentelo de nuevo"]);
        }
    }
}
// Crea una instancia de la clase 'Perfil' 
$perfil = new Perfil();
// Verificar si se están subiendo datos
if (isset($_GET['update'])) {
    $perfil->updateUser();
}
// Verificar si se solicita una eliminación
if (isset($_GET['delete'])) {
    $perfil->deleteUser();
}
?>
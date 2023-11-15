<?php
require_once('../db.php');
class Perfil
{
    private $db;
    private $connection;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->connection = $this->db->getConnection();
        $this->connection->set_charset("utf8mb4");
    }

    public function obtenerDatosUsuario()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $user_id = $_SESSION['user_id'];

        $query = "SELECT name, imagen_perfil FROM perfiles WHERE usuario_id = $user_id";
        $result = $this->connection->query($query);

        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_name = $row['name'];
                $user_image = $row['imagen_perfil'];
            } else {
                $user_name = "Usuario Desconocido";
                $user_image = "../Img/User-Profile.png"; // Ruta predeterminada
            }
        } else {
            $user_name = "Error en la consulta";
            $user_image = "../Img/User-Profile.png"; // Ruta predeterminada
        }

        return ['name' => $user_name, 'imagen_perfil' => $user_image];
    }

    public function obtenerDatosUsuarioPublico($profile_id)
    {
        $query = "SELECT name, imagen_perfil FROM perfiles WHERE usuario_id = $profile_id";
        $result = $this->connection->query($query);

        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_name_p = $row['name'];
                $user_image_p = $row['imagen_perfil'];
            } else {
                $user_name_p = "Usuario Desconocido";
                $user_image_p = "../Img/User-Profile.png"; // Ruta predeterminada
            }
        } else {
            $user_name_p = "Error en la consulta";
            $user_image_p = "../Img/User-Profile.png"; // Ruta predeterminada
        }

        return ['name' => $user_name_p, 'imagen_perfil' => $user_image_p];
    }


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
    public function updateUser()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            // Recoge los datos del formulario
            $name = isset($_POST['name']) ? $_POST['name'] : null;
            $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : null;
            $dateOfBirth = isset($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] : null;
            $country = isset($_POST['country']) ? $_POST['country'] : null;
            $city = isset($_POST['city']) ? $_POST['city'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Verifica si se ha subido una imagen
            if (isset($_FILES['image_profile'])) {
                $image_profile = $this->procesarImagen($user_id, $_FILES['image_profile']);
                if (!$image_profile) {
                    return json_encode(['success' => false, 'message' => 'Error al subir la imagen']);
                }
            } else {
                $image_profile = null;
            }

            // Aplica formato a los datos si es necesario
            $nameFormatted = ($name !== null) ? ucfirst(strtolower($name)) : null;
            $lastNameFormatted = ($lastName !== null) ? ucfirst(strtolower($lastName)) : null;
            $dateOfBirthFormatted = ($dateOfBirth !== null) ? date('Y-m-d', strtotime(str_replace('/', '-', $dateOfBirth))) : null;

            // Actualizar datos de la tabla "perfiles"
            $this->updateProfileData($user_id, $nameFormatted, $lastNameFormatted, $dateOfBirthFormatted, $country, $city, $image_profile);

            // Actualizar datos de la tabla "usuario"
            $this->updateUserData($user_id, $email, $hashedPassword);
        }
    }

    public function updateProfileData($user_id, $name, $lastName, $dateOfBirth, $country, $city, $image_profile)
    {
        $updateFields = [];
        $params = [];
        $responseData = [];

        if ($name !== null) {
            $updateFields[] = "name = ?";
            $params[] = $name;
            $responseData['name'] = $name;
        }

        if ($lastName !== null) {
            $updateFields[] = "last_name = ?";
            $params[] = $lastName;
            $responseData['last_name'] = $lastName;
        }

        if ($dateOfBirth !== null) {
            $updateFields[] = "date_of_birth = ?";
            $params[] = $dateOfBirth;
            $responseData['date_of_birth'] = $dateOfBirth;
        }

        if ($country !== null) {
            $updateFields[] = "country = ?";
            $params[] = $country;
            $responseData['country'] = $country;
        }

        if ($city !== null) {
            $updateFields[] = "city = ?";
            $params[] = $city;
            $responseData['city'] = $city;
        }

        if ($image_profile !== null) {
            $updateFields[] = "imagen_perfil = ?";
            $params[] = $image_profile;
            $responseData['imagen_perfil'] = $image_profile;
        }

        if (!empty($updateFields)) {
            $updateFieldsStr = implode(', ', $updateFields);
            $query = "UPDATE perfiles SET $updateFieldsStr WHERE usuario_id = ?";
            $stmt = $this->connection->prepare($query);

            if ($stmt) {
                $params[] = $user_id;
                $types = str_repeat('s', count($params));
                $stmt->bind_param($types, ...$params);
                $result = $stmt->execute();
                $stmt->close();

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


    public function updateUserData($user_id, $email, $password)
    {
        $updateFields = [];
        $params = [];
        $responseData = [];

        if ($email !== null) {
            $updateFields[] = "email = ?";
            $params[] = $email;
            $responseData['email'] = $email;
        }

        if ($password !== null) {
            $updateFields[] = "password = ?";
            $params[] = $password;
            $responseData['password'] = $password;
        }

        if (!empty($updateFields)) {
            $updateFieldsStr = implode(', ', $updateFields);
            $query = "UPDATE usuario SET $updateFieldsStr WHERE usuario_id = ?";
            $stmt = $this->connection->prepare($query);
            if ($stmt) {
                $params[] = $user_id;
                $types = str_repeat('s', count($params));
                $stmt->bind_param($types, ...$params);
                $result = $stmt->execute();
                $stmt->close();

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
    private function procesarImagen($user_id, $imagen)
    {
        // Lógica para obtener el directorio y generar un nombre de archivo único (mismo que en subirImagen).
        $directorio_usuario = '../Img/Perfil_ID__' . $user_id . '/';

        // Verifica si el directorio del usuario ya existe, si no, créalo
        if (!file_exists($directorio_usuario)) {
            mkdir($directorio_usuario, 0777, true);
        }

        $nombre_archivo_original = $imagen['name']; // Obtenemos el nombre original del archivo
        $extension = pathinfo($nombre_archivo_original, PATHINFO_EXTENSION);
        $nombre_archivo = uniqid() . '.' . $extension; // Generamos un nombre único
        $ruta_archivo = $directorio_usuario . $nombre_archivo;

        if (move_uploaded_file($imagen['tmp_name'], $ruta_archivo)) {
            // Luego, puedes guardar $ruta_archivo en la base de datos o realizar otras acciones según tus necesidades.
            return $ruta_archivo;
        } else {
            return false;
        }
    }



    public function deleteUser()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $user_id = $_SESSION['user_id'];
    // Obtén la contraseña del formulario
    $password = $_POST['password'];
    // Inicializa $hashed_password con un valor predeterminado
    $hashed_password = null;

    // Obtén la contraseña almacenada en la base de datos
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

$perfil = new Perfil();
if (isset($_GET['update'])) {
    $perfil->updateUser();
}
if (isset($_GET['delete'])) {
    $perfil->deleteUser();
}
?>